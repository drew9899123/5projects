<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>จัดการ นักเรียน นักศึกษา</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php
    include 'navbar.php';
    include '../connect.php';

    if (!isset($_GET['std_id'])) {
        header('location:std.php?keyword=');
    } else {
        $std_id = $_GET['std_id'];
        $sql = "SELECT
                              std_id,
                              password,
                              prefix,
                              name,
                              surname,
                              CONCAT_WS(' ', name, surname) AS full_name,
                              CONCAT('(', SUBSTRING_INDEX(level, '(', -1)) AS level,
                              field_name,
                              study_group.group_name,
                              system,
                              tel
                          FROM student
                          INNER JOIN studyfield ON student.field_id = studyfield.field_id
                          INNER JOIN study_group ON student.group_id = study_group.group_id
                          WHERE
                              std_id = '$std_id';
                        ";
        $result = $con->query($sql);
        $row = mysqli_fetch_array($result);
    }

    if (isset($_POST['save'])) {
        $dept_name = $_POST['dept_name'];
        $teacher_id = $_POST['teacher_id'];
        if (empty($teacher_id)) {
            //Teacher has selected
            $sqlUpdate = "UPDATE department SET dept_name = '$dept_name', teacher_id = NULL
                             WHERE dept_id = '$dept_id'";
        } else {
            //Teacher has not selected
            $sqlUpdate = "UPDATE department SET dept_name = '$dept_name', teacher_id = '$teacher_id'
                        WHERE dept_id = '$dept_id'";
        }
        $result = $con->query($sqlUpdate);
        if ($result) {
            echo "<script>alert('บันทึกการแก้ไขสำเร็จ')</script>";
            echo "<script>window.location.href='dept.php?keyword='</script>";
        } else {
            echo "<script>alert('ไม่สามารถแก้ไขได้')</script>";
        }
    }

    if (isset($_POST['delete'])) {
        $sql = "DELETE FROM department WHERE dept_id = '$dept_id'";
        if ($result = $con->query($sql)) {
            echo "<script>alert('ลบรายการ " . $dept_id . " สำเร็จ')</script>";
            echo "<script>window.location.href='dept.php?keyword='</script>";
        } else {
            echo "<script>alert('ลบรายการ " . $dept_id . " สำไม่สำเร็จ เนื่องจากคำสั่งไม่ถูกต้อง')</script>";
        }
    }
    ?>
    <div class="container my-5">
        <div class="header">
            <h1>จัดการ แผนก หน่วยงานและสาขาวิชา</h1>
        </div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="modal-body">
                <div class="row mb-3">
                    <!-- รหัสนักศึกษา -->
                    <div class="col-sm">
                        <label for="" class="form-label">รหัสนักศึกษา</label>
                        <input type="text" name="std_id" id="" class="form-control" maxlength="11" value="<?php echo $row['std_id']?>" disabled>
                    </div>
                    <!-- รหัสผ่าน -->
                    <div class="col-sm">
                        <label for="" class="form-label">รหัสผ่าน</label>
                        <input type="text" name="password" id="" class="form-control" maxlength="12" value="<?php echo $row['password']?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <!-- คำนำหน้า -->
                    <div class="col-sm-2">
                        <?php include 'libary/prefix_selection.php' ?>
                    </div>
                    <!-- ชื่อ -->
                    <div class="col-sm-5">
                        <label for="" class="form-label">ชื่อ</label>
                        <input type="text" name="name" id="" class="form-control" value="<?php echo $row['name']?>" required>
                    </div>
                    <!-- สกุล -->
                    <div class="col-sm-5">
                        <label for="" class="form-label">สกุล</label>
                        <input type="text" name="surname" id="" class="form-control" value="<?php echo $row['surname']?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- ระดับชั้น -->
                    <div class="col-sm">
                        <?php include 'libary/level_selection.php'; ?>
                    </div>
                    <!-- สาขางาน -->
                    <div class="col-sm">
                        <?php include 'libary/field_selection.php'; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- กลุ่มเรียน -->
                    <div class="col-sm">
                        <?php include 'libary/studygroup_selection.php'; ?>
                    </div>
                    <!-- ระบบ -->
                    <div class="col-sm">
                        <?php include 'libary/system_selection.php' ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- โทร -->
                    <div class="col-sm">
                        <label for="" class="form-label">เบอร์โทรฯ</label>
                        <input type="tel" class="form-control" name="tel" id="" value="<?php echo $row['tel']?>" required>
                    </div>
                </div>
            </div>

            <div class="row mb-3 gap-3 d-flex flex-row">
                <input class="btn btn-warning w-25" type="submit" name="save" value="บันทึกการแก้ไข">
                <input class="btn btn-danger w-25" type="submit" name="delete" value="ลบรายการ" onclick="return confirm('คุณต้องการลบรายการนี้ใช่หรือไม่?')">
            </div>
        </form>
        <?php

        ?>

    </div>
</body>

</html>