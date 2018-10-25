<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">

    <!-- Identify your business so that you can collect the payments. -->
    <input type="hidden" name="business" value="info@stockgitter.com">

    <!-- Specify a Subscribe button. -->
    <input type="hidden" name="cmd" value="_xclick-subscriptions">

    <!-- Identify the subscription. -->
    <input type="hidden" name="item_name" value="PROFESSIONAL">
    <input type="hidden" name="item_number" value="PROFESSIONAL">
    <input TYPE="hidden" NAME="return" value="<?= $this->Url->build(['_name' => 'subscription'], true); ?>">
    <input type="hidden" name="rm" value="2">

    <!-- Set the terms of the 1st trial period. -->
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="a1" value="10">
    <input type="hidden" name="p1" value="7">
    <input type="hidden" name="t1" value="D">

    <!-- Set the terms of the regular subscription. -->
    <input type="hidden" name="a3" value="30.00">
    <input type="hidden" name="p3" value="1">
    <input type="hidden" name="t3" value="Y">

    <!-- Set recurring payments until canceled. -->
    <input type="hidden" name="src" value="1">

    <!-- Display the payment button. -->
    <?= $this->Form->button(__('UPGRADE'), ['class' => 'btn btn-default', 'type' => 'submit']);?>
</form>