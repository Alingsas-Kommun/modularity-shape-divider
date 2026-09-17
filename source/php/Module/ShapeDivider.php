<?php

namespace ModularityShapeDivider\Module;

use ModularityShapeDivider\Helper\CacheBust;

/**
 * Class ShapeDivider
 * @package ShapeDivider\Module
 */
class ShapeDivider extends \Modularity\Module {
    public $slug = 'shape-divider';

    public $supports = array();

    public function init() {
        $this->nameSingular = __("Shape Divider", 'modularity-shape-divider');
        $this->namePlural = __("Shape Divider", 'modularity-shape-divider');
        $this->description = __("Display SVG background images that can overflow to upper or lower modules.", 'modularity-shape-divider');
    }

    /**
     * Data array
     * @return array $data
     */
    public function data(): array {
        $data = (array) \Modularity\Helper\FormatObject::camelCase(
            $this->getFields()
        );

        // Gutenberg blocks have no WP_Post, so $this->post_type is null.
        $baseClass = 'modularity-mod-' . $this->slug;
        $svg_code = $this->getSvgCode($data['svgFile'] ?? null);

        if (!empty($data['replaceSvgColors'])) {
            $color = ($data['color'] ?? '') === 'custom'
                ? ($data['customColor'] ?? 'currentColor')
                : 'currentColor';
            $svg_code = $this->replaceSvgColors($svg_code, $color);
        }

        $instanceId = $this->ID ?: uniqid('block-');
        $extraClasses = $this->getExtraClasses($data);

        if ($this->mode === 'module' && $extraClasses !== []) {
            $ID = $this->ID;
            add_filter('Modularity/Display/BeforeModule::classes', function ($classes, $args, $post_type, $current_ID) use ($extraClasses, $ID) {
                if ($post_type !== 'mod-shape-divider') {
                    return $classes;
                }

                if ($current_ID !== $ID) {
                    return $classes;
                }

                return array_merge($classes, $extraClasses);
            }, 10, 4);
        }

        $data['instanceClass'] = $baseClass . '-' . $instanceId;
        $data['baseClass'] = $baseClass;
        $data['svgCode'] = $svg_code;
        $data['color'] = $data['color'] ?? 'none';
        $data['classes'] = implode(' ', array_merge(
            [$baseClass . '-wrapper', $data['instanceClass']],
            $extraClasses
        ));

        return $data;
    }

    /**
     * Blade Template
     * @return string
     */
    public function template(): string {
        return "shape-divider.blade.php";
    }

    /**
     * Style - Register & adding css
     * @return void
     */
    public function style() {
        //Register custom css
        wp_register_style(
            'modularity-shape-divider',
            MODULARITY_SHAPE_DIVIDER_URL . '/dist/' . CacheBust::name('css/modularity-shape-divider.css'),
            null,
            '1.0.0'
        );

        //Enqueue
        wp_enqueue_style('modularity-shape-divider');
    }

    /**
     * Script - Register & adding scripts
     * @return void
     */
    public function script() {
        //Register custom css
        wp_register_script(
            'modularity-shape-divider',
            MODULARITY_SHAPE_DIVIDER_URL . '/dist/' . CacheBust::name('js/modularity-shape-divider.js'),
            null,
            '1.0.0'
        );

        //Enqueue
        wp_enqueue_script('modularity-shape-divider');
    }

    /**
     * Modifier classes from extra settings.
     *
     * @param array $data
     * @return string[]
     */
    private function getExtraClasses(array $data): array {
        $classes = [];

        if (!empty($data['noBottomMargin'])) {
            $classes[] = 'no-bottom-margin';
        }

        if (!empty($data['noTopMargin'])) {
            $classes[] = 'no-top-margin';
        }

        if (!empty($data['noHeight'])) {
            $classes[] = 'no-height';
            $classes[] = ($data['overlap'] ?? '') === 'up' ? 'overlap-up' : 'overlap-down';
        }

        if (!empty($data['flipHorizontally'])) {
            $classes[] = 'flip-horizontally';
        }

        if (!empty($data['flipVertically'])) {
            $classes[] = 'flip-vertically';
        }

        return $classes;
    }

    /**
     * Load SVG markup from the attachment file on disk.
     *
     * @param mixed $attachmentId ACF svg_file attachment ID.
     * @return string
     */
    private function getSvgCode($attachmentId): string
    {
        if (empty($attachmentId)) {
            return '';
        }

        $svgPath = get_attached_file($attachmentId);
        if (is_string($svgPath) && is_readable($svgPath)) {
            $svgCode = file_get_contents($svgPath);
            return is_string($svgCode) ? $svgCode : '';
        }

        return $this->getSvgCodeFromRemoteFallback($attachmentId);
    }

    /**
     * TEMPORARY local-dev fallback: fetch SVG via attachment URL when the file is missing on disk.
     * Remove this method and its call in getSvgCode() when local uploads are available.
     *
     * @param mixed $attachmentId ACF svg_file attachment ID.
     * @return string
     */
    private function getSvgCodeFromRemoteFallback($attachmentId): string
    {
        $url = wp_get_attachment_url($attachmentId);
        if (!$url) {
            return '';
        }

        $response = wp_remote_get($url);
        if (is_wp_error($response)) {
            return '';
        }

        if ((int) wp_remote_retrieve_response_code($response) !== 200) {
            return '';
        }

        $body = wp_remote_retrieve_body($response);
        return is_string($body) ? $body : '';
    }

    private function replaceSvgColors($svg, $color) {
        $pattern = '/\b(color|fill|stroke)\s*=\s*"[#\w\d\s\(\),.]+"/i';
        $replacement = '$1="' . $color . '"';

        $svg = preg_replace($pattern, $replacement, $svg);

        return $svg;
    }

    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script            Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */
}
