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
    if ($('#checkAll')[0] != undefined) {
        $('#checkAll').click(function(){
            var checkName  = $('#checkAll').data('name');
            var checkBoxes = $("input[name='" + checkName + "[]']");
            for (var i = 0; i < checkBoxes.length; i++) {
                if ($(this).prop('checked')) {
                    $(checkBoxes[i]).prop('checked', true);
                } else {
                    $(checkBoxes[i]).prop('checked', false);
                }
            }
        });

    }
    if (($('#role_id')[0] != undefined) && ($('#role_id').data('user') == 'add')) {
        $('#role_id').change(function(){
            if ($('#role_id').val() != 0) {
                window.location.href = '/users/add/' + $('#role_id').val();
            }
        });
    }
    if ($('#users-form')[0] != undefined) {
        $('#users-form').submit(function(){
            if ($('#user_process_action').val() == '-1') {
                return confirm('This action cannot be undone. Are you sure?');
            } else {
                return true;
            }
        });
    }
    if ($('#permission-add-link')[0] != undefined) {
        var resources = $('select[id^="resource_"]');
        for (var i = 0; i < resources.length; i++) {
            $(resources[i]).change(function(){
                var id = $(this).prop('id');
                id = id.substring(id.lastIndexOf('_') + 1);

                var opts = $('#action_' + id + ' > option').remove();

                $('#action_' + id).append('<option value="----">----</option>');

                $.get('/roles/json/' + $('#resource_' + id).val(), function(json){
                    if (json.permissions != undefined) {
                        for (var i = 0; i < json.permissions.length; i++) {
                            $('#action_' + id).append('<option value="' + json.permissions[i] + '">' + json.permissions[i] + '</option>');
                        }
                    }
                });
            });
        }
        $('#permission-add-link').click(function(){
            var resource     = $('select[id^="resource_"]:last');
            var resourceId   = 'resource_' + (parseInt(resource.prop("id").match(/\d+/g), 10) + 1);
            var action       = $('select[id^="action_"]:last');
            var actionId     = 'action_' + (parseInt(action.prop("id").match(/\d+/g), 10) + 1);
            var permission   = $('select[id^="permission_"]:last');
            var permissionId = 'permission_' + (parseInt(permission.prop("id").match(/\d+/g), 10) + 1);

            resource.clone(true).prop('id', resourceId).prop('name', resourceId).appendTo($('select[id^="resource_"]:last').parent());
            action.clone(true).prop('id', actionId).prop('name', actionId).appendTo($('select[id^="action_"]:last').parent());
            permission.clone(true).prop('id', permissionId).prop('name', permissionId).appendTo($('select[id^="permission_"]:last').parent());
        });
    }
});