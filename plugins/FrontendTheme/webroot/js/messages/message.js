var users = [];
var getMentionUsersUrl = $('.information-ajax').attr("data-mention-users-url");
var getMentionSymbolsUrl = $('.information-ajax').attr("data-mention-companies-url");
var getSymbolUrl = $('.information-ajax').attr("data-symbol-url");
var getForexUrl = $('.information-ajax').attr("data-forex-url");
var getUserSymbolUrl = $('.information-ajax').attr("data-user-symbol-url");
var getImgUrl = $('.feed-container').attr("data-url-img");
var getGradeUrl = $('.feed-container').attr("data-grade-url");
var getCommentsUrl = $('.feed-container').attr("data-post-comments-url");
var getModalUrl = $('.feed-container').attr("data-modal-url");
var getScreenshotUrl = $('.feed-container').attr("data-screenshot");
var getDeleteUrl = $('.feed-container').attr("data-delete-post");
var getRefreshUrl = $('.feed-container').attr("data-refresh");
var getRefreshCommentUrl = $('.feed-container').attr("data-refresh-comment");
var getCommentCountUrl = $('.feed-container').attr("data-count-comment");
var getReadNotificationUrl = $('.information-ajax').attr("data-read-notification-url");
var getFollowUrl = $('.feed-container').attr("data-follow-url");

$.get(getMentionSymbolsUrl, function (response) {
    users = response.mentionSymbols;
    $("#message").mention({
        delimiter: '!',
        sensitive: true,
        typeaheadOpts: {
            items: 15
        },
        queryBy: ['name', 'username'],
        users: users
    });
}, "json");

$("body").delegate('.comments_post', 'click', function () {

    var message_id = $(this).attr('data-message-id');
    getMessageComments(message_id);
    $('#message-id-' + message_id).html('');
});

function commentCount() {

    var _comment_count = jQuery('.comments_post_count');
    var messages_data = [];
    var i = 0;
    _comment_count.each(function () {
        var _t = jQuery(this);
        messages_data[i] = getCommentCount(_t);
        i++
    });
    $.ajax({
        type: "post",
        url: getCommentCountUrl,
        data: {
            messages_data: messages_data
        },
        success: function (response) {
            var array = response.data;
            $.each(array, function (index, value) {
                $(".comments_post_count[data-message-id='" + value.id + "']").text(value.count);
            });
            setTimeout(commentCount, 5000);
        }
    });
}

function getMessageComments(message_id) {
    $.ajax({
        type: "post",
        url: getCommentsUrl,
        data: {
            message_id: message_id
        },
        success: function (response) {
            var output = '';
            var array = response.data;
            if (!jQuery.isEmptyObject(array)) {

                $.each(array, function (index, value) {
                    var template = $('#user-message-comment').html();
                    var message_content = replaceMessage(value.data.Message.message);
                    output += Mustache.render(template, {
                        date: value.data.Message.message_date,
                        fullname: value.data.User.username,
                        experience: value.data.User.experience,
                        investment_style: value.data.User.investment_style,
                        avatarPath: value.avatar_path,
                        message_id: value.data.Message.message_id,
                        message: message_content
                    });
                });

                $('#message-id-' + message_id).html('');
                $(output).prependTo('#posts #message-id-' + message_id).hide().fadeIn('slow');
            }

        },
        error: function (e) {

        }
    });
}

