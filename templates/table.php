
<table class="table table-hover">
  <thead>
  <tr>
    <?php foreach ($cols as $col) {
      echo '<th scope="col">' .$col. '</th>';
    }?>
  </tr>
  </thead>
  <tbody>
  <?php
  if (isset($rows) && count($rows) > 0)
    foreach ($rows as $row):?>
    <tr>
      <?php

        foreach ($row as $data) {
        echo "<td>$data</td>";
      }?>
    </tr>
  <?php endforeach;?>
  </tbody>
</table>
