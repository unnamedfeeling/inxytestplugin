<?php


namespace TestPlugin\ACF;


class ACFBootstrap
{
    public function __construct()
    {
        define( 'MY_ACF_PATH', TESTPLUGIN_DIR . '/vendor/acf/' );
        define( 'MY_ACF_URL', TESTPLUGIN_URL . '/vendor/acf/' );

        include_once( MY_ACF_PATH . 'acf.php' );

        add_filter('acf/settings/url', [$this, 'my_acf_settings_url']);
        add_filter('acf/settings/show_admin', [$this, 'my_acf_settings_show_admin']);

    }

    function my_acf_settings_url( $url ) {
        return MY_ACF_URL;
    }

    public function my_acf_settings_show_admin( $show_admin ) {
        return false;
    }

}