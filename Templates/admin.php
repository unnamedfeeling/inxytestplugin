<div class="wrap">
    <h1><?=__('Test plugin settings page', 'inxytest')?></h1>
    <form method="post" action="/wp-json/testplugin/v1/handleJsonFileContents" class="inxytestForm js-inxytestForm">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><?=__('File to import', 'inxytest')?></th>
                    <td>
                        <input name="inxytest_option[jsonfile]" type="text" value="">
                        <input type="button" value="Select or upload file" class="button button-primary js-inxytestUploadBtn">
                    </td>
                </tr>
                <tr>
                    <th scope="row" colspan="2"><?=__('or', 'inxytest')?></th>
                </tr>
                <tr>
                    <th scope="row"><?=__('Input your URL', 'inxytest')?></th>
                    <td>
                        <input name="inxytest_option[jsonfile]" type="text" value="">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" value="<?=__('Upload from this url', 'inxytest')?>" class="button button-primary js-inxytestDirectUploadBtn">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <code class="console js-console hidden"></code>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>