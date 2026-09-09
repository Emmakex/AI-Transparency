<?php
/**
 * Compile a simple gettext PO catalog into a binary MO file.
 *
 * Usage: php bin/compile-po.php source.po target.mo
 */

declare(strict_types=1);

if (3 !== $argc) {
    fwrite(STDERR, "Usage: php bin/compile-po.php source.po target.mo\n");
    exit(2);
}

$source = $argv[1];
$target = $argv[2];
$catalog = parse_po_catalog($source);
ksort($catalog, SORT_STRING);

$originals = array_keys($catalog);
$translations = array_values($catalog);
$count = count($catalog);

$header_size = 28;
$original_table_offset = $header_size;
$translation_table_offset = $original_table_offset + ($count * 8);
$string_data_offset = $translation_table_offset + ($count * 8);

$original_descriptors = '';
$translation_descriptors = '';
$original_data = '';
$translation_data = '';

$offset = $string_data_offset;
foreach ($originals as $string) {
    $length = strlen($string);
    $original_descriptors .= pack('V2', $length, $offset);
    $original_data .= $string . "\0";
    $offset += $length + 1;
}

foreach ($translations as $string) {
    $length = strlen($string);
    $translation_descriptors .= pack('V2', $length, $offset);
    $translation_data .= $string . "\0";
    $offset += $length + 1;
}

$header = pack(
    'V7',
    0x950412de,
    0,
    $count,
    $original_table_offset,
    $translation_table_offset,
    0,
    $offset
);

$binary = $header . $original_descriptors . $translation_descriptors . $original_data . $translation_data;
$directory = dirname($target);
if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
    fwrite(STDERR, 'Unable to create target directory: ' . $directory . "\n");
    exit(1);
}

if (false === file_put_contents($target, $binary)) {
    fwrite(STDERR, 'Unable to write MO file: ' . $target . "\n");
    exit(1);
}

printf("Compiled %d translation entries to %s\n", $count, $target);

/**
 * Parse a gettext PO catalog, including multiline msgid/msgstr values.
 *
 * @param string $path PO file path.
 * @return array<string,string>
 */
function parse_po_catalog(string $path): array
{
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if (false === $lines) {
        throw new RuntimeException('Unable to read PO file: ' . $path);
    }

    $entries = array();
    $msgid = null;
    $msgstr = null;
    $state = null;

    $commit = static function () use (&$entries, &$msgid, &$msgstr): void {
        if (null !== $msgid && null !== $msgstr) {
            $entries[$msgid] = $msgstr;
        }
        $msgid = null;
        $msgstr = null;
    };

    foreach ($lines as $line) {
        if (0 === strpos($line, 'msgid ')) {
            $commit();
            $msgid = po_decode(substr($line, 6));
            $state = 'msgid';
            continue;
        }
        if (0 === strpos($line, 'msgstr ')) {
            $msgstr = po_decode(substr($line, 7));
            $state = 'msgstr';
            continue;
        }
        if ('' !== $line && '"' === $line[0]) {
            if ('msgid' === $state) {
                $msgid .= po_decode($line);
            } elseif ('msgstr' === $state) {
                $msgstr .= po_decode($line);
            }
            continue;
        }
        if ('' === trim($line)) {
            $commit();
            $state = null;
        }
    }

    $commit();
    return $entries;
}

/**
 * Decode a quoted PO string.
 *
 * @param string $value Quoted value.
 * @return string
 */
function po_decode(string $value): string
{
    $value = trim($value);
    if (strlen($value) < 2) {
        return '';
    }
    return stripcslashes(substr($value, 1, -1));
}