function newComment() {
    var data_what_a_page = $('.feed-container').attr("data-what-a-page");
    var data_page = $('.feed-container').attr("data-what-a-page-data");
    var messages_data = [];
    var _container_message = jQuery('.messageli_comment');
    var i = 0;
    _container_message.each(function () {

        var _t = jQuery(this);
        messages_data[i] = getMessagesInfo(_t);
        i++;
    });
    $.ajax({
        type: "post",
        url: getRefreshCommentUrl,
        data: {
            page: data_what_a_page,
            data: data_page,
            messages_data: messages_data
        },
        success: function (response) {
            var array_remove = response.remove;
            if (!jQuery.isEmptyObject(array_remove)) {
                for (var r = 0; r < response.remove.length; r++) {
                    $('#message_' + response.remove[r]['id']).next('hr').remove();
                    $('#message_' + response.remove[r]['id']).remove();
                }
            }

            var output = '';
            var array = response.data;
            if (!jQuery.isEmptyObject(array)) {
                $.each(array, function (index, value) {
                    output = addDataInPartialComment(array, index);
                    $('#message_' + value.data.Message.message_id).replaceWith(output);
                    $('#message_' + value.data.Message.message_id).next('hr').remove();
                });
            }

            var array_new = response.new;
            var output_new = '';
            if (!jQuery.isEmptyObject(array_new)) {
                $.each(array_new, function (index, value) {
                    output_new = addDataInPartialComment(array_new, index);
                    $(output_new).appendTo('#message-id-' + value.data.Message.message_comment_id).fadeIn('slow');
                });

            }
            setTimeout(newComment, 5000);
        },
        error: function (e) {

        }
    });
}

function newMessage() {
    var data_what_a_page = $('.feed-container').attr("data-what-a-page");
    var data_page = $('.feed-container').attr("data-what-a-page-data");
    var messages_data = [];
    var _container_message = jQuery('.messageli');
    var i = 0;
    _container_message.each(function () {

        var _t = jQuery(this);
        messages_data[i] = getMessagesInfo(_t);
        i++;
    });
    $.ajax({
        type: "post",
        url: getRefreshUrl,
        data: {
            page: data_what_a_page,
            data: data_page,
            messages_data: messages_data
        },
        success: function (response) {
            var array_remove = response.remove;
            if (!jQuery.isEmptyObject(array_remove)) {
                for (var r = 0; r < response.remove.length; r++) {
                    $('#message_' + response.remove[r]['id']).next('.toggle').remove();
                    $('#message_' + response.remove[r]['id']).next('hr').remove();
                    $('#message_' + response.remove[r]['id']).remove();
                }
            }

            var output = '';
            var array = response.data;
            if (!jQuery.isEmptyObject(array)) {
                for (var index = 0; index < array.length; index++) {
                    output = addDataInPartial(array, index);
                    $('#message_' + array[index].data.Message.message_id).next('hr').remove();
                }
            }

            var array_new = response.new;
            var output_new = '';
            if (!jQuery.isEmptyObject(array_new)) {
                for (var index = 0; index < array_new.length; index++) {
                    var template_toggle = $('#user-message-comment-toggle').html();
                    output_new += addDataInPartial(array_new, index);
                    output_new += Mustache.render(template_toggle, {
                        date: array_new[index].data.Message.message_date,
                        fullname: array_new[index].data.User.username,
                        avatarPath: getImgUrl,
                        message_id: array_new[index].data.Message.message_id
                    });

                    var attachment = array_new[index].data.Message.attachment;

                    var hostname = $(location).attr('hostname');

                    var ext = attachment.split('.').pop();
                    var icon = '';

                    if (ext == 'xls' || ext == 'xlsx') {
                        icon = "fa-file-excel-o";
                    } else if (ext == 'pdf') {
                        icon = "fa-file-pdf-o";
                    } else if (ext == 'docs' || ext == 'docx') {
                        icon = "fa-file-word-o";
                    }

                    if (ext == 'jpg' || ext == 'png' || ext == 'jpeg') {
                        var atteched_document = '<img class="img-fluid" src=' + attachment + '>';
                    } else {
                        var atteched_document = '<a href="' + attachment + '" download> <i class="fa ' + icon + '"></i> Download Attached ' + ext + ' File</a>';
                    }

                    var newImgLi = '<li id="upload_' + array_new[index].data.Message.message_id + '"><div class="row"><div class="col-lg-2 col-md-2 col-sm-12 mt-5 mrp-1"><div class="thumbnail float-left avatar mr-20"><img src=' + getImgUrl + ' width="60" height="60" class="user_avatar" alt=""></div></div><div class="col-lg-10 col-md-10 col-sm-12 mb-20m margin_left_0">' + atteched_document + '</div></div></li><hr>"';

                    $(output_new).prependTo('#first-stream-box').hide().fadeIn('slow');
                    $(newImgLi).prependTo('#second-stream-box').hide().fadeIn('slow');
                }
            }

            setTimeout(newMessage, 3000);
        },
        error: function (e) {

        }
    });
}

