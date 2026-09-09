<?php
/**
 * Verify the state produced by the real admin CRUD handlers.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

require __DIR__ . '/assertions.php';

$registry = ( new WordPressOptionsRegistryRepository() )->load();
$systems  = $registry->all();
ai_transparency_runtime_assert( 1 === count( $systems ), 'Runtime CRUD did not preserve exactly one registry record.' );
$system = reset( $systems );

ai_transparency_runtime_assert( 'Runtime Chatbot Updated' === $system->name(), 'Runtime edit did not persist the updated name.' );
ai_transparency_runtime_assert( AiSystem::TYPE_ASSISTANT === $system->type(), 'Runtime edit did not persist the updated system type.' );
ai_transparency_runtime_assert( AiSystem::REVIEW_REVIEWED === $system->review_status(), 'Runtime edit did not persist reviewed state.' );
ai_transparency_runtime_assert( AiSystem::STATUS_ARCHIVED === $system->status(), 'Runtime archive did not persist archived state.' );
ai_transparency_runtime_assert( $system->interaction_disclosure_required(), 'Runtime CRUD lost the configured interaction disclosure state.' );
ai_transparency_runtime_assert( '' !== $system->updated_at(), 'Runtime CRUD did not persist an update timestamp.' );
ai_transparency_runtime_assert( '' !== $system->reviewed_at(), 'Runtime review did not persist a review timestamp.' );

echo "Real WordPress admin add/edit/archive smoke passed.\n";
