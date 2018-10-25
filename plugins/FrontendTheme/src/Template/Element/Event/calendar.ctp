<h4 class="text-center">Calendar</h4>
<div class="toggle toggle-bordered-simple" data-url="<?= $this->Url->build(['_name' => 'event_list']); ?>">
    <div class="toggle calendar" id="week">
        <label class="text-center">This week</label>
        <div class="toggle-content week">
        </div>
    </div>

    <div class="toggle calendar" id="month">
        <label class="text-center">This Month</label>
        <div class="toggle-content month">
        </div>
    </div>

    <div class="toggle calendar" id="year">
        <label class="text-center">This year</label>
        <div class="toggle-content year">
        </div>
    </div>
</div>

<!-- /toggle -->
<script type="text/javascript">
    $(document).ready(function() {
        _toggle();
        var getUrl = $('.toggle-bordered-simple').data('url');

        $(".calendar").click(function() {
            var active = $(this).hasClass('active');
            var date = $(this).attr('id');
            if (active) {
                $.ajax({
                    type: "get",
                    url: getUrl,
                    data: {
                        data: date
                    },
                    success: function (response) {
                        $('div.toggle-content.' + date).html(response);
                    },
        
                });
            }
        });
    });
</script>