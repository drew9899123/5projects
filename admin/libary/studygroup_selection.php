<label for="" class="form-label">กลุ่มเรียน</label>
<select name="group_id" id="" class="form-select" required>
    <option value="">เลือก...</option>
    <?php
    $sql2 = "SELECT * FROM study_group ORDER BY group_name";
    $result2 = $con->query($sql2);
    while ($row2 = mysqli_fetch_array($result2)) {
    ?>
        <option value="<?php echo $row2['group_id']; ?>">
            <?php echo $row2['group_name']; ?>
        </option>
    <?php
    }
    ?>
</select>