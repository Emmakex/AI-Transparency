<?php
/**
 * Public AI disclosure shortcode renderer.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Disclosure;

use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

/**
 * Resolves a shortcode selector against current server-side registry state.
 */
final class DisclosureShortcode {
	public const TAG = 'kairoseth_ai_disclosure';

	/**
	 * Site-local registry repository.
	 *
	 * @var WordPressOptionsRegistryRepository
	 */
	private $repository;

	/**
	 * Deterministic disclosure engine.
	 *
	 * @var DisclosureEngine
	 */
	private $engine;

	/**
	 * Create the shortcode adapter.
	 */
	public function __construct() {
		$this->repository = new WordPressOptionsRegistryRepository();
		$this->engine     = new DisclosureEngine();
	}

	/**
	 * Register frontend hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( self::TAG, array( $this, 'render' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue narrowly scoped frontend styles for supported singular content.
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		if ( ! is_singular() ) {
			return;
		}

		$post_id = get_queried_object_id();
		if ( ! $post_id ) {
			return;
		}

		$content = (string) get_post_field( 'post_content', $post_id );
		if ( '' === $content || ! has_shortcode( $content, self::TAG ) ) {
			return;
		}

		wp_enqueue_style(
			'ai-transparency-disclosure',
			plugins_url( 'assets/frontend.css', KAIROSETH_AI_TRANSPARENCY_FILE ),
			array(),
			KAIROSETH_AI_TRANSPARENCY_VERSION
		);
	}

	/**
	 * Render one disclosure from current server-side registry state.
	 *
	 * @param array<string, mixed>|string $attributes Shortcode attributes.
	 * @return string
	 */
	public function render( $attributes ): string {
		$attributes = shortcode_atts(
			array( 'system' => '' ),
			is_array( $attributes ) ? $attributes : array(),
			self::TAG
		);

		$system_id = sanitize_text_field( (string) $attributes['system'] );
		$system_id = trim( $system_id );

		if ( '' === $system_id || 191 < strlen( $system_id ) ) {
			return '';
		}

		$system = $this->repository->load()->find( $system_id );
		if ( null === $system ) {
			return '';
		}

		$disclosure = $this->engine->create_disclosure( $system );
		if ( null === $disclosure ) {
			return '';
		}

		$title = __( 'AI transparency notice', 'ai-transparency' );
		$body  = sprintf(
			/* translators: %s is the administrator-reviewed AI system name. */
			__( 'This interaction uses the AI system “%s”. Review important information or outcomes before relying on them.', 'ai-transparency' ),
			$disclosure->subject_system_name()
		);

		return sprintf(
			'<aside class="ai-transparency-disclosure" aria-label="%1$s"><strong class="ai-transparency-disclosure__title">%2$s</strong><p class="ai-transparency-disclosure__body">%3$s</p></aside>',
			esc_attr( $title ),
			esc_html( $title ),
			esc_html( $body )
		);
	}
}
