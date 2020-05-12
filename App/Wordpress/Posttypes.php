<?php
declare(strict_types=1);


namespace TestPlugin\Wordpress;


class Posttypes
{
    /**
     * Posttypes constructor.
     */
    public function __construct()
    {
        if (!defined('ABSPATH')) {
            die();
        }
    }

    public static function registerTypes()
    {
        foreach(self::getPosttypesConfig() as $key => $conf){
            register_post_type($key, $conf);
        }
    }

    private static function getPosttypesConfig(){
        return [
            'servers' => [
                'labels'             => [
                    'name'               => __('Servers', 'inxytest'),
                    'singular_name'      => __('Server', 'inxytest'),
                ],
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => true,
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => ['title','editor','author','thumbnail','excerpt','comments']
            ]
        ];
    }
}