<?php

class Security {
    public static function text($value, $maxLength = 255) {
        $value = trim((string) $value);
        return function_exists('mb_substr')
            ? mb_substr($value, 0, $maxLength)
            : substr($value, 0, $maxLength);
    }

    public static function slug($value) {
        $value = strtolower(self::text($value, 255));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value);
        return trim($value, '-');
    }

    public static function sanitizeHtml($html) {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }
        if (!class_exists('DOMDocument')) {
            return nl2br(htmlspecialchars(strip_tags($html), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        }

        $allowedTags = [
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li',
            'h2', 'h3', 'h4', 'h5', 'blockquote', 'a', 'div', 'span', 'img',
        ];
        $allowedAttributes = ['href', 'title', 'target', 'rel', 'class', 'src', 'alt'];

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="ascb-content-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('ascb-content-root');
        if (!$root) {
            return '';
        }

        self::sanitizeNode($root, $allowedTags, $allowedAttributes);
        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }
        return $output;
    }

    private static function sanitizeNode(DOMNode $node, array $allowedTags, array $allowedAttributes) {
        for ($index = $node->childNodes->length - 1; $index >= 0; $index--) {
            $child = $node->childNodes->item($index);
            if ($child instanceof DOMComment) {
                $node->removeChild($child);
                continue;
            }
            if (!($child instanceof DOMElement)) {
                continue;
            }

            $tag = strtolower($child->tagName);
            if (!in_array($tag, $allowedTags, true)) {
                if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'form'], true)) {
                    $node->removeChild($child);
                } else {
                    self::sanitizeNode($child, $allowedTags, $allowedAttributes);
                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);
                }
                continue;
            }

            for ($attributeIndex = $child->attributes->length - 1; $attributeIndex >= 0; $attributeIndex--) {
                $attribute = $child->attributes->item($attributeIndex);
                $name = strtolower($attribute->name);
                if (!in_array($name, $allowedAttributes, true)) {
                    $child->removeAttribute($attribute->name);
                    continue;
                }
                if (in_array($name, ['href', 'src'], true) && !self::isSafeUrl($attribute->value)) {
                    $child->removeAttribute($attribute->name);
                }
            }

            if ($tag === 'a' && $child->getAttribute('target') === '_blank') {
                $child->setAttribute('rel', 'noopener noreferrer');
            }
            self::sanitizeNode($child, $allowedTags, $allowedAttributes);
        }
    }

    private static function isSafeUrl($url) {
        $url = trim(html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($url === '' || $url[0] === '#' || strpos($url, './') === 0 || strpos($url, '../') === 0) {
            return true;
        }
        if ($url[0] === '/') {
            return !isset($url[1]) || $url[1] !== '/';
        }
        return in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https', 'mailto'], true);
    }
}
