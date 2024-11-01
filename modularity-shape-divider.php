<?php

/**
 * Plugin Name:       Modularity Shape Divider
 * Plugin URI:        https://github.com/alingsas-kommun/modularity-shape-divider
 * Description:       Display SVG background images that can overflow to upper or lower modules.
 * Version:           0.1.0
 * Author:            Consid
 * Author URI:        https://github.com/alingsas-kommun/
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       mod-shape-divider
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('MODULARITY_SHAPE_DIVIDER_PATH', plugin_dir_path(__FILE__));
define('MODULARITY_SHAPE_DIVIDER_URL', plugins_url('', __FILE__));
define('MODULARITY_SHAPE_DIVIDER_TEMPLATE_PATH', MODULARITY_SHAPE_DIVIDER_PATH . 'templates/');
define('MODULARITY_SHAPE_DIVIDER_VIEW_PATH', MODULARITY_SHAPE_DIVIDER_PATH . 'views/');
define('MODULARITY_SHAPE_DIVIDER_MODULE_VIEW_PATH', plugin_dir_path(__FILE__) . 'source/php/Module/views');
define('MODULARITY_SHAPE_DIVIDER_MODULE_PATH', MODULARITY_SHAPE_DIVIDER_PATH . 'source/php/Module/');

load_plugin_textdomain('modularity-shape-divider', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once MODULARITY_SHAPE_DIVIDER_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once MODULARITY_SHAPE_DIVIDER_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new ModularityShapeDivider\Vendor\Psr4ClassLoader();
$loader->addPrefix('ModularityShapeDivider', MODULARITY_SHAPE_DIVIDER_PATH);
$loader->addPrefix('ModularityShapeDivider', MODULARITY_SHAPE_DIVIDER_PATH . 'source/php/');
$loader->register();

// Acf auto import and export
$acfExportManager = new \AcfExportManager\AcfExportManager();
$acfExportManager->setTextdomain('modularity-shape-divider');
$acfExportManager->setExportFolder(MODULARITY_SHAPE_DIVIDER_PATH . 'source/php/AcfFields/');
$acfExportManager->autoExport(array(
    'shape-divider-module' => 'group_671a36a4089c2'
));
$acfExportManager->import();

// Modularity 3.0 ready - ViewPath for Component library
add_filter('/Modularity/externalViewPath', function ($arr) {
    $arr['mod-shape-divider'] = MODULARITY_SHAPE_DIVIDER_MODULE_VIEW_PATH;
    return $arr;
}, 10, 3);

// Start application
new ModularityShapeDivider\App();
