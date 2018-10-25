<div class="float-rigth">
    <?php for ($i = 5; $i >= 1; $i--): ?>
        <?php if (isset($rating) && $rating == $i): ?>
            <input class="star star-<?= $i ?>" disabled checked id="star-<?= $i; ?>" type="radio" value="<?= $i; ?>" name="star"/>
        <?php else: ?>
            <input class="star star-<?= $i ?>" disabled id="star-<?= $i; ?>" type="radio" value="<?= $i; ?>" name="star"/>
        <?php endif; ?>
        <label class="star star-<?= $i ?>" attr_value ="<?= $i; ?>" for="star-<?= $i; ?>"></label>
    <?php endfor; ?>
</div>