/**
 * Application Scripts
 */

pop = {
    addResource : function(vals) {
        var resource     = $('select[id^="resource_"]:last');
        var resourceId   = 'resource_' + (parseInt(resource.prop("id").match(/\d+/g), 10) + 1);
        var action       = $('select[id^="action_"]:last');
        var actionId     = 'action_' + (parseInt(action.prop("id").match(/\d+/g), 10) + 1);
        var permission   = $('select[id^="permission_"]:last');
        var permissionId = 'permission_' + (parseInt(permission.prop("id").match(/\d+/g), 10) + 1);

        resource.clone(true).prop('id', resourceId).prop('name', resourceId).appendTo($('select[id^="resource_"]:last').parent());
        action.clone(true).prop('id', actionId).prop('name', actionId).appendTo($('select[id^="action_"]:last').parent());
        permission.clone(true).prop('id', permissionId).prop('name', permissionId).appendTo($('select[id^="permission_"]:last').parent());

        if (vals != null) {
            if (vals.resource != null) {
                $('#' + resourceId).val(vals.resource);
            }
            if (vals.action != null) {
                var actId = actionId.substring(actionId.lastIndexOf('_') + 1);
                pop.changeAction(actId, vals.action);
            }
            if (vals.permission != null) {
                if (vals.permission == 'allow') {
                    $('#' + permissionId).val(1);
                } else if (vals.permission == 'deny') {
                    $('#' + permissionId).val(0);
                }
            }
        }
    },
    changeAction : function(id, action) {
        $('#action_' + id + ' > option').remove();
        $('#action_' + id).append('<option value="----">----</option>');

        $.get('/roles/json/' + $('#resource_' + id).val(), function(json){
            if (json.permissions != undefined) {
                for (var i = 0; i < json.permissions.length; i++) {
                    $('#action_' + id).append('<option value="' + json.permissions[i] + '">' + json.permissions[i] + '</option>');
                }
                if (action != null) {
                    $('#action_' + id).val(action);
                }
            }
        });
    }
};

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
                pop.changeAction(id);

            });
        }
        $('#permission-add-link').click(function(){
            pop.addResource();
        });
    }

    if (($('#role-form')[0] != undefined) && ($('#id').val() != 0)) {
        $.get('/roles/json/' + $('#id').val(), function(json){
            if (json.length > 0) {
                $('#resource_1').val(json[0].resource);

                pop.changeAction(1, json[0].action);

                if (json[0].permission == 'allow') {
                    $('#permission_1').val(1);
                } else if (json[0].permission == 'deny') {
                    $('#permission_1').val(0);
                }

                json.shift();
                if (json.length > 0) {
                    for (var i = 0; i < json.length; i++) {
                        console.log(json[i]);
                        pop.addResource(json[i]);
                    }
                }
            }
        });
    }
});