function addDataInPartialComment(array, index) {
    var message_content = replaceMessage(array[index].data.Message.message);
    var template = $('#user-message-comment').html();
    output = Mustache.render(template, {
        date: array[index].data.Message.message_date,
        fullname: array[index].data.User.username,
        experience: array[index].data.User.experience,
        investment_style: array[index].data.User.investment_style,
        avatarPath: array[index].avatar_path,
        message_id: array[index].data.Message.message_id,
        message: message_content
    });
    return output;
}

function addDataInPartial(array, index) {
    var output = '';
    if (typeof array[index]['parent'] == 'undefined') {
        var template = $('#user-send-message').html();
        var message_content = replaceMessage(array[index].data.Message.message);
        var url = urlify(array[index].data.Message.message);
        var img_data = null;
        var status = 'none';
        if (array[index].img_page) {
            status = 'block';
        }

        output += Mustache.render(template, {
            date: array[index].data.Message.message_date,
            fullname: array[index].data.User.username,
            experience: array[index].data.User.experience,
            investment_style: array[index].data.User.investment_style,
            avatarPath: array[index].avatar_path,
            stars: getStarsHtml(array[index].data.Message.rating),
            rating: array[index].data.Message.rating,
            message_id: array[index].data.Message.message_id,
            message: message_content,
            delete_status: array[index].delete_status,
            img_data: array[index].img_page,
            url_page: array[index].url_page,
            status: status
        });
    } else {
        var template = $('#user-share-message').html();
        var message_content = replaceMessage(array[index].data.Message.message);
        var message_content_parent = replaceMessage(array[index].parent.Message.message);
        var img_data = null;
        var status = 'none';
        if (array[index].img_page) {
            var status = 'block';
            img_data = array[index].img_page;
        }
        var img_data_parent = null;
        var status_parent = 'none';
        if (array[index].img_data_parent) {
            var status_parent = 'block';
            img_data_parent = array[index].img_data_parent;
        }

        output += Mustache.render(template, {
            date: array[index].data.Message.message_date,
            fullname: array[index].data.User.username,
            experience: array[index].data.User.experience,
            investment_style: array[index].data.User.investment_style,
            avatarPath: array[index].avatar_path,
            message: message_content,
            message_id: array[index].data.Message.message_id,
            delete_status: array[index].delete_status,
            status: status,
            stars: getStarsHtml(array[index].data.Message.rating),
            rating: array[index].data.Message.rating,
            img_data: img_data,
            url_page: array[index].url_page,
            date_parent: array[index].parent.Message.message_date,
            fullname_parent: array[index].parent.User.username,
            experience_parent: array[index].parent.User.experience,
            investment_style_parent: array[index].parent.User.investment_style,
            avatarPath_parent: array[index].parent_avatar,
            message_parent_id: array[index].parent.Message.message_id,
            message_parent: message_content_parent,
            status_parent: status_parent,
            stars_parent: getStarsHtml(array[index].parent.Message.rating),
            parent_rating: array[index].parent.Message.rating,
            img_data_parent: img_data_parent,
            url_data_parent: array[index].url_data_parent
        });
    }
    return output;
}

