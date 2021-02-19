<?php
?>

<li>
  <a href="<?= $item->link() ?>">
    <div class="svgcontainer" style="background-color: <?= $item->color() ?>">
        <?= $item->icon() ?>
    </div>
    <span><?= $item->name() ?></span>
  </a>
</li>
