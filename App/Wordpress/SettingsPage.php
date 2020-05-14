<?php
declare(strict_types=1);


namespace TestPlugin\Wordpress;


use TestPlugin\Loaders\TemplateLoader;

class SettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        if (!defined('ABSPATH')) {
            die();
        }

        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            __('Test plugin settings', 'inxytest'),
            __('Test plugin', 'inxytest'),
            'manage_options',
            'inxytest-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'inxytest_option' );


        $tplLoader = new TemplateLoader();

        $tpldata = ['options' => $this->options];

        $tplLoader->set_template_data($tpldata, 'data');
        $tplLoader->get_template_part('admin');
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'inxytest_option_group', // Option group
            'inxytest_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            __('Test plugin settings', 'inxytest'), // Title
            array( $this, 'print_section_info' ), // Callback
            'inxytest-admin' // Page
        );

        add_settings_field(
            'jsonfile',
            __('File to import', 'inxytest'),
            array( $this, 'mediaUpload_cb' ),
            'inxytest-admin',
            'setting_section_id'
        );

        add_settings_section(
            'setting_section_separator1', // ID
            '', // Title
            array( $this, 'print_section_separator_or' ), // Callback
            'inxytest-admin' // Page
        );

        add_settings_section(
            'direct_upload_section', // ID
            '', // Title
            array( $this, 'directUpload_cb' ), // Callback
            'inxytest-admin' // Page
        );


    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( array $input )
    {
        $new_input = array();

        if( isset( $input['jsonfile'] ) )
            $new_input['jsonfile'] = sanitize_text_field( $input['jsonfile'] );

        if( isset( $input['url'] ) )
            $new_input['url'] = sanitize_text_field( $input['url'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print __('Enter your settings below:', 'inxytest');
    }

    /**
     * Print the Section separator 'or'
     */
    public function print_section_separator_or()
    {
        print __('or', 'inxytest');
    }

    public function mediaUpload_cb()
    {
        printf(
            ' <input name="inxytest_option[jsonfile]" type="text" value="%1$s" />
                <input type="button" value="%2s" class="button button-primary js-inxytestUploadBtn" /><br/>',
            isset( $this->options['jsonfile'] ) ? esc_attr( $this->options['jsonfile']) : '',
            __('Select or upload file', 'inxytest')
        );
    }

    public function directUpload_cb()
    {
        printf(
            ' <input name="inxytest_option[url]" type="text" value="%1$s" />
                <input type="button" value="%2s" class="button button-primary js-inxytestDirectUploadBtn" /><br/>',
            isset( $this->options['url'] ) ? esc_attr( $this->options['url']) : '',
            __('Upload from this url', 'inxytest')
        );
    }
}