<?php

namespace ModularityShapeDivider;

use ModularityShapeDivider\Helper\CacheBust;

class App
{
    public function __construct()
    {

        //Init subset
        new Admin\Settings();

        //Register module
        add_action('plugins_loaded', array($this, 'registerModule'));

        // Add view paths
        add_filter('Municipio/blade/view_paths', array($this, 'addViewPaths'), 1, 1);

        // Add aria-hidden
        add_filter('Modularity/Display/BeforeModule', function($beforeModule, $args, $post_type, $ID) {
            if ($post_type === 'mod-shape-divider') {
                $beforeModule = str_replace('>', ' aria-hidden="true">', $beforeModule);
            }

            return $beforeModule;
        }, 10, 4);
        add_filter('Modularity/Display/mod-shape-divider/Markup', function($markup, $module) {
            //var_dump($module);
            return $markup;
        }, 10, 2);
    }

    /**
     * Register the module
     * @return void
     */
    public function registerModule()
    {
        if (function_exists('modularity_register_module')) {
            modularity_register_module(
                MODULARITY_SHAPE_DIVIDER_MODULE_PATH,
                'ShapeDivider'
            );
        }
    }

    /**
     * Add searchable blade template paths
     * @param array  $array Template paths
     * @return array        Modified template paths
     */
    public function addViewPaths($array)
    {
        // If child theme is active, insert plugin view path after child views path.
        if (is_child_theme()) {
            array_splice($array, 2, 0, array(MODULARITY_SHAPE_DIVIDER_VIEW_PATH));
        } else {
            // Add view path first in the list if child theme is not active.
            array_unshift($array, MODULARITY_SHAPE_DIVIDER_VIEW_PATH);
        }

        return $array;
    }
}
