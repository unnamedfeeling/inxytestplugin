<?php


namespace TestPlugin\Importer;


use stdClass;
use WP_Post;
use WP_Query;
use WP_REST_Request;
use JsonSchema\Validator;

class JSONFileImporter
{
    private static $serversQuery = [];
    private static $importStatus = [];
    private static $json         = '';
    private static $checkedPosts = [];

    public static function handleJsonFileContents(WP_REST_Request $request)
    {
        $params = $request->get_params();

        if(!empty($params['url'])){
            self::$json = json_decode(file_get_contents($params['url']));

            $validator = new Validator;
            $validator->validate(self::$json, (object)["type"=>"object"]);

            if ($validator->isValid()){
                self::$serversQuery = self::queryAllServers();

                self::importJSON();
            } else {
                self::$importStatus = [
                    'status' => 'jsonValidationError',
                    'errors' => $validator->getErrors()
                ];
            }

        }

        wp_send_json([
            'params' => $params,
            'status' => self::$importStatus,
        ]);
    }

    private static function queryAllServers()
    {
        $args = [
           'post_type' => 'servers',
           'posts_per_page' => -1,
        ];

        return new WP_Query($args);
    }

    private static function importJSON(){
        if ( true === WP_DEBUG ) @ini_set( 'display_errors', 1 );

        if(!empty(self::$json) && self::$json){
            self::$importStatus['importJSON'] = [
                'status' => 'OK',
                'data' => []
            ];

            $queryPosts =  array_values(self::$serversQuery->posts);

            foreach(self::$json->data as $key => $server){
                $fixedParams = array_filter((array)$server, function ($key) {
                    return in_array(
                        $key,
                        [
                            'provider_name',
                            'provider',
                            'location',
                            'city',
                            'country',
                            'datacenter',
                            'brand_label',
                            'brand',
                            'model',
                            'cpu',
                            'core',
                            'ram',
                            'drive_label',
                            'drive',
                        ]
                    );
                }, ARRAY_FILTER_USE_KEY);
                $title = "$server->provider - $server->location - " . md5(json_encode($fixedParams));

                $posts = [];

                if(!is_array(self::$serversQuery) && self::$serversQuery->have_posts()){
                    $posts = array_values(
                            array_filter($queryPosts, function ($post) use ($title){
                            /**
                             * @var WP_Post $post
                             */
                            return $post->post_title === $title;
                        })
                    );
                }

                if(count($posts) > 0){
                    self::$checkedPosts[] = $posts[0];
//                    self::$importStatus['importJSON']['data'][$title] = self::handleServer($server, $posts[0]);
                    self::handleServer($server, $posts[0]);
                } else {
//                    self::$importStatus['importJSON']['data'][$title] = self::handleNewServer($server, $title);
                    self::handleNewServer($server, $title);
                }
            }

//            self::$importStatus['importJSON']['checkedPosts'] = count(self::$checkedPosts);
//            self::$importStatus['importJSON']['currentSitePosts'] = count($queryPosts);

//            if(count($queryPosts) > 0 && !empty(self::$checkedPosts)){
//                $queriedPostIds = $checkedPostIds = [];
//
//                foreach ($queryPosts as $queryPost) {
//                    $queriedPostIds[] = $queryPost->ID;
//                }
//
//                foreach (self::$checkedPosts as $checkedPost) {
//                    $checkedPostIds[] = $checkedPost->ID;
//                }

//                $orphanPosts = array_diff($queriedPostIds, $checkedPostIds);

//                self::$importStatus['importJSON']['orphanPosts'] = [
//                    'queriedPostIds' => $queriedPostIds,
//                    'checkedPostIds' => $checkedPostIds,
//                    'orphanPosts' => $orphanPosts,
//                ];
//
//                if(!empty($orphanPosts)){
//                    foreach ($orphanPosts as $orphanPost) {
//                        wp_delete_post($orphanPost, true);
//                    }
//                }
//            }
        }
    }

    private static function handleNewServer(stdClass $serverData, string $title){
        $insertArgs = [
            'post_status'   => 'publish',
            'post_type'     => 'servers',
            'post_title'    => $title,
            'meta_input'    => (array)$serverData,
        ];

        return wp_insert_post(wp_slash($insertArgs));
    }

    private static function handleServer(stdClass $serverData, WP_Post $post = null)
    {
        if(!$post) return $post;

        $meta = get_post_meta($post->ID, '', false);

        foreach ((array) $serverData as $key => $serverDatum) {
            if($serverDatum !== $meta[$key][0]) update_post_meta($post->ID, $key, $serverDatum);
        }

        return $post->ID;
    }
}