<?php
/**
 * Verify migration of a legacy registry payload in real WordPress storage.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;
use Kairoseth\AITransparency\Registry\RegistrySchema;

require __DIR__ . '/assertions.php';

$legacy = array(
	array(
		'id'                              => 'legacy-runtime',
		'name'                            => 'Legacy Runtime AI',
		'type'                            => 'chatbot',
		'source'                          => 'manual',
		'interaction_disclosure_required' => true,
	),
);

update_option( WordPressOptionsRegistryRepository::OPTION_NAME, $legacy, false );

$repository = new WordPressOptionsRegistryRepository();
$registry   = $repository->load();
$stored     = get_option( WordPressOptionsRegistryRepository::OPTION_NAME );
$system     = $registry->find( 'legacy-runtime' );

ai_transparency_runtime_assert( is_array( $stored ), 'Migrated runtime registry is not stored as an array.' );
ai_transparency_runtime_assert( RegistrySchema::VERSION === ( $stored['schema_version'] ?? null ), 'Real WordPress migration did not persist the current schema version.' );
ai_transparency_runtime_assert( null !== $system, 'Real WordPress migration lost the legacy AI system.' );
ai_transparency_runtime_assert( AiSystem::STATUS_ACTIVE === $system->status(), 'Legacy runtime migration did not apply active lifecycle default.' );
ai_transparency_runtime_assert( AiSystem::REVIEW_PENDING === $system->review_status(), 'Legacy runtime migration did not apply pending review default.' );
ai_transparency_runtime_assert( $system->interaction_disclosure_required(), 'Legacy runtime migration lost disclosure state.' );

echo "Real WordPress legacy migration acceptance passed.\n";
