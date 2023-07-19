<?php
$sql2 = "SELECT teacher.teacher_id, CONCAT_WS(' ', name, surname) AS teacher_name FROM teacher ORDER BY teacher_name;";
$result2 = $con->query($sql2);
while ($row2 = mysqli_fetch_array($result2)) {
?>
    <option value="<?php echo $row2['teacher_id']; ?>">
        <?php echo $row2['teacher_name']; ?>
    </option>
<?php
}
?>