function getMessagesInfo(_t) {
    var data = {};
    data = {
        id: _t.attr('data-id'),
        avatar: _t.find('.avatar img').first().attr('src').trim(),
        date: _t.find('.date').first().text().trim(),
        rate: _t.find('.rate').first().text().trim(),
        username: _t.find('.message-username b').first().text().trim(),
        experience: _t.find('.message-username .experience').first().text().trim(),
        investment: _t.find('.message-username .investment').first().text().trim(),
        message: _t.find('.message-body').first().text().trim()
    }
    return data;
}

function getCommentCount(_t) {
    var data = {};
    data = {
        id: _t.attr('data-message-id'),
        count: _t.text().trim(),
    }
    return data;
}

function changeMessages() {
    $('.message-content').each(function (index) {
        var message_content = replaceMessage($(this).find('.message-body').text());
        $(this).find('.message-body').html(message_content);
    });
}

$("body").delegate('.formMessage', 'submit', function (e) {

// Stop form from submitting normally
    e.preventDefault();
    var THIS = $(this).find('.message-box');
    $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (response) {
            var output = '';
            var array = response.data;
            if (!jQuery.isEmptyObject(array)) {
                var template = $('#user-message-comment').html();
                var message_content = replaceMessage(array.Message.message);
                output += Mustache.render(template, {
                    date: array.Message.message_date,
                    fullname: array.User.username,
                    experience: array.User.experience,
                    investment_style: array.User.investment_style,
                    avatarPath: getImgUrl,
                    message_id: array.Message.message_id,
                    message: message_content
                });
                THIS.val('');
                $('#message_' + array.Message.message_id).next('hr').remove();
                $('#message_' + array.Message.message_id).remove();
                $(output).appendTo('#message-id-' + array.Message.message_comment_id).fadeIn('slow');
                var countSpan = $(".comments_post_count[data-message-id='" + array.Message.message_comment_id + "']");
                var count = parseInt(countSpan.text());
                countSpan.text(count + 1);

            }
        },
        error: function (e) {
        }
    });
});

$('#formMessage').submit(function (e) {
    var len = $('.message-box').val().length;
    var length = pageScreenshot($('.message-box').val());
// Stop form from submitting normally
    e.preventDefault();
    var file = e.currentTarget.attach.files[0];
    var data = new FormData();
    data.append('file', file);
    if (file != undefined) {
        $.ajax({
            type: "POST",
            url: $(this).attr('action') + '_upload_img',
            data: data,
            contentType: false,
            processData: false,
            success: function (response) {

                if (len - length <= 200) {

                    $.ajax({
                        type: "POST",
                        url: $('#formMessage').attr('action'),
                        data: $('#formMessage').serialize() + "&attachment=" + response,
                        success: function (response) {
                            document.getElementById("formMessage").reset();
                            $("#mydiv_iframe").hide();
                        },
                        error: function (e) {
                            $("#mydiv_iframe").hide();
                            if (e.status == 401) {
                                var template = $('#validate-user-logged').html();
                                var output = Mustache.render(template);
                                $('.message_alert').html(output);
                            } else {

                            }
                        }
                    });
                }
            }
        });
    } else {
        if (len - length <= 200) {

            $.ajax({
                type: "POST",
                url: $('#formMessage').attr('action'),
                data: $('#formMessage').serialize(),
                success: function (response) {
                    document.getElementById("formMessage").reset();
                    $("#mydiv_iframe").hide();
                },
                error: function (e) {
                    $("#mydiv_iframe").hide();
                    if (e.status == 401) {
                        var template = $('#validate-user-logged').html();
                        var output = Mustache.render(template);
                        $('.message_alert').html(output);
                    } else {

                    }
                }
            });
        }
    }

});
///modal start ///

