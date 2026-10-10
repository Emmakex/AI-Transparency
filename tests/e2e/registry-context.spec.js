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

function rowForSystem(page, systemName) {
  return page.getByRole('cell', { name: systemName, exact: true }).locator('..');
}

test.describe('Registry administrator context', () => {
  test('administrator context is explicit, editable, persisted and reflected in readiness without discovery inference', async ({ page }) => {
    const runId = `${Date.now()}-${Math.random().toString(16).slice(2, 8)}`;
    const systemName = `Registry Context ${runId}`;

    await login(page);
    await page.goto('/wp-admin/tools.php?page=ai-transparency');

    await expect(page.getByRole('heading', { name: 'Administrator context' })).toBeVisible();
    await expect(page.getByText(
      'These fields are administrator declarations. Discovery does not infer them from plugin identity or version.',
      { exact: true }
    )).toBeVisible();

    await page.getByLabel('System name').fill(systemName);
    await page.getByLabel('System type').selectOption('assistant');
    await page.getByLabel('Purpose').fill('Answer product-support questions for customers.');
    await page.getByLabel('Provider').fill('Example Provider');
    await page.getByLabel('Model or model family').fill('Example Model Family');
    await page.getByLabel('Audience').fill('Customers using the public support flow.');
    await page.getByLabel('Human oversight').fill('Support staff review escalations before action.');
    await page.getByLabel('Data use context').fill('Customer questions and approved knowledge-base content.');
    await page.getByLabel('Interaction context').fill('Public support widget on product pages.');
    await page.getByLabel('Review status').selectOption('reviewed');
    await page.getByRole('button', { name: 'Add AI system' }).click();

    await expect(page.locator('.notice-success')).toContainText('AI system saved.');

    let row = rowForSystem(page, systemName);
    await expect(row).toContainText('Reviewed');
    await row.getByRole('link', { name: 'Edit' }).click();

    await expect(page.getByLabel('Purpose')).toHaveValue('Answer product-support questions for customers.');
    await expect(page.getByLabel('Provider')).toHaveValue('Example Provider');
    await expect(page.getByLabel('Model or model family')).toHaveValue('Example Model Family');
    await expect(page.getByLabel('Audience')).toHaveValue('Customers using the public support flow.');
    await expect(page.getByLabel('Human oversight')).toHaveValue('Support staff review escalations before action.');
    await expect(page.getByLabel('Data use context')).toHaveValue('Customer questions and approved knowledge-base content.');

    await page.getByLabel('Purpose').fill('Answer and triage product-support questions for customers.');
    await page.getByRole('button', { name: 'Update AI system' }).click();
    await expect(page.locator('.notice-success')).toContainText('AI system saved.');

    row = rowForSystem(page, systemName);
    await row.getByRole('link', { name: 'Edit' }).click();

    await expect(page.getByLabel('Purpose')).toHaveValue('Answer and triage product-support questions for customers.');
    await expect(page.getByLabel('Provider')).toHaveValue('Example Provider');
    await expect(page.getByLabel('Model or model family')).toHaveValue('Example Model Family');
    await expect(page.getByLabel('Audience')).toHaveValue('Customers using the public support flow.');
    await expect(page.getByLabel('Human oversight')).toHaveValue('Support staff review escalations before action.');
    await expect(page.getByLabel('Data use context')).toHaveValue('Customer questions and approved knowledge-base content.');

    await page.getByLabel('Human oversight').fill('');
    await page.getByRole('button', { name: 'Update AI system' }).click();
    await expect(page.locator('.notice-success')).toContainText('AI system saved.');

    await page.goto('/wp-admin/tools.php?page=ai-transparency-readiness');
    const contextFinding = page.locator('.ai-transparency-finding').filter({
      has: page.getByRole('heading', { level: 2, name: systemName, exact: true }),
    });

    await expect(contextFinding).toHaveCount(1);
    await expect(contextFinding).toContainText(
      'One or more administrator context fields are not documented for this active AI system.'
    );
    await expect(contextFinding).toContainText(
      'Review the missing administrator context fields and document those that are applicable to this system.'
    );
    await expect(contextFinding.locator('.ai-transparency-signature')).toHaveText(/^[a-f0-9]{64}$/);
  });
});
