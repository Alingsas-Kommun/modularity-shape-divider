<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_671a36a4089c2',
    'title' => __('Shape Divider', 'modularity-shape-divider'),
    'fields' => array(
        0 => array(
            'key' => 'field_671a3e3dc3c9b',
            'label' => __('Content', 'modularity-shape-divider'),
            'name' => '',
            'aria-label' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'placement' => 'top',
            'endpoint' => 0,
        ),
        1 => array(
            'key' => 'field_671a36a4c8b78',
            'label' => __('SVG file', 'modularity-shape-divider'),
            'name' => 'svg_file',
            'aria-label' => '',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'uploader' => '',
            'return_format' => 'id',
            'library' => 'all',
            'acfe_thumbnail' => 0,
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
            'preview_size' => 'medium',
        ),
        2 => array(
            'key' => 'field_671a3a28da7d3',
            'label' => __('Color', 'modularity-shape-divider'),
            'name' => 'color',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'none' => __('Standard', 'modularity-shape-divider'),
                'primary' => __('Primary color', 'modularity-shape-divider'),
                'primary-light' => __('Primary color (light)', 'modularity-shape-divider'),
                'primary-dark' => __('Primary color (dark)', 'modularity-shape-divider'),
                'secondary' => __('Secondary color', 'modularity-shape-divider'),
                'secondary-light' => __('Secondary color (light)', 'modularity-shape-divider'),
                'secondary-dark' => __('Secondary color (dark)', 'modularity-shape-divider'),
                'custom' => __('Custom', 'modularity-shape-divider'),
            ),
            'default_value' => __('none', 'modularity-shape-divider'),
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'allow_custom' => 0,
            'search_placeholder' => '',
        ),
        3 => array(
            'key' => 'field_6728d9600eff3',
            'label' => __('Custom color', 'modularity-shape-divider'),
            'name' => 'custom_color',
            'aria-label' => '',
            'type' => 'color_picker',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_671a3a28da7d3',
                        'operator' => '==',
                        'value' => 'custom',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'enable_opacity' => 0,
            'return_format' => 'string',
        ),
        4 => array(
            'key' => 'field_6728d919ff5ad',
            'label' => __('Replace embedded colors', 'modularity-shape-divider'),
            'name' => 'replace_svg_colors',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => __('Yes', 'modularity-shape-divider'),
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        5 => array(
            'key' => 'field_671a3e47c3c9c',
            'label' => __('Extra settings', 'modularity-shape-divider'),
            'name' => '',
            'aria-label' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'placement' => 'top',
            'endpoint' => 0,
        ),
        6 => array(
            'key' => 'field_671a3dfde182c',
            'label' => __('No top margin', 'modularity-shape-divider'),
            'name' => 'no_top_margin',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        7 => array(
            'key' => 'field_671a3e12e182d',
            'label' => __('No bottom margin', 'modularity-shape-divider'),
            'name' => 'no_bottom_margin',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        8 => array(
            'key' => 'field_671b5e8b9a5b9',
            'label' => __('Flip upside down', 'modularity-shape-divider'),
            'name' => 'flip_vertically',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        9 => array(
            'key' => 'field_671b65ff90cc2',
            'label' => __('Mirror', 'modularity-shape-divider'),
            'name' => 'flip_horizontally',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        10 => array(
            'key' => 'field_671a3e5fc3c9d',
            'label' => __('No height', 'modularity-shape-divider'),
            'name' => 'no_height',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        11 => array(
            'key' => 'field_671b71ca893c2',
            'label' => __('Overlap', 'modularity-shape-divider'),
            'name' => 'overlap',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_671a3e5fc3c9d',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'up' => __('Up', 'modularity-shape-divider'),
                'down' => __('Down', 'modularity-shape-divider'),
            ),
            'default_value' => __('down', 'modularity-shape-divider'),
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'allow_custom' => 0,
            'search_placeholder' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-shape-divider',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/shape-divider',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'left',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
    'acfe_display_title' => '',
    'acfe_autosync' => array(
        0 => 'php',
        1 => 'json',
    ),
    'acfe_form' => 0,
    'acfe_meta' => '',
    'acfe_note' => '',
));
}