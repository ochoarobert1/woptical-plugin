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
    var dataCol = jQuery.parseJSON(custom_admin_url.custom_window_height);
    var dataRow = jQuery.parseJSON(custom_admin_url.custom_window_width);

    console.log(dataCol);
    console.log(dataRow);

    var data = [
        ['Jazz', 'Honda', '2019-02-12', '', true, '$ 2.000,00', '#777700'],
        ['Civic', 'Honda', '2018-07-11', '', true, '$ 4.000,01', '#007777'],
    ];
     
    jspreadsheet(document.getElementById('specialPrice1'), {
        data:data,
        columns: [
            {
                type: 'text',
                title:'Car',
                width:90
            },
            {
                type: 'dropdown',
                title:'Make',
                width:120,
                source:[
                    "Alfa Romeo",
                    "Audi",
                    "Bmw",
                    "Chevrolet",
                    "Chrystler",
                    // (...)
                  ]
            },
            {
                type: 'calendar',
                title:'Available',
                width:120
            },
            {
                type: 'image',
                title:'Photo',
                width:120
            },
            {
                type: 'checkbox',
                title:'Stock',
                width:80
            },
            {
                type: 'numeric',
                title:'Price',
                mask:'$ #.##,00',
                width:80,
                decimal:','
            },
            {
                type: 'color',
                width:80,
                render:'square',
            },
         ]
    });

    
});

