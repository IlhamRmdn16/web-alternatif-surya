<?php

namespace App\Support;

/**
 * Pembersih HTML hasil editor (TinyMCE) sebelum disimpan: hanya tag & atribut aman yang dipertahankan.
 */
class Html
{
    private const TAGS = ['p', 'br', 'h2', 'h3', 'h4', 'h5', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'sub', 'sup',
        'ul', 'ol', 'li', 'a', 'img', 'blockquote', 'hr', 'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption',
        'colgroup', 'col', 'span', 'div', 'figure', 'figcaption', 'pre', 'code'];

    // Dibuang beserta isinya
    private const DROP = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select',
        'link', 'meta', 'svg', 'math', 'noscript', 'video', 'audio', 'source', 'base', 'head', 'title'];

    private const ATTRS = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan', 'scope'],
        'ol' => ['start', 'type'],
        'table' => ['border', 'cellpadding', 'cellspacing', 'width'],
        'col' => ['width'],
    ];

    private const STYLE_PROPS = ['text-align', 'color', 'background-color', 'font-size', 'font-weight', 'font-style', 'font-family',
        'text-decoration', 'float', 'width', 'height', 'max-width', 'margin', 'margin-left', 'margin-right', 'margin-top',
        'margin-bottom', 'padding', 'border', 'border-collapse', 'border-width', 'border-style', 'border-color',
        'vertical-align', 'line-height', 'list-style-type', 'display'];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') return '';

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $prev = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $root = $dom->getElementsByTagName('div')->item(0);
        if (! $root) return '';

        self::walk($root);

        $out = '';
        foreach ($root->childNodes as $child) $out .= $dom->saveHTML($child);
        return trim($out);
    }

    private static function walk(\DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof \DOMComment) { $node->removeChild($child); continue; }
            if (! $child instanceof \DOMElement) continue;

            $tag = strtolower($child->tagName);
            if (in_array($tag, self::DROP, true)) { $node->removeChild($child); continue; }

            self::walk($child);

            if (! in_array($tag, self::TAGS, true)) {
                while ($child->firstChild) $node->insertBefore($child->firstChild, $child);
                $node->removeChild($child);
                continue;
            }
            self::cleanAttributes($child, $tag);
        }
    }

    private static function cleanAttributes(\DOMElement $el, string $tag): void
    {
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);
            if ($name !== 'style' && ! in_array($name, self::ATTRS[$tag] ?? [], true)) {
                $el->removeAttribute($attr->name);
                continue;
            }
            $val = trim($attr->value);
            if ($name === 'style') {
                $clean = self::cleanStyle($val);
                $clean === '' ? $el->removeAttribute('style') : $el->setAttribute('style', $clean);
            } elseif ($name === 'href' && ! preg_match('~^(https?://|mailto:|tel:|/|#)~i', $val)) {
                $el->removeAttribute('href');
            } elseif ($name === 'src' && ! preg_match('~^(https?://|/)~i', $val)) {
                $el->removeAttribute('src');
            } elseif ($name === 'target' && $val !== '_blank') {
                $el->removeAttribute('target');
            }
        }

        if ($tag === 'a' && $el->getAttribute('target') === '_blank') $el->setAttribute('rel', 'noopener noreferrer');
        if ($tag === 'img') {
            if (! $el->hasAttribute('src')) { $el->parentNode?->removeChild($el); return; }
            $el->setAttribute('loading', 'lazy');
        }
    }

    private static function cleanStyle(string $style): string
    {
        $out = [];
        foreach (explode(';', $style) as $decl) {
            if (! str_contains($decl, ':')) continue;
            [$prop, $value] = array_map('trim', explode(':', $decl, 2));
            $prop = strtolower($prop);
            if (! in_array($prop, self::STYLE_PROPS, true)) continue;
            if (preg_match('~(url\s*\(|expression|javascript:|@import|[<>\\\\])~i', $value)) continue;
            $out[] = $prop.': '.$value;
        }
        return implode('; ', $out);
    }
}
