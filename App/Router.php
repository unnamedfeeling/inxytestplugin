<?php
declare(strict_types=1);

namespace TestPlugin;

class Router
{
    /**
     * Router constructor.
     */
    public function __construct()
    {
        if (!defined('ABSPATH')) {
            die();
        }
    }

    /**
     * @param array $rules
     */
    public function registerRewriteRoutes(array $rules): void
    {
        add_action('parse_request', function () use ($rules) {
            $this->rewriteRulesRegistrationCallback($rules);
        });
    }

    /**
     * @param array $rules
     */
    public function rewriteRulesRegistrationCallback(array $rules)
    {
        global $wp;

        $rulesPages = [];

        if (!isset($wp->query_vars[ 'pagename' ])) {
            return;
        }

        $pagename =  $wp->query_vars[ 'pagename' ];

        foreach ($rules as $rule) {
            $rulesPages[] = $rule['name'];
        }

        if (!empty($rulesPages) && in_array($pagename, $rulesPages, true)) {
            array_filter($rules, static function (array $rule) {
                $class = new $rule['callback'][0];

                $class->{$rule['callback'][1]}($rule['params']);
            });
        }
    }
}