$("body").delegate('.modal_share', 'click', function () {
    var message_id = $(this).data('id');
    var user_avatar = $(this).parents('.message').find('.user_avatar').attr('src');
    $('.parent_img_url').val(user_avatar);
    $('.user_message').html('');
    $.ajax({
        type: "GET",
        url: getModalUrl,
        data: {
            'message_id': message_id
        },
        success: function (response) {

            var template = $('#user-send-messag-modal').html();
            var message_content = replaceMessage(response.data.Message.message);
            var output = Mustache.render(template, {
                date: response.data.Message.message_date,
                fullname: response.data.User.username,
                experience: response.data.User.experience,
                investment_style: response.data.User.investment_style,
                avatarPath: user_avatar,
                message_id: response.data.Message.message_id,
                message: message_content
            });
            $('#modal_share').modal('show');
            $('.parent_message_id').val(message_id);
            $('.user_message').html(output);
//            $(".livepreview").livePreview();
        },
        error: function (e) {
            if (e.status == 401) {
                var template = $('#validate-user-logged-share').html();
                var output = Mustache.render(template);
                $('.message_alert_share').html('');
                $('.message_alert_share').html(output);
            } else {

            }
        }
    });
});

$("body").delegate('#formMessageModal', 'submit', function (e) {
// Stop form from submitting normally
    e.preventDefault();
    var len = $('.message-box-modal').val().length;
    var length = pageScreenshot($('.message-box-modal').val());
    if (len - length <= 200) {
        $('#modal_share').modal('hide');
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (response) {
                var template = $('#user-share-message').html();
                var template_toggle = $('#user-message-comment-toggle').html();
                var message_content = replaceMessage(response.data.Message.message);
                var message_content_parent = replaceMessage(response.parent.Message.message);
                var img_data = null;
                var status = 'none';
                if (response.img_page) {
                    var status = 'block';
                    img_data = response.img_page;
                }
                var img_data_parent = null;
                var status_parent = 'none';
                if (response.img_data_parent) {
                    var status_parent = 'block';
                    img_data_parent = response.img_data_parent;
                }
                var output = '';
                output += Mustache.render(template, {
                    date: response.data.Message.message_date,
                    fullname: response.data.User.username,
                    experience: response.data.User.experience,
                    investment_style: response.data.User.investment_style,
                    avatarPath: getImgUrl,
                    message: message_content,
                    message_id: response.data.Message.message_id,
                    delete_status: '',
                    status: status,
                    stars: getStarsHtml(response.data.Message.rating),
                    rating: response.data.Message.rating,
                    img_data: img_data,
                    url_page: response.url_page,
                    date_parent: response.parent.Message.message_date,
                    fullname_parent: response.parent.User.username,
                    experience_parent: response.parent.User.experience,
                    investment_style_parent: response.parent.User.investment_style,
                    avatarPath_parent: response.parent_avatar,
                    message_parent_id: response.parent.Message.message_id,
                    message_parent: message_content_parent,
                    status_parent: status_parent,
                    stars_parent: getStarsHtml(response.parent.Message.rating),
                    parent_rating: response.parent.Message.rating,
                    img_data_parent: img_data_parent,
                    url_data_parent: response.url_data_parent,
                });
                output += Mustache.render(template_toggle, {
                    date: response.data.Message.message_date,
                    fullname: response.data.User.username,
                    avatarPath: getImgUrl,
                    message_id: response.data.Message.message_id
                });
                $(output).prependTo('#first-stream-box').hide().fadeIn('slow');
                document.getElementById("formMessageModal").reset();
            },
            error: function (e) {
                if (e.status == 401) {
                    var template = $('#validate-user-logged').html();
                    var output = Mustache.render(template);
                    $('.message_alert').html(output);
                } else {

                }
            }
        });
    }

});
///modal end///

