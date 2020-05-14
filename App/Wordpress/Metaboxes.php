<?php
declare(strict_types=1);


namespace TestPlugin\Wordpress;


use WP_Post;

class Metaboxes
{
    /* construct function, add meta box and save action hooks */
    function __construct(array $options) {

        if (!defined('ABSPATH')) {
            die();
        }

        $this->options = $options;
        $this->prefix = $this->options['id'] .'_';
        add_action( 'add_meta_boxes', array( &$this, 'create' ) );
        add_action( 'save_post', array( &$this, 'save' ), 1, 2 );
    }

    /* function that creates the metabox */
    function create() {
        /* for each post type defined */
        foreach ($this->options['post_type'] as $post_type):

            add_meta_box( $this->options['id'], $this->options['name'], array(&$this, 'fill'), $post_type, $this->options['position'], $this->options['priority']);

        endforeach;
    }

    /* meta box html */
    function fill(){
        global $post;

        if( isset( $this->options['args'] ) ):

            $metabox_html = '<table class="form-table"><tbody>';

            /* for each option defined */
            foreach ( $this->options['args'] as $param ):

                $metabox_html .= '<tr>';

                /* get option value and set default parameters */
                if( !$value = get_post_meta($post->ID, $this->prefix . $param['id'] , true) )
                    if( isset( $param['default'] ) )
                        $value = $param['default'];

                switch ( $param['type'] ) :

                    /* <input type=text> */
                    case 'text':{
                        $metabox_html .= '<th style="font-weight:normal"><label for="' . $this->prefix . $param['id'] . '">' . $param['label'] . '</label></th><td><input name="' . $this->prefix .$param['id'] . '" type="text" id="' . $this->prefix .$param['id'] . '" value="' . esc_attr($value) . '" ';
                        $metabox_html .= 'class="regular-text" /><br />';
                        if( isset( $param['description'] ) )
                            $metabox_html .= '<span class="description">' . $param['description'] . '</span>';
                        $metabox_html .= '</td>';
                        break;
                    }

                    /* <input type=checkbox> */
                    case 'checkbox':{
                        $metabox_html .= '<th style="font-weight:normal"><label for="' . $this->prefix .$param['id'] . '">' . $param['label'] . '</label></th><td><label for="' . $this->prefix .$param['id'] . '"><input name="' . $this->prefix .$param['id'] . '" type="checkbox" id="' . $this->prefix .$param['id'] . '"';
                        if( $value == 'on')
                            $metabox_html .= ' checked="checked"';
                        $metabox_html .= ' />';
                        if( isset( $param['description'] ) )
                            $metabox_html .= '<span class="description">' . $param['description'] . '</span>';
                        $metabox_html .= '</td>';
                        break;
                    }

                endswitch;
                $metabox_html .= '</tr>';
            endforeach;

            $metabox_html .= '</tbody></table>';

            /* echo metabox content*/
            echo $metabox_html;

        endif;
    }


    function save( int $post_id, WP_Post $post ){

        /* if this post type do not have metabox */
        if ( !in_array($post->post_type, $this->options['post_type']))
            return;

        foreach ( $this->options['args'] as $param ) {

            if ( isset( $_POST[ $this->prefix . $param['id'] ] ) && trim( $_POST[ $this->prefix . $param['id'] ] ) ) {
                update_post_meta( $post_id, $this->prefix . $param['id'], $_POST[ $this->prefix . $param['id'] ] );
            } else {
                delete_post_meta( $post_id, $this->prefix . $param['id'] );
            }

        }
    }
}