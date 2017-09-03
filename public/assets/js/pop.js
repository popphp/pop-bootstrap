/**
 * pop.js
 */
$(document).ready(function(){
    if ($('#sub-nav-toggle')[0] != undefined) {
        $('#sub-nav-toggle').click(function(){
            if ($('#sub-nav-toggle').prop('class') == 'sub-nav-toggle-right') {
                $('#sidebar-nav').prop('class', 'sidebar-nav');
                $('#main-content').prop('class', 'main-content');
                $('body').prop('class', 'main main-side-nav');
                $('#sub-nav-toggle').prop('class', 'sub-nav-toggle-left');
            } else {
                $('#sidebar-nav').prop('class', 'sidebar-nav sidebar-nav-collapse');
                $('#main-content').prop('class', 'main-content main-content-collapse');
                $('body').prop('class', 'main main-side-nav main-collapse');
                $('#sub-nav-toggle').prop('class', 'sub-nav-toggle-right');
            }
            return false;
        });
    }
});