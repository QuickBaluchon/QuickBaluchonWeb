<?php
extract($this->_template);
extract($data);
?>

<div class="container-xl">
    <div class="row">
        <div class="col-lg">
            <?php $Title .= isset($roadmap['dateRoute']) ? $roadmap['dateRoute'] : date('Y/m/d') ; ?>
            <h1><?= $deliveryman['lastname'] ?> / <?= $Title ?></h1>

            <?php if (count($roadmap) > 0): ?>
                <section class="mb-5 mt-5">
                    <?php
                    foreach ($roadmap['stops'] as $stopNb => $stop):
                        $style = "";

                        if ($stopNb < $roadmap['currentStop']) {
                            $style = "text-white " ;
                            $style .= $stop['delivery'] != null ? "bg-success" : "bg-warning";
                        }

                        if ($stopNb == $roadmap['currentStop'])
                            $style = "bg-primary text-white";
                    ?>
                        <div class="card mb-3">
                            <div class="card-header <?= $style ?>">
                                <?= $CardHeader ?> <?= $stopNb + 1 ?>
                            </div>
                            <div class="card-body">
                                <p><?= $TextPackage ?> <?= $stop['package'] ?></p>
                                <p>
                                    <?= $stop['recipient'] ?>
                                    <br>
                                    <?= $stop['address'] ?>
                                </p>
                                <?php
                                if ($stopNb == $roadmap['currentStop']):
                                    $hours = intval($stop['timeNextHop']) == 0 ? null : intval($stop['timeNextHop']);
                                    $minutes = intval(($stop['timeNextHop'] - $hours) * 60) ;
                                ?>
                                    <p>
                                        <?= $TextDistanceToNext ?> <?= $stop['distanceNextHop'] ?> km<br>
                                        <?= $TextTimeToNext ?> <?php isset($hours) ? $hours . " heures " : "" ; echo $minutes . " minutes" ; ?>
                                    </p>
                                <?php endif ; ?>
                            </div>
                            <div class="card-footer">
                                <?php
                                if ($stop['delivery'] != null):
                                    echo $TextDateTimeDelivery  . " " . $stop['delivery'] ;
                                elseif ($stopNb == $roadmap['currentStop']):
                                    echo $TextDelivering ;
                                else:
                                    echo $TextNotDelivered ;
                                endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>

                <hr>
                <?php if ($roadmap['finished'] == 1): ?>
                <section class="mt-5">
                    <div class="alert alert-success">
                        Cette livraison est terminée
                    </div>
                </section>
                <?php else: ?>
                <section class="mt-5">
                    <h2><?= $TitleDanger ?></h2>
                    <div class="container rounded border border-danger d-flex flex-wrap justify-content-between p-4">
                        <div class="flex-auto">
                            <strong><?= $LabelDelete ?></strong>
                            <p class="mb-0"><?= $WarningDelete ?></p>
                        </div>
                        <div>
                            <button type='button' onclick='stopDeliveries(<?= $roadmap['id'] ?>)' class='btn btn-outline-danger'><?= $ButtonDelete ?></button>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

            <?php else: ?>
                <section class="mt-5">
                    <div class="alert alert-info"><?= $TextNoRoadmap ?></div>
                </section>
            <?php endif; ?>
        </div>
    </div>
</div>