<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-mini alert-danger mb-30" onclick="this.classList.add('d-none');"><?= $message ?></div>
