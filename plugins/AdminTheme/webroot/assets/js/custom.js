$('#companyFilter').on('change', function() {
    $.ajax({
        url: document.URL+"/"+$(this).val()+"/"+$('#experienceFilter').val(),
        data: {},
        type: 'post',
        success: function (data) {
            $("#sample_editable_1 > tbody").replaceWith(data);
        }
    })
})

$('#experienceFilter').on('change', function() {
	$.ajax({
            url: document.URL+"/"+$('#companyFilter').val()+"/"+$(this).val(),
            data: {},
            type: 'post',
            success: function (data) {
                $("#sample_editable_1 > tbody").replaceWith(data);
            }
        })
})