$("body").delegate('.delete_share', 'click', function () {
    $('#confirm-delete-post').modal('show');
    $('.delete-post').attr('data-id', $(this).data('id'));
});
$(".modal").delegate('.delete-post', 'click', function () {
    $('#confirm-delete-post').modal('hide');
    var id = $(this).attr('data-id');

    $.ajax({
        type: "POST",
        url: getDeleteUrl,
        data: {
            'message_id': id
        },
        success: function (response) {

            $('#upload_' + id).hide();
            $(".delete_share[data-id='" + id + "']").parents('li.messageli').next('.toggle').remove();
            $(".delete_share[data-id='" + id + "']").parents('li.messageli').next('hr').remove();
            $(".delete_share[data-id='" + id + "']").parents('li.messageli').remove();

        },
        error: function (e) {
            var template = $('#validate-user-logged-delete').html();
            var output = Mustache.render(template);
            $('.message_alert').html('');
            $('.message_alert').html(output);
        }
    });
});
$("body").delegate('.star', 'change', function () {
    var THIS = $(this);
    var id = $(this).parents('.stars').data('id');
    var val = $(this).val();
    $.ajax({
        type: "POST",
        url: getGradeUrl,
        data: {
            'message_id': id,
            'grade': val
        },
        success: function (response) {
            THIS.parents('.stars').find('.rate').text(response.rating);
            newMessage();
        },
        error: function (e) {
            if (e.status == 401) {
                var template = $('#validate-user-logged-star').html();
                var output = Mustache.render(template);
                $('.message_alert_share').html('');
                $('.message_alert_share').html(output);
            } else {

            }
        }
    });
});
$("body").delegate('.notifications li.unread a', 'click', function (e) {
    e.preventDefault();
    var notification_id = $(this).attr('data-id');
    $.ajax({
        url: getReadNotificationUrl,
        data: {
            'notification_id': notification_id
        },
        type: 'post',
        success: function (data) {
            location.href = $("a[data-id='" + data.notification_id + "']").attr('href');
        }
    });
});
function countChar(val) {
    var len = val.value.length;
    var length = pageScreenshot(val.value);
    $('.words-to-write').text(200 - len + length);
    if ((len - length) > 200) {
        $('.btn-send-message').prop("disabled", true);
    } else {
        $('.btn-send-message').prop("disabled", false);
    }
}
;
function pageScreenshot(val) {
    var url = urlify(val);
    var length = 0;
    if ($.isArray(url)) {

        $('.btn-send-message').first().prop("disabled", true);
        $.ajax({
            url: getScreenshotUrl,
            data: {
                'src': url[url.length - 1]
            },
            type: 'get',
            dataType: "html",
            success: function (data) {
                $("#mydiv_iframe").show();
                $("#mydiv_iframe").html(data);
                $('.btn-send-message').first().prop("disabled", false);
            }, error: function (e) {
                $('.btn-send-message').first().prop("disabled", false);
            }
        });
        for (var i = 0; i < url.length; i++) {
            length += url[i].length;
        }
    } else {
        $("#mydiv_iframe").hide();
        $('.btn-send-message').first().prop("disabled", false);
    }
    return length;
}

function countCharModal(val) {
    var len = val.value.length;
    var length = pageScreenshot(val.value);
    $('.words-to-write-modal').text(200 - len + length);
    if ((len - length) > 200) {
        $('.share-modal-button').prop("disabled", true);
    } else {
        $('.share-modal-button').prop("disabled", false);
    }
}

