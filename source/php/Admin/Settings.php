<?php

namespace ModularitySvgBackground\Admin;

class Settings
{
    public function __construct() {
        add_action('acf/init', array($this, 'registerSettings'));
    }

    /**
     * Register settings
     * @return void
     */
    public function registerSettings()
    {
        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page(array(
                'page_title'  => __("Modularity SVG background", 'modularity-svg-background'),
                'menu_title'  => __("Modularity SVG background Settings", 'modularity-svg-background'),
                'menu_slug'   => 'modularity-svg-background-settings',
                'parent_slug' => 'options-general.php',
                'capability'  => 'manage_options'
            ));
        }
    }
}
