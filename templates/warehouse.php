<section id="warehouse">
  <h1>Entrepôt</h1>
  <p>adresse: <?= $details["address"]?></p>
  <p><?= $details["AvailableVolume"]?> m3 / <?= $details["volume"]?> dispo </p>
  <p><?= $deliveryman ?> liveurs associés</p>
</section>

<section class="mt-5">
  <h2>Danger Zone</h2>
  <div class="container rounded border border-danger d-flex flex-wrap justify-content-between p-4">
    <div class="flex-auto">
      <strong>Supprimer cet entrepôt</strong>
      <p class="mb-0">La suppression d'un entrepôt est définitive. Soyez certain.</p>
    </div>
    <div>
      <button type="button" onclick="updateWarehouse(<?= $id ?>)" class="btn btn-outline-danger">Supprimer cet entrepôt</button>
    </div>
  </div>
</section>

<script src="<?=WEB_ROOT.'media/script/warehouse/updateWarehouse.js'?>"></script>
