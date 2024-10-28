<?php

/**
 * Plugin Name:       Modularity SVG background
 * Plugin URI:        https://github.com/alingsas-kommun/modularity-svg-background
 * Description:       Display SVG background images that can overflow to upper or lower modules.
 * Version:           0.1.0
 * Author:            Consid
 * Author URI:        https://github.com/alingsas-kommun/
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       mod-svg-background
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('MODULARITY_SVG_BACKGROUND_PATH', plugin_dir_path(__FILE__));
define('MODULARITY_SVG_BACKGROUND_URL', plugins_url('', __FILE__));
define('MODULARITY_SVG_BACKGROUND_TEMPLATE_PATH', MODULARITY_SVG_BACKGROUND_PATH . 'templates/');
define('MODULARITY_SVG_BACKGROUND_VIEW_PATH', MODULARITY_SVG_BACKGROUND_PATH . 'views/');
define('MODULARITY_SVG_BACKGROUND_MODULE_VIEW_PATH', plugin_dir_path(__FILE__) . 'source/php/Module/views');
define('MODULARITY_SVG_BACKGROUND_MODULE_PATH', MODULARITY_SVG_BACKGROUND_PATH . 'source/php/Module/');

load_plugin_textdomain('modularity-svg-background', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once MODULARITY_SVG_BACKGROUND_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once MODULARITY_SVG_BACKGROUND_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new ModularitySvgBackground\Vendor\Psr4ClassLoader();
$loader->addPrefix('ModularitySvgBackground', MODULARITY_SVG_BACKGROUND_PATH);
$loader->addPrefix('ModularitySvgBackground', MODULARITY_SVG_BACKGROUND_PATH . 'source/php/');
$loader->register();

// Acf auto import and export
$acfExportManager = new \AcfExportManager\AcfExportManager();
$acfExportManager->setTextdomain('modularity-svg-background');
$acfExportManager->setExportFolder(MODULARITY_SVG_BACKGROUND_PATH . 'source/php/AcfFields/');
$acfExportManager->autoExport(array(
    'svg-background-module' => 'group_671a36a4089c2'
));
$acfExportManager->import();

// Modularity 3.0 ready - ViewPath for Component library
add_filter('/Modularity/externalViewPath', function ($arr) {
    $arr['mod-svg-background'] = MODULARITY_SVG_BACKGROUND_MODULE_VIEW_PATH;
    return $arr;
}, 10, 3);

// Start application
new ModularitySvgBackground\App();
