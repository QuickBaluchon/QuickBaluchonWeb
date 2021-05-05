<?php
extract($this->_template) ;
extract($data);
?>
<h1 class="mb-4"><?= $Title ?></h1>
<?php
if ($roadmaps !== null)
    foreach ($roadmaps as $id => $roadmap):
        $percentage = intval(100 * $roadmap['currentStop'] / $roadmap['nbPackages']) ;
        if ($percentage > 100) $percentage = 100;

        $bg = $percentage === 100 ? 'bg-success' : ($percentage <= 50 ? 'bg-warning' : 'bg-info');
        if ($percentage < 100 && $roadmap['finished'] == 1) $bg = 'bg-danger';
        $hours = intval($roadmap['timeTotal']) ;
        $min = intval(($roadmap['timeTotal'] - $hours) * 60) ;
?>
    <div>
        <h5><?= $roadmap['dateRoute'] . " $HeaderBy " . $roadmap['firstname'] . " " . $roadmap['lastname'] ?></h5>
        <p>
            <?= $TextTotal ?> :
            <?= $hours !== 0 ? "$hours $TextHours" : ' ' ?>
            <?= "$min $TextMinutes / " . number_format($roadmap['kmTotal'], 2) . ' km'?>
        </p>
        <p>
            <?= $TextPackages ?> :
            <?= $roadmap['currentStop'] . ' / ' . $roadmap['nbPackages'] ?>
        </p>
        <div class="progress">
            <div class="progress-bar <?= $bg ?>"
                 aria-valuemin="0"
                 aria-valuemax="<?= $roadmap['nbPackages'] ?>"
                 aria-valuenow="<?= $percentage ?>"
                 style="width: <?= $percentage ?>%">
                <?= $percentage ?>%
            </div>
        </div>
        <?php if ($roadmap['finished'] == 1 && $percentage === 0): ?>
        <br>
        <div class="alert alert-danger"><?= $TextAbandonned ?></div>
        <?php endif; ?>
    </div>
   <hr>
<?php
    endforeach;
else {
?>
    <div class="alert alert-info"><?= $TextNotFound ?></div>
<?php
}
