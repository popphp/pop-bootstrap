/**
 * Pop Bootstrap Scripts
 */

pop = {
    sessionToInt : null,

    changeTitle : function(value) {
        if ($('#title-span')[0] != undefined) {
            $('#title-span')[0].innerHTML = value;
        }
    },

    addResource  : function(vals) {
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
    },

    timeoutWarning : function(){
        if ($('#session-timeout')[0] == undefined) {
            $('body').append(
                '<div id="session-timeout">' +
                '<h4 id="countdown">30</h4>Your session is about to expire.<br /><span>' +
                '<a href="#" onclick="pop.sessionContinue(); return false;">Continue</a>? ' +
                '[No, <a href="/logout">Logout</a>]</span>' +
                '</div>'
            );

            $('#session-timeout').fadeIn();

            pop.sessionToInt = setInterval(function(){
                var sec = parseInt($('#countdown')[0].innerHTML);
                if (sec > 0) {
                    var newSec = sec - 1;
                    $('#countdown')[0].innerHTML = newSec;
                } else {
                    window.location = '/logout?expired=1';
                }
            }, 1000);
        }
    },

    sessionContinue : function() {
        $('#session-timeout').fadeOut({complete : function(){
            $('#session-timeout').remove();
            clearInterval(pop.sessionToInt);

            var timeout = pop.cookie.load('pop_session_timeout');
            var warning = pop.cookie.load('pop_timeout_warning');
            if ((timeout != '') && (warning != '')) {
                pop.sessionToInt = setInterval(pop.timeoutWarning, (timeout - warning) * 1000);
            }
        }});
    },

    browser : function() {
        var browser = {
            "ua"      : navigator.userAgent,
            "name"    : '',
            "version" : '',
            "device"  : '',
            "os"      : ''
        };

        var os  = browser.ua.toLowerCase().match(/(windows|macintosh|linux|freebsd|openbsd|netbsd|sunos)/i);
        var brw = browser.ua.toLowerCase().match(/(chrome|firefox|msie|trident|edge|konqueror|navigator|opera|safari)/i);
        var dev = browser.ua.toLowerCase().match(/(android|blackberry|windows ce|windows phone|opera mini|pre|presto|ipod|iphone|ipad|nokia|symbian|palm|treo|hiptop|avantgo|plucker|xiino|blazer|elaine|teleca|up.browser|up.link|mmp|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|pda|psp)/i);

        if ((os != null) && (os[0] != undefined)) {
            browser.os = os[0].charAt(0).toUpperCase() + os[0].substring(1).replace('bsd', 'BSD').replace('os', 'OS');
        }

        if ((brw != null) && (brw[0] != undefined)) {
            if ((brw[0] == 'msie') || (brw[0] == 'trident') || (brw[0] == 'edge')) {
                browser.name = 'MSIE';
            } else {
                browser.name = brw[0].charAt(0).toUpperCase() + brw[0].substring(1);
            }
        }

        switch (browser.name) {
            case 'Chrome':
                browser.version = browser.ua.substring(browser.ua.indexOf('Chrome/') + 7);
                browser.version = browser.version.substring(0, browser.version.indexOf(' '));
                break;
            case 'Firefox':
                browser.version = browser.ua.substring(browser.ua.indexOf('Firefox/') + 8);
                break;
            case 'MSIE':
                if (browser.ua.indexOf('Edge') != -1) {
                    browser.version = browser.ua.substring(browser.ua.indexOf('Edge') + 4);
                }
                if (browser.ua.indexOf('MSIE ') != -1) {
                    browser.version = browser.ua.substring(browser.ua.indexOf('MSIE ') + 5);
                    browser.version = browser.version.substring(0, browser.version.indexOf(';'));
                } else {
                    browser.version = browser.ua.substring(browser.ua.indexOf('rv:') + 3);
                    browser.version = browser.version.substring(0, browser.version.indexOf(')'));
                }
                break;
            case 'Safari':
                browser.version = browser.ua.substring(browser.ua.indexOf('Version/') + 8);
                browser.version = browser.version.substring(0, browser.version.indexOf(' '));
                break;
        }

        if ((dev != null) && (dev[0] != undefined)) {
            browser.device = dev[0];
        }

        return browser;
    },

    cookie : {
        save : function(name, value, options) {
            if (typeof value != 'string') {
                if (typeof value == 'number') {
                    value = value.toString();
                } else {
                    value = JSON.stringify(value);
                }
            }
            var cookie = name + '=' + encodeURI(value);

            // Parse options
            if (options != undefined) {
                cookie += (options.domain != undefined) ? ';domain=' + options.domain : '';
                cookie += (options.path != undefined) ? ';path=' + options.path : '';
                if (options.expire != undefined) {
                    var expdate = new Date();
                    expdate.setDate(expdate.getDate() + options.expire);
                    cookie += ';expires=' + expdate.toGMTString();
                }
            }

            // Set the cookie.
            document.cookie = cookie;

            return this;
        },
        load : function(name) {
            var value = '';

            // If the cookie is set, parse the value.
            if (document.cookie.length > 0) {
                if (name == null) {
                    value = {};
                    var ary = document.cookie.split(';');
                    for (var i = 0; i < ary.length; i++) {
                        var a = ary[i].trim().split('=');
                        var n = a[0];
                        var v = decodeURI(a[1]);
                        if ((v.indexOf('{') != -1) || (v.indexOf('[') != -1)) {
                            v = JSON.parse(decodeURIComponent(v));
                        }
                        value[n] = v;
                    }
                } else {
                    var start = document.cookie.indexOf(name + '=');

                    if (start != -1) {
                        start = start + name.length + 1;
                        var end = document.cookie.indexOf(';', start);
                        if (end == -1) {
                            end = document.cookie.length;
                        }

                        value = decodeURI(document.cookie.substring(start, end));
                        if ((value.indexOf('{') != -1) || (value.indexOf('[') != -1)) {
                            value = JSON.parse(decodeURIComponent(value));
                        }
                    }
                }
            }

            return value;
        },
        remove : function(name) {
            if (name == null) {
                var c = this.load();
                for (var n in c) {
                    this.save(n, '', {"expire" : -1});
                }
            } else {
                this.save(name, '', {"expire" : -1});
            }

            return this
        }
    }
};

