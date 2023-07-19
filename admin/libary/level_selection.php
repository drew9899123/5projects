<label for="" class="form-label">ระดับชั้น</label>
<select name="level" id="" class="form-select" required>
    <option value="">เลือก...</option>
    <?php
    $sql2 = "SELECT DISTINCT level FROM student ORDER BY level";
    $result2 = $con->query($sql2);
    while ($row2 = mysqli_fetch_array($result2)) {
    ?>
        <option value="<?php echo $row2['level']; ?>">
            <?php echo $row2['level']; ?>
        </option>
    <?php
    }
    ?>
</select>