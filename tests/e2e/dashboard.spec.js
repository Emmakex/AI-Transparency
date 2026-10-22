const { test, expect } = require('@playwright/test');
const AxeBuilder = require('@axe-core/playwright').default;

const adminUser = process.env.WP_TEST_ADMIN_USER;
const adminPass = process.env.WP_TEST_ADMIN_PASS;

async function login(page) {
  if (!adminUser || !adminPass) {
    throw new Error('WordPress runtime test credentials were not provided.');
  }

  await page.goto('/wp-login.php');
  await page.locator('#user_login').fill(adminUser);
  await page.locator('#user_pass').fill(adminPass);
  await Promise.all([
    page.waitForURL(/\/wp-admin\//),
    page.locator('#wp-submit').click(),
  ]);
}

test('v1.3 dashboard acts as an accessible operational center for the plugin workflow', async ({ page }) => {
  await login(page);
  await page.goto('/wp-admin/admin.php?page=kairoseth-ai-transparency');

  await expect(page.getByRole('heading', { level: 1, name: 'Kairoseth AI Transparency' })).toBeVisible();
  await expect(page.locator('.kat-dashboard-version')).toContainText(/^v\d+\.\d+\.\d+$/);
  await expect(page.locator('.kat-dashboard-metric')).toHaveCount(4);
  await expect(page.locator('.kat-dashboard-priority')).toHaveCount(1);
  await expect(page.locator('.kat-dashboard-action')).toHaveCount(3);
  await expect(page.locator('.kat-dashboard-workflow-link')).toHaveCount(6);

  const heroPrimary = page.locator('.kat-dashboard-hero-actions .button-primary');
  await expect(heroPrimary).toBeVisible();
  await expect(heroPrimary).toHaveCSS('background-color', 'rgb(255, 255, 255)');
  await expect(heroPrimary).toHaveCSS('color', 'rgb(13, 68, 140)');
  await expect(page.locator('.kat-dashboard-priority-label')).toBeHidden();

  await expect(page.getByRole('link', { name: 'Open AI Systems Registry' }).first()).toBeVisible();
  await expect(page.getByRole('link', { name: 'AI Discovery' }).first()).toBeVisible();

  const pluginMenu = page.locator('#toplevel_page_kairoseth-ai-transparency');
  await expect(pluginMenu).toBeVisible();

  const submenuHrefs = await pluginMenu.locator('.wp-submenu a').evaluateAll((links) =>
    links.map((link) => link.getAttribute('href'))
  );

  expect(submenuHrefs).toEqual(
    expect.arrayContaining([
      'admin.php?page=kairoseth-ai-transparency',
      'admin.php?page=ai-transparency',
      'admin.php?page=ai-transparency-discovery',
      'admin.php?page=ai-transparency-readiness',
      'admin.php?page=ai-transparency-disclosure',
      'admin.php?page=ai-transparency-evidence-export',
      'admin.php?page=ai-transparency-support',
    ])
  );

  const duplicateToolsLinks = page.locator('#menu-tools .wp-submenu a[href*="ai-transparency"]');
  await expect(duplicateToolsLinks).toHaveCount(0);

  const accessibility = await new AxeBuilder({ page })
    .include('.kat-dashboard')
    .analyze();
  const blockingViolations = accessibility.violations.filter((violation) =>
    ['critical', 'serious'].includes(violation.impact)
  );
  expect(blockingViolations, JSON.stringify(blockingViolations, null, 2)).toEqual([]);

  await page.setViewportSize({ width: 390, height: 844 });
  await page.reload();
  await expect(page.locator('.kat-dashboard-priority > .button')).toBeVisible();
  const horizontalOverflow = await page.evaluate(() =>
    document.documentElement.scrollWidth - document.documentElement.clientWidth
  );
  expect(horizontalOverflow).toBeLessThanOrEqual(1);

  await page.goto('/wp-admin/admin.php?page=ai-transparency');
  await expect(page.getByRole('heading', { level: 1, name: 'Kairoseth AI Transparency' })).toBeVisible();
  await expect(page.locator('link[href*="assets/admin-v11.css"]')).toHaveCount(1);
});
