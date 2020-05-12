<?php


namespace TestPlugin\Wordpress;


class Posttypes
{
    /**
     * @var array
     */
    private $posttypes;

    /**
     * Posttypes constructor.
     */
    public function __construct()
    {
        if (!defined('ABSPATH')) {
            die();
        }

        $this->posttypes = [
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

    public function registerTypes()
    {
        foreach($this->posttypes as $key => $conf){
            register_post_type($key, $conf);
        }
    }
}