const fs = require('fs/promises');
const { test, expect } = require('@playwright/test');
const AxeBuilder = require('@axe-core/playwright').default;

const adminUser = process.env.WP_TEST_ADMIN_USER;
const adminPass = process.env.WP_TEST_ADMIN_PASS;
const editorUser = process.env.WP_TEST_EDITOR_USER;
const editorPass = process.env.WP_TEST_EDITOR_PASS;

async function login(page, username, password) {
  if (!username || !password) {
    throw new Error('WordPress runtime test credentials were not provided.');
  }

  await page.goto('/wp-login.php');
  await page.locator('#user_login').fill(username);
  await page.locator('#user_pass').fill(password);
  await Promise.all([
    page.waitForURL(/\/wp-admin\//),
    page.locator('#wp-submit').click(),
  ]);
}

function rowForSystem(page, systemName) {
  return page.getByRole('cell', { name: systemName, exact: true }).locator('..');
}

function collectKeys(value, keys = new Set()) {
  if (Array.isArray(value)) {
    for (const item of value) {
      collectKeys(item, keys);
    }
    return keys;
  }

  if (value && typeof value === 'object') {
    for (const [key, item] of Object.entries(value)) {
      keys.add(key);
      collectKeys(item, keys);
    }
  }

  return keys;
}

async function requestJsonEvidence(page) {
  const nonce = await page.locator('input[name="_kat_evidence_export_nonce"]').inputValue();
  expect(nonce.length).toBeGreaterThan(0);

  const response = await page.request.post('/wp-admin/admin-post.php', {
    form: {
      action: 'ai_transparency_export_evidence',
      _kat_evidence_export_nonce: nonce,
    },
  });

  expect(response.status()).toBe(200);

  const headers = response.headers();
  expect(headers['content-type']).toContain('application/json');
  expect(headers['content-type']).toContain('charset=UTF-8');
  expect(headers['content-disposition']).toMatch(
    /attachment; filename="kairoseth-ai-transparency-evidence-\d{8}T\d{6}Z\.json"/
  );
  expect(headers['cache-control']).toContain('no-store');
  expect(headers['cache-control']).toContain('no-cache');

  const text = await response.text();
  expect(() => JSON.parse(text)).not.toThrow();

  return JSON.parse(text);
}

test.describe.configure({ mode: 'serial' });

test.describe('Phase 6 Evidence Export runtime acceptance', () => {
  test('administrator downloads a deterministic privacy-bounded site-local JSON snapshot', async ({ page }) => {
    const runId = `${Date.now()}-${Math.random().toString(16).slice(2, 8)}`;
    const systemName = `Phase 6 Evidence Assistant ${runId}`;
    const context = `Phase 6 confidential operational context ${runId}.`;
    const changedContext = `${context} Updated.`;

    await login(page, adminUser, adminPass);
    await page.goto('/wp-admin/tools.php?page=ai-transparency');

    await page.getByLabel('System name').fill(systemName);
    await page.getByLabel('System type').selectOption('assistant');
    await page.getByLabel('Interaction context').fill(context);
    await page.getByLabel('Review status').selectOption('reviewed');
    await page.getByRole('checkbox').check();
    await page.getByRole('button', { name: 'Add AI system' }).click();
    await expect(page.locator('.notice-success')).toContainText('AI system saved.');

    const registryRow = rowForSystem(page, systemName);
    const editHref = await registryRow.getByRole('link', { name: 'Edit' }).getAttribute('href');
    expect(editHref).toBeTruthy();
    const systemId = new URL(editHref).searchParams.get('system');
    expect(systemId).toBeTruthy();

    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/wp-admin/tools.php?page=ai-transparency-evidence-export');

    await expect(page.getByRole('heading', { level: 1, name: 'AI Evidence Export' })).toBeVisible();
    await expect(page.getByText('Confidentiality:', { exact: true })).toBeVisible();
    await expect(page.getByText(/does not upload, email or persist/i)).toBeVisible();
    await expect(page.getByRole('button', { name: 'Download JSON evidence' })).toBeVisible();

    const overflow390 = await page.evaluate(() =>
      document.documentElement.scrollWidth - document.documentElement.clientWidth
    );
    expect(overflow390).toBeLessThanOrEqual(1);

    await page.locator('html').evaluate((element) => {
      element.style.fontSize = '200%';
    });
    const overflowZoom = await page.evaluate(() =>
      document.documentElement.scrollWidth - document.documentElement.clientWidth
    );
    expect(overflowZoom).toBeLessThanOrEqual(1);

    const accessibility = await new AxeBuilder({ page })
      .include('.ai-transparency-admin')
      .analyze();
    const blockingViolations = accessibility.violations.filter((violation) =>
      ['critical', 'serious'].includes(violation.impact)
    );
    expect(blockingViolations, JSON.stringify(blockingViolations, null, 2)).toEqual([]);

    await page.locator('html').evaluate((element) => {
      element.style.fontSize = '';
    });

    const downloadPromise = page.waitForEvent('download');
    await page.getByRole('button', { name: 'Download JSON evidence' }).click();
    const download = await downloadPromise;

    expect(download.suggestedFilename()).toMatch(
      /^kairoseth-ai-transparency-evidence-\d{8}T\d{6}Z\.json$/
    );

    const downloadPath = await download.path();
    expect(downloadPath).toBeTruthy();
    const downloadedText = await fs.readFile(downloadPath, 'utf8');
    const first = JSON.parse(downloadedText);

    expect(first.export_schema_version).toBe(1);
    expect(first.generated_at).toMatch(/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/);
    expect(first.snapshot_signature).toMatch(/^[a-f0-9]{64}$/);
    expect(first.generator).toEqual({
      plugin_slug: 'ai-transparency',
      plugin_name: 'Kairoseth AI Transparency',
      plugin_version: '1.0.0',
    });
    expect(first.site.blog_id).toBeGreaterThan(0);
    expect(typeof first.site.is_multisite).toBe('boolean');
    expect(first.site.home_url).toMatch(/^https?:\/\//);

    const exportedSystem = first.registry.systems.find((system) => system.id === systemId);
    expect(exportedSystem).toBeTruthy();
    expect(exportedSystem.name).toBe(systemName);
    expect(exportedSystem.interaction_context).toBe(context);
    expect(exportedSystem.review_status).toBe('reviewed');
    expect(exportedSystem.interaction_disclosure_required).toBe(true);

    const finding = first.findings.find(
      (item) => item.subject_system_id === systemId && item.rule_id === 'configured_disclosure_review_v1'
    );
    expect(finding).toBeTruthy();
    expect(finding.evidence_signature).toMatch(/^[a-f0-9]{64}$/);
    expect(finding).not.toHaveProperty('generated_at');

    const readiness = first.disclosure_readiness.find((item) => item.system_id === systemId);
    expect(readiness).toEqual({
      system_id: systemId,
      eligible: true,
      reason_codes: [],
    });

    expect(first.discovery_evidence.some((item) => item.system_id === systemId)).toBe(false);

    const keys = collectKeys(first);
    for (const forbiddenKey of [
      'password',
      'api_key',
      'access_token',
      'refresh_token',
      'cookie',
      'cookies',
      'nonce',
      'headers',
      'user_id',
      'user_email',
      'prompt',
      'prompts',
      'conversation',
      'conversations',
      'logs',
    ]) {
      expect(keys.has(forbiddenKey)).toBe(false);
    }

    const second = await requestJsonEvidence(page);
    expect(second.snapshot_signature).toBe(first.snapshot_signature);

    await page.goto(editHref);
    await page.getByLabel('Interaction context').fill(changedContext);
    await page.getByRole('button', { name: 'Update AI system' }).click();
    await expect(page.locator('.notice-success')).toContainText('AI system saved.');

    await page.goto('/wp-admin/tools.php?page=ai-transparency-evidence-export');
    const changed = await requestJsonEvidence(page);
    expect(changed.snapshot_signature).not.toBe(first.snapshot_signature);

    const changedSystem = changed.registry.systems.find((system) => system.id === systemId);
    expect(changedSystem.interaction_context).toBe(changedContext);
  });

  test('editor cannot access or generate the privileged evidence export', async ({ page }) => {
    await login(page, editorUser, editorPass);

    await page.goto('/wp-admin/tools.php?page=ai-transparency-evidence-export');
    await expect(page.locator('body')).not.toContainText('Download JSON evidence');
    await expect(page.locator('body')).toContainText(/not allowed|permission/i);

    const response = await page.request.post('/wp-admin/admin-post.php', {
      form: {
        action: 'ai_transparency_export_evidence',
      },
    });

    expect(response.headers()['content-type'] || '').not.toContain('application/json');
    const body = await response.text();
    expect(body).toMatch(/not allowed|permission/i);
  });
});
