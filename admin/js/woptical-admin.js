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


    if (document.getElementById('specialPrice1')) {
        var data = []
        var currentColumns = [];
        var dataActual = custom_admin_url.custom_pricing_table;
        //var dataActual = [];
        var dataCol = jQuery.parseJSON(custom_admin_url.custom_spheres_values);
        var dataRow = jQuery.parseJSON(custom_admin_url.custom_crystal_values);

        dataActual = JSON.parse(dataActual.replace(/\\/g, ""));

        var columnsVar = {
            type: 'text',
            title: 'Esfera/Cristales',
            width: 100
        }
        currentColumns.push(columnsVar);



        for (let index = 0; index < dataCol.length; index++) {
            columnsVar = {
                type: 'text',
                title: dataCol[index],
                width: 50
            }
            currentColumns.push(columnsVar);
        }
        if (dataActual.length == 0) {
            for (let index = 0; index < dataRow.length; index++) {
                data[index] = [dataRow[index]];
            }

        } else {
            var data = dataActual;
        }

        jspreadsheet(document.getElementById('specialPrice1'), {
            data: data,
            freezeColumns: 2,
            columns: currentColumns,
            minDimensions: [dataCol.length, dataRow.length],
        });

        jQuery('#submitTable').on('click', function(e) {
            e.preventDefault();

            var data = document.getElementById('specialPrice1').jspreadsheet.getData();

            jQuery.ajax({
                method: 'POST',
                url: ajaxurl,
                data: {
                    action: 'custom_pricing_table_save_data',
                    info: JSON.stringify(data)
                },
                beforeSend: function() {
                    jQuery('#responseTable').html('<div class="loader-ring"><div></div><div></div><div></div><div></div></div>');
                },
                success: function(response) {
                    jQuery('#responseTable').html(response.data);
                },
                error: function(request, status, error) {
                    console.log(error);
                }
            });
        });

    }
});