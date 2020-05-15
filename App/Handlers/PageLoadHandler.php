<?php
declare(strict_types=1);

namespace TestPlugin\Handlers;

use TestPlugin\Loaders\TemplateLoader;
use TestPlugin\Wordpress\Posttypes;
use WP_Query;

class PageLoadHandler
{
    /**
     * PageLoadHandler constructor.
     */
    public function __construct()
    {
        if (!defined('ABSPATH')) {
            die();
        }
    }

    public function handlePageLoad(): void
    {
        add_filter('template_include', [$this, 'filterContent'], 10);
        add_filter('pre_handle_404', [$this, 'override404'], 10, 2);
    }

    public function filterContent(): void
    {
        $tplLoader = new TemplateLoader();

        $table = ['tableHtml' => $this->generateTableHtml()];

        $tplLoader->set_template_data($table, 'data');
        $tplLoader->get_template_part('main');
    }

    /**
     * @param bool $is404
     * @param WP_Query $wpQuery
     * @return bool
     */
    public function override404(bool $is404, WP_Query $wpQuery): bool
    {
        global $wp_query;

        $wp_query = new WP_Query([]);
        status_header(200);

        return true;
    }

    /**
     * @return string
     */
    public function generateTableHtml(): string
    {
        $postTypes = new Posttypes();
        $args = [];

        if(!empty($_GET) && !empty($_GET['paged'])) $args['paged'] = (int)$_GET['paged'];

        $query = $postTypes->queryPosts($args);
        
        if ($query->have_posts()) {
            $html = '<table class="table table-striped testPlugin-table js-testPluginTable">
            <thead>
                <tr>
                    <th>Provider</th>
                    <th>Brand</th>
                    <th>Location</th>
                    <th>CPU</th>
                    <th>Drive</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>%s</tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="6">%s</td>
                </tr>
            </tfoot>
            </table>';
            
            ob_start();

            echo (new ServersHandler())->returnTableRows($query);

            $pagination = (new ServersHandler())->returnPaginationHtml($query);

            return sprintf($html, ob_get_clean(), $pagination); // WPCS: XSS OK.
        }

        return '';
    }
}
