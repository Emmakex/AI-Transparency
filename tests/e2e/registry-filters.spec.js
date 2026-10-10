const { test, expect } = require('@playwright/test');

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

test('Registry filters search the rendered local evidence without a server mutation', async ({ page }) => {
  await login(page);
  await page.goto('/wp-admin/admin.php?page=ai-transparency');

  const fixtureName = `Registry filter fixture ${Date.now()}`;
  await page.locator('#kat-system-name').fill(fixtureName);
  await page.locator('#kat-interaction-context').fill('Visible only for local Registry filter acceptance.');

  await Promise.all([
    page.waitForURL(/admin\.php\?page=ai-transparency&kat_notice=saved/),
    page.getByRole('button', { name: 'Add AI system' }).click(),
  ]);

  const filters = page.locator('.kat-registry-filters');
  await expect(filters).toBeVisible();
  await expect(filters.locator('input[type="search"]')).toHaveCount(1);
  await expect(filters.locator('select')).toHaveCount(3);

  const search = filters.locator('input[type="search"]');
  await search.fill(fixtureName);

  const visibleRows = page.locator('.ai-transparency-table-wrap tbody tr:visible');
  await expect(visibleRows).toHaveCount(1);
  await expect(visibleRows.first()).toContainText(fixtureName);

  const selects = filters.locator('select');
  await selects.nth(0).selectOption({ label: 'Chatbot' });
  await selects.nth(1).selectOption({ label: 'Pending review' });
  await selects.nth(2).selectOption({ label: 'Active' });
  await expect(visibleRows).toHaveCount(1);
  await expect(visibleRows.first()).toContainText(fixtureName);

  await search.press('Escape');
  await expect(search).toHaveValue('');
});
