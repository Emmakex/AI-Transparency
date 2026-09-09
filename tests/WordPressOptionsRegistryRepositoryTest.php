<?php
/**
 * WordPress Options registry repository contract tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;
use PHPUnit\Framework\TestCase;

$GLOBALS['kat_test_blog_id'] = 1;
$GLOBALS['kat_test_options'] = array();

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $name, $default = false ) {
		$blog_id = $GLOBALS['kat_test_blog_id'];
		return $GLOBALS['kat_test_options'][ $blog_id ][ $name ] ?? $default;
	}
}

if ( ! function_exists( 'update_option' ) ) {
	function update_option( $name, $value, $autoload = null ) {
		$blog_id = $GLOBALS['kat_test_blog_id'];
		$current = $GLOBALS['kat_test_options'][ $blog_id ][ $name ] ?? null;
		$GLOBALS['kat_test_options'][ $blog_id ][ $name ] = $value;
		return $current !== $value;
	}
}

final class WordPressOptionsRegistryRepositoryTest extends TestCase {
	protected function setUp(): void {
		$GLOBALS['kat_test_blog_id'] = 1;
		$GLOBALS['kat_test_options'] = array();
	}

	public function test_repository_persists_and_loads_registry(): void {
		$repository = new WordPressOptionsRegistryRepository();
		$registry   = $repository->load();
		$registry->put( new AiSystem( 'assistant', 'Assistant', 'assistant', 'manual' ) );

		$this->assertTrue( $repository->save( $registry ) );
		$this->assertSame( 'Assistant', $repository->load()->find( 'assistant' )->name() );
	}

	public function test_repository_keeps_wordpress_blog_contexts_isolated(): void {
		$repository = new WordPressOptionsRegistryRepository();
		$registry   = $repository->load();
		$registry->put( new AiSystem( 'site-one', 'Site One Assistant', 'assistant', 'manual' ) );
		$repository->save( $registry );

		$GLOBALS['kat_test_blog_id'] = 2;
		$site_two_registry           = $repository->load();

		$this->assertSame( 0, $site_two_registry->count() );
		$site_two_registry->put( new AiSystem( 'site-two', 'Site Two Chatbot', 'chatbot', 'manual' ) );
		$repository->save( $site_two_registry );

		$GLOBALS['kat_test_blog_id'] = 1;
		$this->assertNotNull( $repository->load()->find( 'site-one' ) );
		$this->assertNull( $repository->load()->find( 'site-two' ) );
	}

	public function test_repository_migrates_legacy_payload_on_load(): void {
		$GLOBALS['kat_test_options'][1][ WordPressOptionsRegistryRepository::OPTION_NAME ] = array(
			array(
				'id'     => 'legacy',
				'name'   => 'Legacy',
				'type'   => 'other',
				'source' => 'manual',
			),
		);

		$repository = new WordPressOptionsRegistryRepository();
		$registry   = $repository->load();
		$stored     = $GLOBALS['kat_test_options'][1][ WordPressOptionsRegistryRepository::OPTION_NAME ];

		$this->assertNotNull( $registry->find( 'legacy' ) );
		$this->assertSame( 1, $stored['schema_version'] );
		$this->assertSame( 'active', $stored['systems'][0]['status'] );
	}
}
