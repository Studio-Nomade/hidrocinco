<?php

declare(strict_types=1);

namespace App;

use DOMDocument;
use DOMElement;
use DOMNode;

final class HtmlSanitizer
{
    private const ALLOWED_TAGS = ['p', 'br', 'h2', 'h3', 'strong', 'em', 'u', 'ul', 'ol', 'li', 'a', 'blockquote'];

    public static function clean(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8"><div id="root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        $root = $document->getElementById('root');
        if (!$root) {
            return '';
        }

        self::sanitizeChildren($root);
        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }
        return trim($output);
    }

    private static function sanitizeChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }
            $tag = strtolower($node->tagName);
            if (!in_array($tag, self::ALLOWED_TAGS, true)) {
                if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed'], true)) {
                    $parent->removeChild($node);
                    continue;
                }
                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }
                $parent->removeChild($node);
                continue;
            }

            foreach (iterator_to_array($node->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                if ($tag !== 'a' || !in_array($name, ['href', 'title'], true)) {
                    $node->removeAttribute($attribute->name);
                }
            }
            if ($tag === 'a' && $node->hasAttribute('href')) {
                $href = trim($node->getAttribute('href'));
                if (!preg_match('#^(https?://|mailto:|tel:|/|\#)#i', $href)) {
                    $node->removeAttribute('href');
                } else {
                    $node->setAttribute('rel', 'noopener');
                }
            }
            self::sanitizeChildren($node);
        }
    }
}
