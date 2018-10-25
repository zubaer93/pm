<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<script type="text/javascript">
    new TradingView.widget({
        "autosize": true,
        "symbol": "<?= $symbol; ?>",
       "interval": "D",
       "timezone": "exchange",
       "theme": "Light",
       "style": "1",
       "locale": "en",
       "toolbar_bg": "#f1f3f6",
       "enable_publishing": false,
       "allow_symbol_change": true,
       "details": true,
       "hotlist": true,
       "hide_side_toolbar": false,
       "save_image": false,
       "hideideas": true
   });
</script>
