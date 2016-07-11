/**
 * Application Scripts
 */

$(document).ready(function(){
    if ($('#saved').data('saved') == 1) {
        $('#saved').fadeIn({complete : function(){
            $('#saved').delay(1500).fadeOut();
        }});
    }
    if ($('#removed').data('removed') == 1) {
        $('#removed').fadeIn({complete : function(){
            $('#removed').delay(1500).fadeOut();
        }});
    }
});