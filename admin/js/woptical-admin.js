jQuery(document).ready(function($) {
    function taxonomy_media_upload(button_class) {
        var custom_media = true,
            original_attachment = wp.media.editor.send.attachment;
        $('body').on('click', button_class, function(e) {
            var button_id = '#' + $(this).attr('id');
            var send_attachment = wp.media.editor.send.attachment;
            var button = $(button_id);
            custom_media = true;
            wp.media.editor.send.attachment = function(props, attachment) {
                if (custom_media) {
                    $('#image_id').val(attachment.id);
                    $('#image_wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                    $('#image_wrapper .custom_media_image').attr('src', attachment.url).css('display', 'block');
                } else {
                    return original_attachment.apply(button_id, [props, attachment]);
                }
            }
            wp.media.editor.open(button);
            return false;
        });
    }
    taxonomy_media_upload('.taxonomy_media_button.button');
    $('body').on('click', '.taxonomy_media_remove', function() {
        $('#image_id').val('');
        $('#image_wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
    });

    $(document).ajaxComplete(function(event, xhr, settings) {
        var queryStringArr = settings.data.split('&');
        if ($.inArray('action=add-tag', queryStringArr) !== -1) {
            var xml = xhr.responseXML;
            $response = $(xml).find('term_id').text();
            if ($response != "") {
                $('#image_wrapper').html('');
            }
        }
    });

	var dataActual = [];
    var dataActual2 = [];
    var dataCol = jQuery.parseJSON(custom_admin_url.custom_window_width);
    var dataRow = jQuery.parseJSON(custom_admin_url.custom_window_height);

    /* TABLE 2 */
    var dataActual = custom_admin_url.custom_table_price_1;
    dataActual = dataActual.replace(/\\/g, "");

    if (dataActual != '') {
        jQuery("#specialPrice1").handsontable({
            data: JSON.parse(dataActual),
            rowHeaders: dataRow,
            colHeaders: dataCol,
            contextMenu: false,
            licenseKey: 'non-commercial-and-evaluation'
        });
    } else {
        getTable(dataRow, dataCol).then((data) => {
                dataActual = data;
                jQuery("#specialPrice1").handsontable({
                    data: dataActual,
                    rowHeaders: dataRow,
                    colHeaders: dataCol,
                    contextMenu: false,
                    licenseKey: 'non-commercial-and-evaluation'
                });
            })
            .catch((error) => {
                console.log(error)
            });
    }
    /* TABLE 2 */
    var dataActual2 = custom_admin_url.custom_table_price_2;
    dataActual2 = dataActual2.replace(/\\/g, "");

    if (dataActual2 != '') {
        jQuery("#specialPrice2").handsontable({
            data: JSON.parse(dataActual2),
            rowHeaders: dataRow,
            colHeaders: dataCol,
            contextMenu: false,
            licenseKey: 'non-commercial-and-evaluation'
        });
    } else {
        getTable(dataRow, dataCol).then((data) => {
                dataActual2 = data;
                jQuery("#specialPrice2").handsontable({
                    data: dataActual2,
                    rowHeaders: dataRow,
                    colHeaders: dataCol,
                    contextMenu: false,
                    licenseKey: 'non-commercial-and-evaluation'
                });
            })
            .catch((error) => {
                console.log(error)
            });
    }
});