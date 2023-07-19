<?php
    include '../connect.php';
    $id = $_GET['group_id'];
    $sql = "SELECT study_group.group_id, study_group.group_name, subquery.teacher_id, subquery.teacher_name
            FROM study_group
            LEFT OUTER JOIN (
                SELECT teacher_id, CONCAT(name,' ',surname) as teacher_name FROM teacher
            ) as subquery ON study_group.teacher_id = subquery.teacher_id WHERE study_group.group_id = '$id';";
    $result = $con->query($sql);
    $row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
</head>

<body>
    <?php include 'navbar.php' ?>
    <div class="container my-5">
        <form action="" method="post">
            <div class="row mb-3">
                <div class="col-sm-3">
                    <label for="" class="form-label">รหัสกลุ่มเรียน</label>
                    <input type="text" name="id" id="" class="form-control" value="<?php echo $id ?>">
                </div>
                <div class="col-sm-9">
                    <label for="" class="form-label">ชื่อเต็มกลุ่ม</label>
                    <input type="text" name="id" id="" class="form-control" value="<?php echo $row['group_name'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm">
                    <label for="" class="form-label">ครูที่ปรึกษา</label>
                    <select name="teacher_id" id="" class="form-select">
                        <option value="">ไม่มีที่ปรึกษา</option>
                        <?php
                        $sql = "SELECT teacher.teacher_id, CONCAT(teacher.name, ' ', teacher.surname) AS teacher_name FROM teacher;";
                        $result = $con->query($sql);
                        while ($row2 = mysqli_fetch_array($result)) {
                        ?>
                            <option value="<?php echo $ro2['teacher_id']; ?>" <?php if ($row2['teacher_id'] == $row['teacher_id']) {
                                                                                    echo ' selected';
                                                                                } ?>>
                                <?php echo $row2['teacher_name']; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm">
                    <input type="submit" value="บันทึก" class="btn btn-warning" name="save">
                </div>
            </div>
        </form>
    </div>
</body>

</html>