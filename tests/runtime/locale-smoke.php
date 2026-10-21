<?php
/**
 * Runtime locale acceptance for the bundled Spanish fallback.
 *
 * Executed through WP-CLI inside wp-env against the production package after
 * the site language has been switched to es_ES.
 *
 * @package KairosethAITransparency
 */

$failures = array();
$locale   = determine_locale();

if ( 'es_ES' !== $locale ) {
	$failures[] = sprintf( 'Expected es_ES runtime locale, found %s.', $locale );
}

$expectations = array(
	'Active'                                                            => 'Activo',
	'Pending review'                                                    => 'Pendiente de revisión',
	'Guidance'                                                          => 'Orientación',
	'AI Systems Registry'                                               => 'Registro de sistemas de IA',
	'AI Discovery'                                                      => 'Descubrimiento de IA',
	'AI Readiness'                                                      => 'Preparación de IA',
	'Maintain a local inventory of AI systems used by this WordPress site.' => 'Mantén un inventario local de los sistemas de IA utilizados por este sitio WordPress.',
);

foreach ( $expectations as $source => $expected ) {
	$actual = __( $source, 'kairoseth-ai-transparency' );
	if ( $expected !== $actual ) {
		$failures[] = sprintf( 'Translation mismatch for "%s": expected "%s", found "%s".', $source, $expected, $actual );
	}
}

if ( $failures ) {
	foreach ( $failures as $failure ) {
		fwrite( STDERR, $failure . "\n" );
	}
	exit( 1 );
}

printf( "Locale smoke passed for %s with bundled Kairoseth AI Transparency Spanish translations.\n", $locale );
