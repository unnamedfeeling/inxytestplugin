<?php


namespace TestPlugin\Handlers;


use TestPlugin\Wordpress\Posttypes;
use WP_Query;
use WP_REST_Request;

class ServersHandler
{
    public static function handleGetTableRowsHtml(WP_REST_Request $request){
        $params = $request->get_params();

        $result = ['params' => $params];

        if(!empty($params['paged'])){
            $serversHandler = new self;

            $query = (new Posttypes())->queryPosts(['paged' => (int)$params['paged']]);

            $result['tableRows'] = $serversHandler->returnTableRows($query);
            $result['pagination'] = $serversHandler->returnPaginationHtml($query);
        }

        wp_send_json($result);
    }

    public function returnTableRows(WP_Query $query){
        ob_start();

        while ($query->have_posts()) : $query->the_post();
            global $post;
            $meta = get_post_meta($post->ID);

            printf(
                '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                !empty($meta['provider'][0]) ? $meta['provider'][0] : '',
                !empty($meta['brand'][0]) ? $meta['brand'][0] : '',
                !empty($meta['location'][0]) ? $meta['location'][0] : '',
                !empty($meta['cpu'][0]) ? $meta['cpu'][0] : '',
                !empty($meta['drive'][0]) ? $meta['drive'][0] : '',
                !empty($meta['price'][0]) ? $meta['price'][0] : ''
            );
        endwhile;

        return ob_get_clean();
    }

    public function returnPaginationHtml(WP_Query $query){
        return paginate_links( array(
            'base' => '/testplugin%_%',
            'format' => '?paged=%#%',
            'current' => max( 1, $query->get('paged') ),
            'total' => $query->max_num_pages
        ) );
    }
}