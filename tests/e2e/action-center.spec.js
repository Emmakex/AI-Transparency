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

test('Action Center routes a pending system directly to its Registry review', async ({ page }) => {
  const runId = `${Date.now()}-${Math.random().toString(16).slice(2, 8)}`;
  const systemName = `Action Center Pending ${runId}`;

  await login(page);
  await page.goto('/wp-admin/admin.php?page=ai-transparency');

  await page.getByLabel('System name').fill(systemName);
  await page.getByLabel('System type').selectOption('assistant');
  await page.getByRole('button', { name: 'Add AI system' }).click();
  await expect(page.locator('.notice-success')).toContainText('AI system saved.');

  await page.goto('/wp-admin/admin.php?page=ai-transparency-actions');

  await expect(page.getByRole('heading', { level: 1, name: 'Actions' })).toBeVisible();

  const item = page.locator('.kat-action-center-item').filter({
    hasText: systemName,
  });

  await expect(item).toHaveCount(1);
  await expect(item).toHaveAttribute('data-action-code', 'review_record');
  await expect(item.getByText(systemName, { exact: true })).toBeVisible();
  await expect(item.getByRole('heading', { level: 2, name: 'Pending review' })).toBeVisible();

  const editLink = item.getByRole('link', { name: 'Edit' });
  await expect(editLink).toHaveAttribute('href', /admin\.php\?page=ai-transparency&system=[^&]+/);

  const pluginMenu = page.locator('#toplevel_page_kairoseth-ai-transparency');
  await expect(pluginMenu.locator('.wp-submenu a[href="admin.php?page=ai-transparency-actions"]')).toHaveCount(1);

  const accessibility = await new AxeBuilder({ page })
    .include('.kat-action-center')
    .analyze();
  const blockingViolations = accessibility.violations.filter((violation) =>
    ['critical', 'serious'].includes(violation.impact)
  );
  expect(blockingViolations, JSON.stringify(blockingViolations, null, 2)).toEqual([]);

  await page.setViewportSize({ width: 390, height: 844 });
  await page.reload();
  await expect(item).toBeVisible();
  const horizontalOverflow = await page.evaluate(() =>
    document.documentElement.scrollWidth - document.documentElement.clientWidth
  );
  expect(horizontalOverflow).toBeLessThanOrEqual(1);
});
