<script>
    $(document).ready(function () {
        Morris.Line({
            element: '<?= $id; ?>',
            data: <?= $paramChart; ?>,
            xkey: 'period',
            ykeys: ['a'],
            labels: ['Value'],
            pointSize: 3,
            ymin: <?= $min; ?>,
            resize: true,
            behaveLikeLine: true,
            lineColors: ['<?= $main_color; ?>'],
            hoverCallback: function (index, options) {
                var row = options.data[index];
                return "<?= $symbol; ?>(" + row.period + ") = " + row.a;
            },
            yLabelFormat: function (y) {
                return y.toFixed(4);
            },
        });

    });
</script>
<div class="col-md-12 col-sm-12 col-lg-12">
    <div id="<?= $id; ?>"><!-- GRAPH CONTAINER --></div>
</div>