<div class="wrap">
    <h1>Test plugin settings page</h1>
    <form method="post" action="options.php" class="js-inxytestForm">
        <?php
        // This prints out all hidden setting fields
        settings_fields( 'inxytest_option_group' );
        do_settings_sections( 'inxytest-admin' );
        ?>
    </form>
</div>