<?php


namespace TestPlugin\API;


use TestPlugin\Importer\JSONFileImporter;

class APIHandler
{
    private $endpointsConfig;
    public function __construct()
    {
        if (!defined('ABSPATH')) {
            die();
        }

        $this->endpointsConfig = [
            'POST' => [
                [
                    'namespace'  => 'testplugin/v1',
                    'action'     => 'handleJsonFileContents',
                    'callback'   => [JSONFileImporter::class, 'handleJsonFileContents']
                ]
            ]
        ];

        $this->registerEndpoints();

    }
    
    private function registerEndpoints(){
        add_action('rest_api_init', function (){
            foreach ($this->endpointsConfig as $method => $configs){
                foreach ($configs as $config){
                    register_rest_route( $config['namespace'], $config['action'], array(
                        'methods' => $method,
                        'callback' => $config['callback'],
                    ) );
                }
            }
        });
    }
}