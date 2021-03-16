<select class="custom-select" id="inputEntrepot">
    <option selected></option>
    <?php
        foreach($warehouses as $warehouse)
            echo "<option value=" . $warehouse["id"] . ">" . $warehouse["address"] . "</option>";
    ?>
</select>
