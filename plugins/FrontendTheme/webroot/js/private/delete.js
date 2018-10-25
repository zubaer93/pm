/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var getDeleteUrl = $('.feed-container').attr("data-delete-post");

$("body").delegate('.delete_private', 'click', function () {
    $('#confirm-delete-post').modal('show');
    $('.delete-post').attr('data-id', $(this).data('id'));
});
$(".modal").delegate('.delete-post', 'click', function () {
    $('#confirm-delete-post').modal('hide');
    var id = $(this).attr('data-id');
    $.ajax({
        type: "get",
        url: getDeleteUrl,
        data: {
            'message_id': id
        },

       success: function (response) {
           $(".delete_private[data-id='" + id + "']").parents('tr').remove();
       },
//        error: function (e) {
//            var template = $('#validate-user-logged-delete').html();
//            var output = Mustache.render(template);
//            $('.message_alert').html('');
//            $('.message_alert').html(output);
//        }
    });
});