function urlify(text) {
    geturl = new RegExp(
            "(^|[ \t\r\n])((ftp|http|https|gopher|mailto|news|nntp|telnet|wais|file|prospero|aim|webcal):(([A-Za-z0-9$_.+!*(),;/?:@&~=-])|%[A-Fa-f0-9]{2}){2,}(#([a-zA-Z0-9][a-zA-Z0-9$_.+!*(),;/?:@&~=%-]*))?([A-Za-z0-9$_+!*();/?:~-]))"
            , "g"
            );

    var testUrl = text.match(geturl);
    return testUrl;
    // or alternatively
    // return text.replace(urlRegex, '<a href="$1">$1</a>')
}
;
(function ($) {
    $.fn.extend({
        livePreview: function (options) {

            var defaults = {
                trigger: 'hover',
                targetWidth: 1000,
                targetHeight: 800,
                viewWidth: 300,
                viewHeight: 200,
                position: 'bottom',
                positionOffset: 40,
            };
            var options = $.extend(defaults, options);
            //calculate appropriate scaling based on width.
            var scale_w = (options.viewWidth / options.targetWidth);
            var scale_h = (options.viewHeight / options.targetHeight);
            var scale_f = 1;
            var preview_id = 'livepreview_dialog';
            if (typeof options.scale != 'undefined')
                scale_f = options.scale;
            else
            {
                if (scale_w > scale_h)
                    scale_f = scale_w;
                else
                    scale_f = scale_h;
            }

            var showPreview = function (event) {
                var triggerType = event.data.triggerType;
                var obj = event.data.target;
                var href = event.data.href;
                var s = event.data.scale;
                if ((triggerType == 'click') && ($('#' + preview_id).length == 0)) {
                    event.preventDefault();
                }

                var currentPos = options.position;
                if (obj.attr("data-position"))
                    currentPos = obj.attr("data-position");
                var currentOffset = options.positionOffset;
                if (obj.attr("data-positionOffset"))
                    currentOffset = obj.attr("data-positionOffset");
                if (obj.attr("data-scale"))
                    s = obj.attr("data-scale");
                var pos = $(this).offset();
                var width = $(this).width();
                var height = $(this).height();
                var toppos = pos.top - (options.viewHeight / 2);
                var leftpos = pos.left + width + currentOffset;
                if (currentPos == 'left') {
                    leftpos = pos.left - options.viewWidth - currentOffset;
                }

                if (currentPos == 'top') {
                    leftpos = pos.left + (width / 2) - (options.viewWidth / 2);
                    toppos = pos.top - options.viewHeight - currentOffset;
                }

                if (currentPos == 'bottom') {
                    leftpos = pos.left + (width / 2) - (options.viewWidth / 2);
                    toppos = pos.top + (height / 2) + currentOffset;
                }

                //hover on 
                $('body').append('<div id="livepreview_dialog" class="' + currentPos + '" style="display:none; padding:0px; left: ' + leftpos + 'px; top:' + toppos + 'px; width: ' + options.viewWidth + 'px; height: ' + options.viewHeight + 'px"><div class="livepreview-container" style="overflow:hidden; width: ' + options.viewWidth + 'px; height: ' + options.viewHeight + 'px"><iframe id="livepreview_iframe" src="' + href + '" style="height:' + options.targetHeight + 'px; width:' + options.targetWidth + 'px;-moz-transform: scale(' + s + ');-moz-transform-origin: 0 0;-o-transform: scale(' + s + ');-o-transform-origin: 0 0;-webkit-transform: scale(' + s + ');-webkit-transform-origin: 0 0;"></iframe></div></div>');
                $('#' + preview_id).fadeIn(100);
            };
            return this.each(function () {
                var o = options;
                var s = scale_f;
                var obj = $(this);
                var href = obj.attr("data-preview-url") || obj.attr("href");
                var triggerType = options.trigger;
                if (obj.attr("data-trigger")) {
                    triggerType = obj.attr("data-trigger");
                }

                if (triggerType != 'click') {
                    triggerType = 'mouseenter';
                    obj.on('click', function () {
                        $('#' + preview_id).remove();
                    });
                }

                obj.on(triggerType, null, {triggerType: triggerType, target: obj, href: href, scale: s}, showPreview);
                obj.on('mouseleave', function () {
                    $('#' + preview_id).remove();
                });
            });
        }
    });
})(jQuery);

