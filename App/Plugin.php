<?php
declare(strict_types=1);

namespace TestPlugin;


use TestPlugin\Wordpress\Posttypes;

class Plugin
{
    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        if (!defined('ABSPATH')) {
            die();
        }
    }

    public function run(): void
    {
        if (!is_admin()) {
            $routes = [
                [
                    'name' => 'testplugin',
                    'callback' => [new PageLoadHandler, 'handlePageLoad'],
                    'params' => [],
                ],
            ];

            $router = new Router();
            $router->registerRewriteRoutes($routes);
        }

        add_action('wp_enqueue_scripts', [Plugin::class, 'loadAssets']);
        add_filter('init', [Posttypes::class, 'registerTypes']);

    }

    public static function loadAssets(): void
    {
        wp_enqueue_script('testplugin-js', TESTPLUGIN_URL . '/assets/dist/bundle.js', null, '1.0', true);
        wp_enqueue_style('testplugin-css', TESTPLUGIN_URL . '/assets/dist/bundle.css', null, '1.0');
    }
}
