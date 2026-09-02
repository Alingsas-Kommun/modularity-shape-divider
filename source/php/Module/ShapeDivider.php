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

    private const EXTRA_SETTINGS = [
        'noTopMargin',
        'noBottomMargin',
        'noHeight',
        'flipVertically',
        'flipHorizontally',
    ];

    /**
     * Prefer legacy Municipio 6 names when still defined, then Styleguide v3 tokens.
     */
    private const COLOR_TOKENS = [
        'primary' => ['--color--primary', '--color-primary'],
        'primary-light' => ['--color-primary-light', '--color--surface', '--color--primary-alt', '--color--primary'],
        'primary-dark' => ['--color-primary-dark', '--color--primary-border', '--color--primary'],
        'secondary' => ['--color--secondary', '--color-secondary'],
        'secondary-light' => ['--color-secondary-light', '--color--secondary'],
        'secondary-dark' => ['--color-secondary-dark', '--color--secondary-border', '--color--secondary'],
    ];

    /**
     * @var array<int, bool>
     */
    private static $registeredLayoutFilters = [];

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
        $data = array();

        $baseClass = "modularity-{$this->post_type}";

        $data = array_merge($data, (array) \Modularity\Helper\FormatObject::camelCase(
            get_fields($this->ID)
        ));

        $color = $data['color'] ?? 'none';
        $customColor = $data['customColor'] ?? '';
        $replaceSvgColors = !empty($data['replaceSvgColors']);
        $svgPath = get_attached_file($data['svgFile'] ?? 0);
        $svgCode = ($svgPath && is_string($svgPath)) ? (string) file_get_contents($svgPath) : '';

        if ($replaceSvgColors && $svgCode !== '') {
            if ($color === 'custom') {
                $svgCode = $this->replaceSvgColors($svgCode, $customColor);
            } else {
                $svgCode = $this->replaceSvgColors($svgCode, 'currentColor');
            }
        }

        $data['instanceClass'] = $baseClass . '-' . $this->ID;
        $data['baseClass'] = $baseClass;
        $data['svgCode'] = $svgCode;
        $data['cssColor'] = $this->getCssColorValue($color, $customColor);
        $data['color'] = $color;

        $classes = [$baseClass . '-wrapper'];

        if ($replaceSvgColors && $color !== 'custom' && $color !== 'none') {
            $classes[] = 'is-using-current-color';
        }

        $this->registerLayoutClasses($data);

        $data['classes'] = implode(' ', $classes);

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
        wp_register_style(
            'modularity-shape-divider',
            MODULARITY_SHAPE_DIVIDER_URL . '/dist/' . CacheBust::name('css/modularity-shape-divider.css'),
            null,
            '1.0.0'
        );

        wp_enqueue_style('modularity-shape-divider');
    }

    /**
     * Script - Register & adding scripts
     * @return void
     */
    public function script() {
        wp_register_script(
            'modularity-shape-divider',
            MODULARITY_SHAPE_DIVIDER_URL . '/dist/' . CacheBust::name('js/modularity-shape-divider.js'),
            null,
            '1.0.0'
        );

        wp_enqueue_script('modularity-shape-divider');
    }

    /**
     * Resolve a palette or custom color for `currentColor` on the SVG.
     *
     * @param string $color ACF color choice.
     * @param string $customColor Hex value when $color is custom.
     * @return string
     */
    private function getCssColorValue(string $color, string $customColor): string {
        if ($color === 'none' || $color === '') {
            return '';
        }

        if ($color === 'custom') {
            return $customColor;
        }

        $tokens = self::COLOR_TOKENS[$color] ?? ['--color--' . $color, '--color-' . $color];
        $css = 'var(' . $tokens[0];

        for ($i = 1, $count = count($tokens); $i < $count; $i++) {
            $css .= ', var(' . $tokens[$i];
        }

        $css .= str_repeat(')', count($tokens));

        return $css;
    }

    /**
     * Attach layout modifier classes to the module wrapper once per instance.
     *
     * @param array $data Module view data.
     * @return void
     */
    private function registerLayoutClasses(array $data): void {
        $hasTruthyExtraSetting = !empty(array_filter(
            array_intersect_key($data, array_flip(self::EXTRA_SETTINGS))
        ));

        if (!$hasTruthyExtraSetting) {
            return;
        }

        $ID = $this->ID;
        if (isset(self::$registeredLayoutFilters[$ID])) {
            return;
        }

        self::$registeredLayoutFilters[$ID] = true;

        add_filter('Modularity/Display/BeforeModule::classes', function ($classes, $args, $post_type, $current_ID) use ($data, $ID) {
            if ($post_type === 'mod-shape-divider' && $current_ID === $ID) {
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
            }

            return $classes;
        }, 10, 4);
    }

    /**
     * Replace fill, stroke and color attributes on the SVG.
     *
     * @param string $svg SVG markup.
     * @param string $color Replacement color (currentColor or hex).
     * @return string
     */
    private function replaceSvgColors($svg, $color) {
        if (!is_string($svg) || $svg === '' || !is_string($color) || $color === '') {
            return is_string($svg) ? $svg : '';
        }

        $pattern = '/\b(color|fill|stroke)\s*=\s*([\'"])[#\w\d\s\(\),.]+\\2/i';
        $replacement = '$1="' . $color . '"';

        $svg = preg_replace($pattern, $replacement, $svg);

        return is_string($svg) ? $svg : '';
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