function getStarsHtml(rating) {

    var html = '';
    html += '<form action=\"\">';
    for (var i = 5; i >= 1; i--) {
        var random = Math.floor(Math.random() * 100000000);
        var status = '';
        if (rating == i) {
            status = 'checked';
        }
        html += '<input class=\"star star-' + i + '\"  id=\"star-' + i + random + '\" ' + status + ' type=\"radio\" value=\"' + i + '\" name=\"star\"/>' +
                '<label class=\"star star-' + i + '\" attr_value =\"' + i + '\" for=\"star-' + i + random + '\"></label>';
    }
    html += '</form>';
    return html;
}

function replaceMessage(message) {
    var message_content = message;
    var links = urlify(message_content);
    if ($.isArray(links)) {
        for (var i = 0; i < links.length; i++) {
            message_content = message_content.replace($.trim(links[i]), '<a class=\"livepreview\" data-trigger=\"click\" href="' + $.trim(links[i]) + '">' + $.trim(links[i]) + '</a> ');
        }
    }

    message_content = message_content.replace(/!([a-zA-Z0-9]+)[-\/]([a-zA-Z0-9]+)/ig, '<a test href="' + getForexUrl + '/$1-$2">!$1-$2</a>');
    message_content = message_content.replace(/!([a-zA-Z0-9]+)[ ]/ig, '<a class=\"livepreview\" data-trigger=\"click\" href="' + getSymbolUrl + '/$1">!$1</a> ');
    message_content = message_content.replace(/!([a-zA-Z0-9]+)[.]/ig, '<a class=\"livepreview\" data-trigger=\"click\" href="' + getSymbolUrl + '/$1">!$1</a>.');
    message_content = message_content.replace(/!([a-zA-Z0-9]+)$/ig, '<a class=\"livepreview\" data-trigger=\"click\" href="' + getSymbolUrl + '/$1">!$1</a><br/>');
    message_content = message_content.replace(/@([a-z\d_]+)/ig, '<a class=\"livepreview\" data-trigger=\"click\" href="' + getUserSymbolUrl + '/$1">@$1</a>');
    return message_content;
}
function popoverUser() {
    var $elements = $('.my-popover');
    var i = 1;
    $elements.each(function () {
        var $element = $(this);
        var username = $element.data('user-username');
        var followed = $element.data('followed');
        var button_class = (followed) ? 'btn-primary' : 'btn-link';
        $element.popover({
            html: true,
            trigger: "manual",
            placement: 'top',
            container: $('body'),
            content: '<button type="button" class="btn ' + button_class + ' follow_button " id="follow_' + i + '" data-user-username="' + username + '">Follow</button>'
        }).on("mouseenter", function () {
            var _this = this;
            $(this).popover("show");
            $(".popover").on("mouseleave", function () {
                $(_this).popover('hide');
            });
        }).on("mouseleave", function () {
            var _this = this;
            setTimeout(function () {
                if (!$(".popover:hover").length) {
                    $(_this).popover("hide");
                }
            }, 300);
        });
        $element.on('shown.bs.popover', function (e) {
            var popover = $element.data('bs.popover');
            if (typeof popover !== "undefined") {
                $('.follow_button').bind('click', function () {
                    var follow_username = $(this).data('user-username');
                    var THIS = $(this);
                    var class_name = '';
                    if ($(this).hasClass('btn-primary')) {
                        $(this).removeClass('btn-primary');
                        class_name = 'btn-link';
                    } else {
                        class_name = 'btn-primary';
                        $(this).removeClass('btn-link');
                    }
                    $.ajax({
                        type: "get",
                        url: getFollowUrl,
                        data: {
                            username: follow_username
                        },
                        success: function (response) {
                            if (response.data == 'delete') {
                                _toastr(follow_username + " removed from the following list", "top-right", "success", false);
                            } else {
                                _toastr(follow_username + " added to the following list", "top-right", "success", false);
                            }
                            THIS.addClass(class_name);
                        },
                        error: function (e) {

                        }
                    });
                });
            }
        });
        i++;
    });
}
$(document).ready(function () {
    newMessage();
    commentCount();
    newComment();
    changeMessages();
    popoverUser();

});
