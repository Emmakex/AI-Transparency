<?php
/**
 * Validate release metadata consistency before creating a distributable ZIP.
 *
 * @package KairosethAITransparency
 */

$root = dirname( __DIR__ );

$plugin_path    = $root . '/ai-transparency.php';
$readme_path    = $root . '/readme.txt';
$changelog_path = $root . '/CHANGELOG.md';
$uninstall_path = $root . '/uninstall.php';

$plugin    = file_get_contents( $plugin_path );
$readme    = file_get_contents( $readme_path );
$changelog = file_get_contents( $changelog_path );

if ( false === $plugin || false === $readme || false === $changelog ) {
	fwrite( STDERR, "Release validation could not read required metadata files.\n" );
	exit( 1 );
}

$failures = array();

$extract = static function ( string $pattern, string $content, string $label ) use ( &$failures ): string {
	if ( 1 !== preg_match( $pattern, $content, $matches ) ) {
		$failures[] = sprintf( 'Could not resolve %s.', $label );
		return '';
	}

	return trim( $matches[1] );
};

$header_version = $extract(
	'/^\s*\*\s*Version:\s*([^\r\n]+)$/m',
	$plugin,
	'plugin header version'
);
$constant_version = $extract(
	"/define\(\s*'KAIROSETH_AI_TRANSPARENCY_VERSION'\s*,\s*'([^']+)'\s*\)/",
	$plugin,
	'KAIROSETH_AI_TRANSPARENCY_VERSION'
);
$stable_tag = $extract(
	'/^Stable tag:\s*([^\r\n]+)$/mi',
	$readme,
	'readme Stable tag'
);
$text_domain = $extract(
	'/^\s*\*\s*Text Domain:\s*([^\r\n]+)$/m',
	$plugin,
	'plugin text domain'
);
$slug = $extract(
	"/define\(\s*'KAIROSETH_AI_TRANSPARENCY_SLUG'\s*,\s*'([^']+)'\s*\)/",
	$plugin,
	'plugin slug constant'
);

$versions = array_filter(
	array(
		'plugin header'      => $header_version,
		'runtime constant'   => $constant_version,
		'readme stable tag'  => $stable_tag,
	),
	static function ( string $version ): bool {
		return '' !== $version;
	}
);

foreach ( $versions as $label => $version ) {
	if ( 1 !== preg_match( '/^\d+\.\d+\.\d+$/', $version ) ) {
		$failures[] = sprintf( '%s must be a stable semantic version; found %s.', $label, $version );
	}
}

if ( count( array_unique( $versions ) ) > 1 ) {
	$failures[] = 'Release versions are inconsistent across plugin header, runtime constant and readme Stable tag.';
}

$expected_version = getenv( 'AI_TRANSPARENCY_EXPECTED_VERSION' );
if ( false !== $expected_version && '' !== $expected_version && $header_version !== $expected_version ) {
	$failures[] = sprintf( 'Release version must be %s for this build; found %s.', $expected_version, $header_version );
}

if ( 'ai-transparency' !== $text_domain ) {
	$failures[] = sprintf( 'Text Domain must remain ai-transparency; found %s.', $text_domain );
}

if ( 'ai-transparency' !== $slug ) {
	$failures[] = sprintf( 'Plugin slug constant must remain ai-transparency; found %s.', $slug );
}

if ( '' !== $header_version && false === strpos( $changelog, '## [' . $header_version . ']' ) ) {
	$failures[] = sprintf( 'CHANGELOG.md is missing the %s release heading.', $header_version );
}

if ( ! is_file( $uninstall_path ) || 0 === filesize( $uninstall_path ) ) {
	$failures[] = 'uninstall.php is missing or empty.';
}

$readme_blocks     = preg_split( '/\R\R+/', trim( $readme ) );
$short_description = isset( $readme_blocks[1] ) ? trim( $readme_blocks[1] ) : '';
$short_length      = strlen( $short_description );

if ( '' === $short_description ) {
	$failures[] = 'WordPress.org short description is missing.';
} elseif ( $short_length > 150 ) {
	$failures[] = sprintf( 'WordPress.org short description is %d characters; maximum accepted by this release gate is 150.', $short_length );
}

$tags_line = $extract(
	'/^Tags:\s*([^\r\n]+)$/mi',
	$readme,
	'readme Tags'
);

if ( '' !== $tags_line ) {
	$tags = array_values(
		array_filter(
			array_map( 'trim', explode( ',', $tags_line ) )
		)
	);

	if ( count( $tags ) < 1 || count( $tags ) > 5 ) {
		$failures[] = sprintf( 'WordPress.org Tags must contain 1–5 entries; found %d.', count( $tags ) );
	}
}

$forbidden_readme_phrases = array(
	'current pre-release development build',
	'No stable WordPress.org release is claimed yet',
);

foreach ( $forbidden_readme_phrases as $phrase ) {
	if ( false !== stripos( $readme, $phrase ) ) {
		$failures[] = sprintf( 'Stable readme still contains pre-release wording: %s.', $phrase );
	}
}

if ( $failures ) {
	foreach ( $failures as $failure ) {
		fwrite( STDERR, $failure . "\n" );
	}
	exit( 1 );
}

if ( in_array( '--print-version', $argv, true ) ) {
	fwrite( STDOUT, $header_version . "\n" );
	exit( 0 );
}

printf(
	"Release metadata gate passed for %s: slug=%s, short_description=%d chars.\n",
	$header_version,
	$slug,
	$short_length
);
