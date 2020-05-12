(function( $ ) {
    $(function() {
        jQuery('input[type=radio][name=shorten_icon_color]').change(function() {
            if (this.value == 'custom') {
                jQuery(".shorten_icon_color_custom_text").show();
                jQuery('.wp-picker-container').show();
            }
            else{
                jQuery(".shorten_icon_color_custom_text").hide();
                jQuery('.wp-picker-container').hide();

            }
        });
        jQuery('input[type=radio][name=shorten_icon_size]').change(function() {
            if (this.value == 'custom') {
                jQuery(".shorten_icon_size_custom_label").show();
            }
            else{
                jQuery(".shorten_icon_size_custom_label").hide();

            }
        });
        jQuery('#shorten_url_box_enabled').change(function() {
            jQuery('#shorten_url_box_enabled:checked').length
            ?jQuery('.url-box-border-options').css('display','block')
            :jQuery('.url-box-border-options').css('display','none')
        });
        

        // Add Color Picker to all inputs that have 'color-field' class
        let button_color_input = $( '.shorten_icon_color_custom' );
        let border_color_input = $( '.shorten_url_box_border_color_custom' );
        $( '#shorten_icon_color_custom_text' ).wpColorPicker();
        $( '#shorten_url_box_border_color_custom_text' ).wpColorPicker({defaultColor : "#ffffff"});
        if($('#shorten_icon_color_custom_text').length){
            function rgb2hex(rgb){
                // if(!rgb)return;
                // rgb = rgb.match(/^rgb((d+),s*(d+),s*(d+))$/);
                rgb = rgb.replace("rgb(","");
                rgb = rgb.replace(")","");
                rgb = rgb.replace(" ","");
                rgb = rgb.replace(" ","");

                rgb = rgb.split(',');

                return "#" +
                 ("0" + parseInt(rgb[0],10).toString(16)).slice(-2) +
                 ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
                 ("0" + parseInt(rgb[2],10).toString(16)).slice(-2);
               }
            (function() {
                var ev = new $.Event('style'),
                    orig = $.fn.css;
                $.fn.css = function() {
                    $(this).trigger(ev);
                    return orig.apply(this, arguments);
                }
            })();
            $('.button-color .wp-color-result').css('background-color',$('#shorten_icon_color_custom_text').val());

            $('.button-color .wp-color-result').bind('style', function(e){
                let hex = rgb2hex(this.style['background-color']);
                // console.log(rgb2hex(this.style['background-color']));
                button_color_input.val(hex);
                button_color_input[0].defaultValue = hex;
                
                
            });
            $('.url-box-border .wp-color-result').bind('style', function(e){
                console.log('changed');
                let hex = rgb2hex(this.style['background-color']);
                // console.log(rgb2hex(this.style['background-color']));
                border_color_input.val(hex);
                border_color_input[0].defaultValue = hex;
                
                
            });
            

        }
         
        if(jQuery('input[type=radio][name=shorten_icon_color]:checked').val()!= "custom"){
            jQuery('.button-color .wp-picker-container').hide();
        } 
        $('.url-box-border .wp-color-result').css("background-color",$( '#shorten_url_box_border_color_custom_text' ).val());

    });
    
})( jQuery );