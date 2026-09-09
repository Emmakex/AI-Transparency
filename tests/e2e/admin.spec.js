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

test.describe.configure({ mode: 'serial' });

test.describe('Phase 2 WordPress runtime acceptance', () => {
  test('administrator can add, edit, review and archive a registry record', async ({ page }) => {
    await login(page, adminUser, adminPass);
    await page.goto('/wp-admin/tools.php?page=ai-transparency');

    await expect(page.getByRole('heading', { level: 1, name: 'Kairoseth AI Transparency' })).toBeVisible();
    await expect(page.getByRole('heading', { name: 'AI Systems Registry' })).toBeVisible();

    await page.getByLabel('System name').fill('Phase 2 Runtime Assistant');
    await page.getByLabel('System type').selectOption('assistant');
    await page.getByLabel('Interaction context').fill('Customer support assistant on the public help flow.');
    await page.getByLabel('Review status').selectOption('reviewed');
    await page.getByRole('checkbox').check();
    await page.getByRole('button', { name: 'Add AI system' }).click();

    await expect(page.locator('.notice-success')).toContainText('AI system saved.');

    let row = page.locator('tbody tr').filter({ hasText: 'Phase 2 Runtime Assistant' });
    await expect(row).toContainText('Assistant');
    await expect(row).toContainText('Reviewed');
    await expect(row).toContainText('Active');

    await row.getByRole('link', { name: 'Edit' }).click();
    await expect(page.getByRole('heading', { name: 'Edit AI system' })).toBeVisible();
    await page.getByLabel('System name').fill('Phase 2 Runtime Assistant Updated');
    await page.getByLabel('Review status').selectOption('pending');
    await page.getByRole('button', { name: 'Update AI system' }).click();

    row = page.locator('tbody tr').filter({ hasText: 'Phase 2 Runtime Assistant Updated' });
    await expect(row).toContainText('Pending review');
    await expect(row).toContainText('Active');

    await row.getByRole('button', { name: 'Archive' }).click();
    await expect(page.locator('.notice-success')).toContainText('AI system archived.');

    row = page.locator('tbody tr').filter({ hasText: 'Phase 2 Runtime Assistant Updated' });
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

  test('non-administrator cannot access the registry administration surface', async ({ page }) => {
    await login(page, editorUser, editorPass);
    await page.goto('/wp-admin/tools.php?page=ai-transparency');

    await expect(page.locator('body')).not.toContainText('AI Systems Registry');
    await expect(page.locator('body')).toContainText(/not allowed|permission/i);
  });
});
