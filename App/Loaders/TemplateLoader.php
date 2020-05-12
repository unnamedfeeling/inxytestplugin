<?php
declare(strict_types=1);

namespace TestPlugin\Loaders;

use Gamajo_Template_Loader;

class TemplateLoader extends Gamajo_Template_Loader
{
    /**
     * TemplateLoader constructor.
     */
    public function __construct()
    {
        if (!defined('ABSPATH')) {
            die();
        }
    }

    /**
     * Prefix for filter names.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $filter_prefix = 'testplugin';

    /**
     * Directory name where custom templates for this plugin should be found in the theme.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $theme_template_directory = 'testplugin';

    /**
     * Reference to the root directory path of this plugin.
     *
     * Can either be a defined constant, or a relative reference from where the subclass lives.
     *
     * In this case, `MEAL_PLANNER_PLUGIN_DIR` would be defined in the root plugin file as:
     *
     * ~~~
     * define( 'MEAL_PLANNER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
     * ~~~
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $plugin_directory = TESTPLUGIN_DIR;

    /**
     * Directory name where templates are found in this plugin.
     *
     * Can either be a defined constant, or a relative reference from where the subclass lives.
     *
     * e.g. 'templates' or 'includes/templates', etc.
     *
     * @since 1.1.0
     *
     * @var string
     */
    protected $plugin_template_directory = 'Templates';
}
