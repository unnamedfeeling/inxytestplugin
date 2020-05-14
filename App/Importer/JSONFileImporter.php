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
//            'servers' => self::$serversQuery,
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
        self::$importStatus = [
            'status' => 'importJSON',
            'data' => self::$json
        ];
        if(!empty(self::$json) && self::$json){
            self::$importStatus = [
                'status' => 'importJSON',
                'data' => []
            ];

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
                            'cpu'
                        ]
                    );
                }, ARRAY_FILTER_USE_KEY);
                $title = "$server->provider - $server->location - " . md5(json_encode($fixedParams));

                $posts = [];

                if(!is_array(self::$serversQuery) && self::$serversQuery->have_posts()){
                    $posts = array_filter(self::$serversQuery->posts, function ($key, $post) use ($title){
                        /**
                         * @var WP_Post $post
                         */
                        return $post->post_title === $title;
                    }, ARRAY_FILTER_USE_BOTH);
                }

                if(count($posts) > 0){
                    self::$importStatus['data'][$title] = self::handleServer($server, $title, $posts[0]);
                } else {
                    self::$importStatus['data'][$title] = self::handleNewServer($server, $title);
                }
            }
        }
    }

    private static function handleNewServer(stdClass $serverData, string $title){
        $insertArgs = [
            'post_type'     => 'servers',
            'post_title' => $title,
            'meta_input' => (array)$serverData,
        ];

        return wp_insert_post(wp_slash($insertArgs));
    }

    private static function handleServer(stdClass $serverData, string $title, WP_Post $post){
        $insertArgs = [
            'ID'         => $post->ID,
            'post_type'  => 'servers',
            'post_title' => $title,
            'meta_input' => (array)$serverData,
        ];

        return wp_insert_post(wp_slash($insertArgs));
    }
}