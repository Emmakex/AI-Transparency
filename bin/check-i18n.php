<?php
/**
 * Verify that runtime gettext strings use the canonical domain and have Spanish translations.
 *
 * @package KairosethAITransparency
 */

declare(strict_types=1);

const KAIROSETH_I18N_DOMAIN = 'kairoseth-ai-transparency';

$root = dirname(__DIR__);
$source_files = array($root . '/ai-transparency.php');

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/src'));
foreach ($iterator as $file) {
    if ($file->isFile() && 'php' === strtolower($file->getExtension())) {
        $source_files[] = $file->getPathname();
    }
}

$messages = array();
$errors = array();

$patterns = array(
    '/\b(?:__|_e|esc_html__|esc_html_e|esc_attr__|esc_attr_e)\s*\(\s*([\'\"])(.*?)\1\s*,\s*([\'\"])(.*?)\3\s*\)/s',
    '/\b(?:_x|_ex|esc_html_x|esc_attr_x)\s*\(\s*([\'\"])(.*?)\1\s*,\s*([\'\"])(.*?)\3\s*,\s*([\'\"])(.*?)\5\s*\)/s',
);

foreach ($source_files as $source_file) {
    $content = file_get_contents($source_file);
    if (false === $content) {
        $errors[] = 'Unable to read source file: ' . $source_file;
        continue;
    }

    if (preg_match_all($patterns[0], $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $msgid = stripcslashes($match[2]);
            $domain = stripcslashes($match[4]);
            $messages[$msgid] = true;
            if (KAIROSETH_I18N_DOMAIN !== $domain) {
                $errors[] = sprintf('Wrong text domain in %s for "%s": %s', $source_file, $msgid, $domain);
            }
        }
    }

    if (preg_match_all($patterns[1], $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $msgid = stripcslashes($match[2]);
            $domain = stripcslashes($match[6]);
            $messages[$msgid] = true;
            if (KAIROSETH_I18N_DOMAIN !== $domain) {
                $errors[] = sprintf('Wrong text domain in %s for "%s": %s', $source_file, $msgid, $domain);
            }
        }
    }
}

$catalog = parse_po($root . '/languages/kairoseth-ai-transparency-es_ES.po');
foreach (array_keys($messages) as $msgid) {
    if (!array_key_exists($msgid, $catalog)) {
        $errors[] = 'Missing Spanish translation: ' . $msgid;
        continue;
    }
    if ('' === trim($catalog[$msgid])) {
        $errors[] = 'Empty Spanish translation: ' . $msgid;
    }
}

if ($errors) {
    fwrite(STDERR, "EN/ES bilingual coverage gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, '- ' . $error . "\n");
    }
    exit(1);
}

printf("EN/ES bilingual coverage gate passed: %d runtime strings have complete Spanish translations.\n", count($messages));

/**
 * Parse a simple gettext PO catalog into msgid => msgstr.
 *
 * @param string $path PO file path.
 * @return array<string,string>
 */
function parse_po(string $path): array
{
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if (false === $lines) {
        throw new RuntimeException('Unable to read PO catalog: ' . $path);
    }

    $entries = array();
    $msgid = null;
    $msgstr = null;
    $state = null;

    $commit = static function () use (&$entries, &$msgid, &$msgstr): void {
        if (null !== $msgid && '' !== $msgid) {
            $entries[$msgid] = (string) $msgstr;
        }
        $msgid = null;
        $msgstr = null;
    };

    foreach ($lines as $line) {
        if (0 === strpos($line, 'msgid ')) {
            $commit();
            $msgid = po_unquote(substr($line, 6));
            $state = 'msgid';
            continue;
        }
        if (0 === strpos($line, 'msgstr ')) {
            $msgstr = po_unquote(substr($line, 7));
            $state = 'msgstr';
            continue;
        }
        if ('' !== $line && '"' === $line[0]) {
            if ('msgid' === $state) {
                $msgid .= po_unquote($line);
            } elseif ('msgstr' === $state) {
                $msgstr .= po_unquote($line);
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
 * Decode one quoted PO string.
 *
 * @param string $value Quoted PO value.
 * @return string
 */
function po_unquote(string $value): string
{
    $value = trim($value);
    if (strlen($value) < 2) {
        return '';
    }
    return stripcslashes(substr($value, 1, -1));
}
