<?php

namespace App\Support;

use App\Models\BangunDatarShape;
use Illuminate\Support\Facades\Cache;

class CollaborationSubmissionFormatter
{
    /**
     * Fallback SVG icons keyed by preset Misi 2 object keys.
     *
     * @var array<string, string>
     */
    private const FALLBACK_BENDA_SVGS = [
        'atap' => '<svg viewBox="0 0 40 30" class="w-full h-full"><polygon points="20,2 2,28 38,28" fill="#E57354" stroke="#78350F" stroke-width="2"/></svg>',
        'dinding' => '<svg viewBox="0 0 40 30" class="w-full h-full"><rect x="4" y="4" width="32" height="22" fill="#EDE9FE" stroke="#7C3AED" stroke-width="2"/></svg>',
        'badan' => '<svg viewBox="0 0 40 30" class="w-full h-full"><rect x="4" y="4" width="32" height="22" fill="#FEF3C7" stroke="#78350F" stroke-width="2"/></svg>',
        'kanopi' => '<svg viewBox="0 0 40 30" class="w-full h-full"><polygon points="12,6 36,6 28,24 4,24" fill="#DCFCE7" stroke="#16A34A" stroke-width="2"/></svg>',
        'bulan' => '<svg viewBox="0 0 40 30" class="w-full h-full"><circle cx="20" cy="15" r="11" fill="#E0F2FE" stroke="#0284C7" stroke-width="2"/></svg>',
        'rumbai' => '<svg viewBox="0 0 40 30" class="w-full h-full"><polygon points="20,3 32,15 20,27 8,15" fill="#FCE7F3" stroke="#DB2777" stroke-width="2"/></svg>',
        'kotak' => '<svg viewBox="0 0 40 30" class="w-full h-full"><rect x="8" y="3" width="24" height="24" rx="1" fill="#FEF3C7" stroke="#D97706" stroke-width="2"/></svg>',
        'penyangga' => '<svg viewBox="0 0 40 30" class="w-full h-full"><polygon points="5,6 35,6 29,24 11,24" fill="#F3E8FF" stroke="#9333EA" stroke-width="2"/></svg>',
        'payung' => '<svg viewBox="0 0 40 30" class="w-full h-full"><polygon points="20,4 4,26 36,26" fill="#FEE2E2" stroke="#EF4444" stroke-width="2"/></svg>',
    ];

    /**
     * Build the 4 namespaced motif SVG canvases for read-only display.
     *
     * @return array<int, string>
     */
    public static function buildCanvases(string $prefix, string $partClass): array
    {
        $svgLoader = static fn (string $file): string => Cache::remember(
            "svg_{$file}",
            3600,
            static fn () => file_exists(public_path("assets/{$file}")) ? file_get_contents(public_path("assets/{$file}")) : ''
        );

        $namespaceSvg = static function (string $svg) use ($prefix, $partClass): string {
            $svg = str_replace(
                ['id="c1-', 'id="c2-', 'id="c3-', 'id="c4-', 'class="colorable-part"'],
                ["id=\"{$prefix}c1-", "id=\"{$prefix}c2-", "id=\"{$prefix}c3-", "id=\"{$prefix}c4-", "class=\"{$partClass}\""],
                $svg
            );

            return str_replace(
                ['id="clip0_', 'url(#clip0_', 'id="path-', 'url(#path-'],
                ["id=\"{$prefix}clip0_", "url(#{$prefix}clip0_", "id=\"{$prefix}path-", "url(#{$prefix}path-"],
                $svg
            );
        };

        return [
            1 => $namespaceSvg($svgLoader('canvas1a.svg')),
            2 => $namespaceSvg($svgLoader('canvas2a.svg')),
            3 => $namespaceSvg($svgLoader('frame3.svg')),
            4 => $namespaceSvg($svgLoader('frame4.svg')),
        ];
    }

    /**
     * Sanitize saved SVG fill colors before embedding in JS or HTML attributes.
     *
     * @param  array<mixed>  $rawColors
     * @return array<string, string>
     */
    public static function sanitizeColorMap(array $rawColors): array
    {
        $sanitized = [];
        foreach ($rawColors as $partId => $color) {
            if (is_string($partId) && preg_match('/^[A-Za-z0-9_-]{1,64}$/', $partId)
                && is_string($color) && preg_match('/^(#[0-9A-Fa-f]{3,8}|[a-zA-Z]{3,20})$/', $color)) {
                $sanitized[$partId] = $color;
            }
        }

        return $sanitized;
    }

