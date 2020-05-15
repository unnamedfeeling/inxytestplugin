<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

get_header();
do_action('wp_body_open');

?>

<main>
    <div class="container">
        <div class="row testPlugin">
            <div class="col">
                <h1>Test plugin data table</h1>
                <pre class="testPlugin-console js-testPluginConsole"></pre>
                <div class="table-responsive">
                    <?= $data->tableHtml ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
do_action('wp_body_close');
get_footer();