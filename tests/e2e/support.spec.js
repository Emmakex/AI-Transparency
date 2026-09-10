const { test, expect } = require('@playwright/test');
const AxeBuilder = require('@axe-core/playwright').default;

const adminUser = process.env.WP_TEST_ADMIN_USER;
const adminPass = process.env.WP_TEST_ADMIN_PASS;
const editorUser = process.env.WP_TEST_EDITOR_USER;
const editorPass = process.env.WP_TEST_EDITOR_PASS;

const allowedKeys = [
  'source',
  'extensionSlug',
  'extensionName',
  'extensionVersion',
  'hostPlatform',
  'hostPlatformVersion',
  'locale',
  'requestType',
];

const forbiddenKeys = [
  'siteUrl',
  'homeUrl',
  'registry',
  'systems',
  'interaction_context',
  'evidence',
  'snapshot_signature',
  'userEmail',
  'userId',
  'prompt',
  'conversation',
  'logs',
  'credentials',
];

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

function assertBoundedUrl(href, expectedRequestType) {
  const url = new URL(href);
  expect(url.protocol).toBe('https:');
  expect(url.hostname).toBe('kairoseth.com');
  expect(url.port).toBe('');
  expect(url.pathname).toBe('/custom-requests');
  expect(url.hash).toBe('');
  expect(Array.from(url.searchParams.keys())).toEqual(allowedKeys);
  expect(url.searchParams.get('source')).toBe('extension');
  expect(url.searchParams.get('extensionSlug')).toBe('ai-transparency');
  expect(url.searchParams.get('extensionName')).toBe('Kairoseth AI Transparency');
  expect(url.searchParams.get('extensionVersion')).toMatch(/^\d+\.\d+\.\d+(?:[-+._A-Za-z0-9]*)?$/);
  expect(url.searchParams.get('hostPlatform')).toBe('wordpress');
  expect(url.searchParams.get('hostPlatformVersion')).toMatch(/^\d+(?:\.\d+)+(?:[-+._A-Za-z0-9]*)?$/);
  expect(['en', 'es']).toContain(url.searchParams.get('locale'));
  expect(url.searchParams.get('requestType')).toBe(expectedRequestType);

  for (const key of forbiddenKeys) {
    expect(url.searchParams.has(key)).toBeFalsy();
  }
}

test.describe.configure({ mode: 'serial' });

test.describe('Phase 7 contextual support acceptance', () => {
  test('administrator sees a local-only support bridge with bounded Kairoseth links', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await login(page, adminUser, adminPass);

    const externalRequests = [];
    page.on('request', (request) => {
      try {
        const url = new URL(request.url());
        if (url.hostname === 'kairoseth.com' || url.hostname.endsWith('.kairoseth.com')) {
          externalRequests.push(request.url());
        }
      } catch {
        // Ignore malformed/non-network browser URLs.
      }
    });

    await page.goto('/wp-admin/tools.php?page=ai-transparency-support');
    await expect(page.getByRole('heading', { level: 1, name: 'AI Transparency Support' })).toBeVisible();
    await expect(page.getByText('Nothing is sent to Kairoseth when this WordPress page loads.')).toBeVisible();
    await expect(page.getByText(/No Registry records, evidence, site URL, administrator identity/)).toBeVisible();
    await expect(page.getByText(/Registry, Discovery, Readiness, Disclosure and Evidence Export continue to work/)).toBeVisible();

    await page.waitForTimeout(250);
    expect(externalRequests).toEqual([]);

    const supportLink = page.getByRole('link', { name: 'Open Kairoseth support' });
    const customLink = page.getByRole('link', { name: 'Open custom request' });
    const supportHref = await supportLink.getAttribute('href');
    const customHref = await customLink.getAttribute('href');

    expect(supportHref).toBeTruthy();
    expect(customHref).toBeTruthy();
    assertBoundedUrl(supportHref, 'implementation_support');
    assertBoundedUrl(customHref, 'third_party_integration');

    await expect(supportLink).toHaveAttribute('target', '_blank');
    await expect(supportLink).toHaveAttribute('rel', /noopener/);
    await expect(supportLink).toHaveAttribute('rel', /noreferrer/);
    await expect(customLink).toHaveAttribute('target', '_blank');

    let horizontalOverflow = await page.evaluate(() =>
      document.documentElement.scrollWidth - document.documentElement.clientWidth
    );
    expect(horizontalOverflow).toBeLessThanOrEqual(1);

    await page.locator('html').evaluate((element) => {
      element.style.fontSize = '200%';
    });
    horizontalOverflow = await page.evaluate(() =>
      document.documentElement.scrollWidth - document.documentElement.clientWidth
    );
    expect(horizontalOverflow).toBeLessThanOrEqual(1);

    const accessibility = await new AxeBuilder({ page })
      .include('.ai-transparency-support-page')
      .analyze();
    const blockingViolations = accessibility.violations.filter((violation) =>
      ['critical', 'serious'].includes(violation.impact)
    );
    expect(blockingViolations, JSON.stringify(blockingViolations, null, 2)).toEqual([]);
  });

  test('editor cannot access the support administration surface', async ({ page }) => {
    await login(page, editorUser, editorPass);
    await page.goto('/wp-admin/tools.php?page=ai-transparency-support');

    await expect(page.locator('body')).not.toContainText('Nothing is sent to Kairoseth');
    await expect(page.locator('body')).toContainText(/not allowed|permission/i);
  });
});