    /**
     * Format Misi 2 identification rows with sanitized visual SVGs and shape metadata.
     *
     * @param  array<mixed>  $rawIdentifications
     * @return array<int, array<string, ?string>>
     */
    public static function formatIdentifications(array $rawIdentifications): array
    {
        $shapesByKey = BangunDatarShape::getCachedActive()->keyBy('shape_key');
        $items = [];

        foreach ($rawIdentifications as $item) {
            if (! is_array($item)) {
                continue;
            }

            $bendaKey = (string) ($item['bendaKey'] ?? ($item['benda_key'] ?? ''));
            $bendaLabel = (string) ($item['bendaName'] ?? ($item['benda_name'] ?? ($item['benda'] ?? '-')));
            $rawSnippet = isset($item['svgSnippet']) && is_string($item['svgSnippet']) ? $item['svgSnippet'] : '';
            $safeBendaSvg = self::sanitizeBendaSvgSnippet($rawSnippet);

            if ($safeBendaSvg === '' && $bendaKey !== '' && isset(self::FALLBACK_BENDA_SVGS[$bendaKey])) {
                $safeBendaSvg = self::FALLBACK_BENDA_SVGS[$bendaKey];
            }

            $shapeKey = (string) ($item['shape'] ?? '');
            $shapeModel = $shapeKey !== '' ? $shapesByKey->get($shapeKey) : null;
            $shapeLabel = $shapeModel
                ? $shapeModel->name
                : (string) ($item['shapeName'] ?? ($item['bangun_datar'] ?? ($shapeKey !== '' ? $shapeKey : '-')));

            $items[] = [
                'benda_label' => $bendaLabel,
                'benda_svg' => $safeBendaSvg,
                'shape_label' => $shapeLabel,
                'shape_svg' => $shapeModel ? $shapeModel->svg_inline : '',
                'shape_color' => $shapeModel ? ($shapeModel->color ?: '#7C3AED') : '#7C3AED',
                'shape_custom_image' => ($shapeModel && $shapeModel->custom_image_path) ? asset($shapeModel->custom_image_path) : null,
                'ciri' => (string) ($item['ciri'] ?? ($item['ciri_ciri'] ?? '-')),
            ];
        }

        return $items;
    }

    /**
     * Sanitize an inline SVG snippet captured from the Misi 2 canvas before rendering.
     */
    public static function sanitizeBendaSvgSnippet(string $rawSvg): string
    {
        $trimmed = trim($rawSvg);
        if ($trimmed === '' || ! str_contains($trimmed, '<svg')) {
            return '';
        }

        $allowedTags = ['svg', 'path', 'rect', 'circle', 'polygon', 'polyline', 'line', 'ellipse', 'g'];
        $allowedAttrs = [
            'viewbox', 'd', 'x', 'y', 'width', 'height', 'cx', 'cy', 'r', 'rx', 'ry',
            'x1', 'y1', 'x2', 'y2', 'points', 'fill', 'stroke', 'stroke-width',
            'stroke-linecap', 'stroke-linejoin', 'fill-opacity', 'transform', 'xmlns',
        ];

        $previousState = libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $loaded = $dom->loadHTML('<?xml encoding="utf-8" ?>'.$trimmed, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        if (! $loaded) {
            return '';
        }

        $svgNode = $dom->getElementsByTagName('svg')->item(0);
        if (! $svgNode instanceof \DOMElement) {
            return '';
        }

        $cleanNode = static function (\DOMElement $node) use (&$cleanNode, $allowedTags, $allowedAttrs): void {
            $children = [];
            foreach ($node->childNodes as $child) {
                $children[] = $child;
            }
            foreach ($children as $child) {
                if ($child instanceof \DOMElement) {
                    if (! in_array(strtolower($child->tagName), $allowedTags, true)) {
                        $node->removeChild($child);

                        continue;
                    }
                    $cleanNode($child);
                } elseif (! ($child instanceof \DOMText) || trim($child->textContent) !== '') {
                    $node->removeChild($child);
                }
            }

            $attrNames = [];
            foreach ($node->attributes as $attr) {
                $attrNames[] = $attr->nodeName;
            }
            foreach ($attrNames as $attrName) {
                $lower = strtolower($attrName);
                if (! in_array($lower, $allowedAttrs, true)) {
                    $node->removeAttribute($attrName);

                    continue;
                }
                $val = $node->getAttribute($attrName);
                if (preg_match('/javascript:|data:|expression\(/i', $val)) {
                    $node->removeAttribute($attrName);
                }
            }
        };

        $cleanNode($svgNode);
        $svgNode->setAttribute('class', 'w-full h-full');

        return $dom->saveXML($svgNode) ?: '';
    }
}
