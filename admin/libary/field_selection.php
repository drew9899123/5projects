<label for="" class="form-label">สาขางาน</label>
<select name="field_id" id="" class="form-select" required>
    <option value="">เลือก...</option>
    <?php
    $sql2 = "SELECT * FROM studyfield ORDER BY field_name";
    $result2 = $con->query($sql2);
    while ($row2 = mysqli_fetch_array($result2)) {
    ?>
        <option value="<?php echo $row2['field_id']; ?>">
            <?php echo $row2['field_name']; ?>
        </option>
    <?php
    }
    ?>
</select>