$(document).ready(function(){
    if ($('#saved').data('saved') == 1) {
        $('#saved').fadeIn({complete : function(){
            $('#saved').delay(2000).fadeOut();
        }});
    }
    if ($('#removed').data('removed') == 1) {
        $('#removed').fadeIn({complete : function(){
            $('#removed').delay(2000).fadeOut();
        }});
    }
    if ($('#expired').data('expired') == 1) {
        $('#expired').fadeIn({complete : function(){
            $('#expired').delay(3000).fadeOut();
        }});
    }
    if ($('#failed').data('failed') == 1) {
        $('#failed').fadeIn({complete : function(){
            $('#failed').delay(3000).fadeOut();
        }});
    }

    if ($('#browser-detect')[0] != undefined) {
        var browser = pop.browser();
        if ((browser.name != '') && (browser.version != '')) {
            $('#browser-detect')[0].innerHTML = browser.name + ' (' + browser.version + ')';
        }
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

    if ($('#roles-form')[0] != undefined) {
        $('#roles-form').submit(function(){
            return confirm('This action cannot be undone. Are you sure?');
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

    var timeout = pop.cookie.load('pop_session_timeout');
    var warning = pop.cookie.load('pop_timeout_warning');
    if (timeout != '') {
        if (warning != '') {
            pop.sessionToInt = setInterval(pop.timeoutWarning, (timeout - warning) * 1000);
        } else {
            setTimeout(function () {
                window.location = '/logout?expired=1';
            }, timeout * 1000);
        }
    }

    $('[data-toggle="offcanvas"]').click(function () {
        if ($('#side-nav-toggle').css('left') == '-8px') {
            $("#side-nav-toggle").animate({left: '-20px'}, {duration: 75});
        } else {
            $("#side-nav-toggle").animate({left: '-8px'}, {duration: 75});
        }
        $('.row-offcanvas').toggleClass('active');
        return false;
    });
});