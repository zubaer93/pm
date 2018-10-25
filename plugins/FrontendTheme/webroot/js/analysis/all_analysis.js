$(document).ready(function () {
    var getDeleteUrl = $('.analysis-table').data('delete-url');
    var getPrintUrl = $('.analysis-table').data('print-url');
    var getWordPrintUrl = $('.analysis-table').data('doc-print-url');
    var getEditPartialUrl = $('.analysis-table').data('edit-partial-url');
    var getApproveUrl = $('.analysis-table').data('approve-url');
    var getMakeCopyeUrl = $('.analysis-table').data('make-copy-url');
    var getTeamShareUrl = $('.analysis-table').data('share-team-url');

//    $('.analysi_form_modal').submit(function (e) {
//        e.preventDefault();
//        $.ajax({
//            type: "POST",
//            url: $(this).attr('action'),
//            data: $(this).serialize(),
//            success: function (response) {
//                $('#addModal').modal('hide');
//                _toastr("The analysi has been saved.", "top-right", "success", false);
//            },
//            error: function (e) {
////                 $('#addModal').modal('hide');
////                _toastr("The analysi could not be saved. Please, try again.", "top-right", "error", false);
//            }
//        });
//    });


    $('.modal_delete_button').click(function () {
        $('#deleteModal').modal('hide');
        deleteAnalysisRow();
    });



    $('.checkAll').on('click', function () {
        $(this).closest('table').find('tbody :checkbox')
                .prop('checked', this.checked)
                .closest('tr').toggleClass('selected', this.checked);

        deleteButton(getCheckedCount());
    });

    $('tbody :checkbox').on('click', function () {
        $(this).closest('tr').toggleClass('selected', this.checked); //Classe de seleção na row

        $(this).closest('table').find('.checkAll').prop('checked',
                ($(this).closest('table').find('tbody :checkbox:checked').length == $(this).closest('table').find('tbody :checkbox').length));
        deleteButton(getCheckedCount());
    });

    function deleteAnalysisRow() {
        var data = getCheckedCheckBoxValue();
        $.ajax({
            type: "GET",
            url: getDeleteUrl,
            data: {
                data: data
            },
            success: function (response) {
                $.each(response.data, function (index, value) {
                    $('.analysis-' + value).remove();
                });
                $('#alert-success-copy').fadeIn(1000).delay(2000).fadeOut(2000)
                        .find('.alert-msg').text('The analysis has been deleted.');
            },
            error: function (e) {
                $('#alert-danger-copy').fadeIn(1000).delay(2000).fadeOut(2000)
                        .find('.alert-msg').text('The analysis could not be deleted. Please, try again.');
            }
        });
    }

    function getCheckedCheckBoxValue() {
        var chkArray = [];

        $(".checkbox_analysis:checked").each(function () {
            chkArray.push($(this).val());
        });
        return chkArray;
    }

    function getCheckedCount() {
        var count = $('.checkbox_analysis:checked').length;
        return count;
    }

    function deleteButton(count) {
        if (count == 0) {
            $('.delete-analysis-button').addClass("disabled").css('cursor', 'not-allowed');
            $('.checked_count').text('');
        } else {
            $('.delete-analysis-button').removeClass('disabled').css('cursor', 'default');
            $('.checked_count').text(count);
        }
    }

    popoverAllWatchlistAction();
    function  popoverAllWatchlistAction() {
        var $elements = $('.my-popover-action');
        var content = $('.popover_content').html();
        $elements.each(function () {
            var $element = $(this);
            var id = $(this).data('id');
            var url = $(this).data('url');
            $element.popover({
                html: true,
                trigger: "manual",
                placement: 'top',
                container: $('body'),
                content: '<div class="popover-parent-div" data-url="' + url + '"  data-id="' + id + '">' + content + '</div>'
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
        });
    }

    $('body').delegate('.popover-edit', 'click', function () {
        var row_id = $(this).parents('.popover-parent-div').data('id');
        $.ajax({
            type: "get",
            url: getEditPartialUrl,
            dataType: 'html',
            data: {
                data: row_id
            },
            success: function (response) {
                $('.editModalBody').html('');
                $('.editModalBody').html(response);
                $('#editModal').modal('show');
                _editors();
                _select2();
            },
            error: function (e) {
            }
        });
    });

    $('body').delegate('.popover-print', 'click', function () {
        var row_id = $(this).parents('.popover-parent-div').data('id');
        window.location.href = getPrintUrl + '?print=' + row_id;
    });
    $('body').delegate('.popover-approve', 'click', function () {
        var row_id = $(this).parents('.popover-parent-div').data('id');
        $.ajax({
            type: "get",
            url: getApproveUrl,
            data: {
                data: row_id
            },
            success: function (response) {
                if (response.status == 'success') {
                    if (response.approve == 1) {
                        $('#approval' + row_id).removeClass('badge-danger');
                        $('#approval' + row_id).addClass('badge-success');
                        $('#approval' + row_id).html('Approved');
                    } else {
                        $('#approval' + row_id).removeClass('badge-success');
                        $('#approval' + row_id).addClass('badge-danger');
                        $('#approval' + row_id).html('No Approval');
                    }
                }
            },
            error: function (e) {
            }
        });
    });

    $('body').delegate('.popover-microsoft-word', 'click', function () {
        var row_id = $(this).parents('.popover-parent-div').data('id');
        window.location.href = getWordPrintUrl + '?print=' + row_id;
        
    });

//    $('body').delegate('.analysi_form', 'submit', function (e) {
//        e.preventDefault();
//        $.ajax({
//            type: "GET",
//            url: $(this).attr('action'),
//            data: $(this).serialize(),
//            success: function (response) {
//                $('#editModal').modal('hide');
//                _toastr("The analysis has been saved.", "top-right", "success", false);
//            },
//            error: function (e) {
//                $('#editModal').modal('hide');
////                _toastr("The analysis could not be saved. Please, try again.", "top-right", "error", false);
//            }
//        });
//    });


    $('body').delegate('.popover-make-copy', 'click', function () {
        var row_id = $(this).parents('.popover-parent-div').data('id');
        $.ajax({
            type: "GET",
            url: getMakeCopyeUrl,
            data: {
                data: row_id
            },
            success: function (response) {
                $('#editModal').modal('hide');
                location.reload();
                $('#alert-success-copy').fadeIn(1000).delay(2000).fadeOut(2000)
                        .find('.alert-msg').text('Analysis Copy Successfully');
            }
        });

        // var thisRow = $('.analysis-'+row_id).closest( 'tr' );
        // $( thisRow ).clone().insertAfter( thisRow ).find( 'input:text' ).val( '' );

    });

    $('body').delegate('.popover-share-with-team', 'click', function () {
        var row_url = $(this).parents('.popover-parent-div').data('url');
        $('.analysis_url').val(row_url);
        $('.share-team-modal-lg').modal('show');
        // $('.send-team').on('click',function(e){
        //     $('.send-team').attr('disabled','disabled');
        // });
    });


    $('body').delegate('.popover-share-with-client', 'click', function () {
        var row_id = $(this).parents('.popover-parent-div').data('id');
        var email = "";
        location.href = "mailto:" + email;
    });
}
);
