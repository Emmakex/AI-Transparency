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

test.describe.configure({ mode: 'serial' });

test.describe('WordPress runtime acceptance', () => {
  test('AI Engine 3.7.7 is discovered with explainable evidence and requires explicit registry acceptance', async ({ page }) => {
    await login(page, adminUser, adminPass);
    await page.goto('/wp-admin/tools.php?page=ai-transparency-discovery');

    await expect(page.getByRole('heading', { level: 1, name: 'AI Discovery' })).toBeVisible();
    await expect(page.getByRole('cell', { name: 'AI Engine', exact: true })).toBeVisible();
    await expect(page.getByRole('cell', { name: '3.7.7', exact: true }).first()).toBeVisible();
    await expect(page.getByRole('cell', { name: 'Supported', exact: true })).toBeVisible();
    await expect(page.getByText('ai-engine/ai-engine.php', { exact: true })).toBeVisible();
    await expect(page.getByText('ai-engine', { exact: true }).last()).toBeVisible();

    const signature = page.getByRole('row').filter({ hasText: 'Evidence signature' }).locator('code');
    await expect(signature).toHaveText(/^[a-f0-9]{64}$/);

    await page.setViewportSize({ width: 390, height: 844 });
    const horizontalOverflow = await page.evaluate(() =>
      document.documentElement.scrollWidth - document.documentElement.clientWidth
    );
    expect(horizontalOverflow).toBeLessThanOrEqual(1);

    const accessibility = await new AxeBuilder({ page })
      .include('.ai-transparency-admin')
      .analyze();
    const blockingViolations = accessibility.violations.filter((violation) =>
      ['critical', 'serious'].includes(violation.impact)
    );
    expect(blockingViolations, JSON.stringify(blockingViolations, null, 2)).toEqual([]);

    await page.getByRole('button', { name: 'Add to registry' }).click();
    await expect(page.locator('.notice-success')).toContainText(
      'Detected AI integration added to the registry for administrator review.'
    );
    await expect(page.getByText('Already in registry', { exact: true })).toBeVisible();

    await page.goto('/wp-admin/tools.php?page=ai-transparency');
    const row = rowForSystem(page, 'AI Engine');
    await expect(row).toContainText('Other');
    await expect(row).toContainText('Pending review');
    await expect(row).toContainText('Active');
  });

  test('administrator can add, edit, review and archive a registry record', async ({ page }) => {
    const runId = `${Date.now()}-${Math.random().toString(16).slice(2, 8)}`;
    const systemName = `Phase 2 Runtime Assistant ${runId}`;
    const updatedName = `${systemName} Updated`;

    await login(page, adminUser, adminPass);
    await page.goto('/wp-admin/tools.php?page=ai-transparency');

    await expect(page.getByRole('heading', { level: 1, name: 'Kairoseth AI Transparency' })).toBeVisible();
    await expect(page.getByRole('heading', { name: 'AI Systems Registry' })).toBeVisible();

    await page.getByLabel('System name').fill(systemName);
    await page.getByLabel('System type').selectOption('assistant');
    await page.getByLabel('Interaction context').fill('Customer support assistant on the public help flow.');
    await page.getByLabel('Review status').selectOption('reviewed');
    await page.getByRole('checkbox').check();
    await page.getByRole('button', { name: 'Add AI system' }).click();

    await expect(page.locator('.notice-success')).toContainText('AI system saved.');

    let row = rowForSystem(page, systemName);
    await expect(row).toContainText('Assistant');
    await expect(row).toContainText('Reviewed');
    await expect(row).toContainText('Active');

    await row.getByRole('link', { name: 'Edit' }).click();
    await expect(page.getByRole('heading', { name: 'Edit AI system' })).toBeVisible();
    await page.getByLabel('System name').fill(updatedName);
    await page.getByLabel('Review status').selectOption('pending');
    await page.getByRole('button', { name: 'Update AI system' }).click();

    row = rowForSystem(page, updatedName);
    await expect(row).toContainText('Pending review');
    await expect(row).toContainText('Active');

    await row.getByRole('button', { name: 'Archive' }).click();
    await expect(page.locator('.notice-success')).toContainText('AI system archived.');

    row = rowForSystem(page, updatedName);
    await expect(row).toContainText('Archived');
    await expect(row.getByRole('button', { name: 'Archive' })).toHaveCount(0);
  });

  test('admin surface passes responsive and serious accessibility acceptance', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await login(page, adminUser, adminPass);
    await page.goto('/wp-admin/tools.php?page=ai-transparency');

    const horizontalOverflow = await page.evaluate(() =>
      document.documentElement.scrollWidth - document.documentElement.clientWidth
    );
    expect(horizontalOverflow).toBeLessThanOrEqual(1);

    const tableRegion = page.locator('.ai-transparency-table-wrap');
    await expect(tableRegion).toBeVisible();
    await tableRegion.focus();
    await expect(tableRegion).toBeFocused();

    await expect(page.getByLabel('System name')).toBeVisible();
    await expect(page.getByLabel('System type')).toBeVisible();
    await expect(page.getByLabel('Interaction context')).toBeVisible();
    await expect(page.getByLabel('Review status')).toBeVisible();

    const accessibility = await new AxeBuilder({ page })
      .include('.ai-transparency-admin')
      .analyze();

    const blockingViolations = accessibility.violations.filter((violation) =>
      ['critical', 'serious'].includes(violation.impact)
    );

    expect(
      blockingViolations,
      JSON.stringify(blockingViolations, null, 2)
    ).toEqual([]);
  });

  test('non-administrator cannot access registry or discovery administration surfaces', async ({ page }) => {
    await login(page, editorUser, editorPass);

    await page.goto('/wp-admin/tools.php?page=ai-transparency');
    await expect(page.locator('body')).not.toContainText('AI Systems Registry');
    await expect(page.locator('body')).toContainText(/not allowed|permission/i);

    await page.goto('/wp-admin/tools.php?page=ai-transparency-discovery');
    await expect(page.locator('body')).not.toContainText('Deterministic discovery');
    await expect(page.locator('body')).toContainText(/not allowed|permission/i);
  });
});
