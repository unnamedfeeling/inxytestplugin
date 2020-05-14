<?php
declare(strict_types=1);

namespace TestPlugin;


use TestPlugin\Wordpress\Posttypes;
use TestPlugin\Wordpress\SettingsPage;

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
//        if (!is_admin()) {
//            $routes = [
//                [
//                    'name' => 'testplugin',
//                    'callback' => [new PageLoadHandler, 'handlePageLoad'],
//                    'params' => [],
//                ],
//            ];
//
//            $router = new Router();
//            $router->registerRewriteRoutes($routes);
//        }

        new SettingsPage();

        add_filter( 'upload_mimes', [$this, 'addCustomMimetypes'] );

        add_action('admin_enqueue_scripts', [$this, 'loadAdminAssets']);
        add_action('wp_enqueue_scripts', [$this, 'loadAssets']);
        add_filter('init', [Posttypes::class, 'registerTypes']);

    }

    public static function loadAssets(): void
    {
        if(file_exists(TESTPLUGIN_DIR . '/assets/dist/inxytestFront.js')){
            wp_enqueue_script('testplugin-js', TESTPLUGIN_URL . '/assets/dist/inxytestFront.js', null, '1.0', true);
        }

        if (file_exists(TESTPLUGIN_DIR . '/assets/dist/inxytestFront.css')){
            wp_enqueue_style('testplugin-css', TESTPLUGIN_URL . '/assets/dist/inxytestFront.css', null, '1.0');
        }
    }

    public static function loadAdminAssets(): void
    {
        if(file_exists(TESTPLUGIN_DIR . '/assets/dist/inxytestAdmin.js')){
            if ( ! did_action( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            }

            wp_enqueue_script('testpluginAdmin-js', TESTPLUGIN_URL . '/assets/dist/inxytestAdmin.js', ['jquery', 'media-upload', 'thickbox'], '1.0');
        }

        if (file_exists(TESTPLUGIN_DIR . '/assets/dist/inxytestAdmin.css')){
            wp_enqueue_style('testpluginAdmin-css', TESTPLUGIN_URL . '/assets/dist/inxytestAdmin.css', null, '1.0');
        }
    }

    public static function addCustomMimetypes( array $mime_types ) {
        return array_merge(
            $mime_types,
            [
                'svg' => 'image/svg+xml',
//                'json' => 'application/json',
                'json' => 'text/plain',
            ]
        );
    }
}
