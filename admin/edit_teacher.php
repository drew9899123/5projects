<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>จัดการ แผนก หน่วยงานและสาขาวิชา</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php
    include 'navbar.php';
    include '../connect.php';

    if (!isset($_GET['teacher_id'])) {
        header('location:dept.php?keyword=');
    } else {
        $teacher_id = $_GET['teacher_id'];
        $sql = "SELECT *
                FROM teacher
                WHERE teacher_id = '$teacher_id';
                ";
        $result = $con->query($sql);
        $row = mysqli_fetch_array($result);
    }

    if (isset($_POST['save'])) {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        if (empty($surname)) {
            $sqlUpdate = "UPDATE teacher SET name = '$name', surname = NULL
                             WHERE teacher_id = '$teacher_id'";
        } else {
            $sqlUpdate = "UPDATE teacher SET name = '$name', surname = '$surname'
                        WHERE teacher_id = '$teacher_id'";
        }
        $result = $con->query($sqlUpdate);
        if ($result) {
            echo "<script>alert('บันทึกการแก้ไขสำเร็จ')</script>";
            echo "<script>window.location.href='teacher.php?keyword='</script>";
        } else {
            echo "<script>alert('ไม่สามารถแก้ไขได้')</script>";
        }
    }

    if (isset($_POST['delete'])) {
        $sql = "DELETE FROM teacher WHERE teacher_id = '$teacher_id'";
        if ($result = $con->query($sql)) {
            echo "<script>alert('ลบรายการ " . $teacher_id . " สำเร็จ')</script>";
            echo "<script>window.location.href='dept.php?keyword='</script>";
        } else {
            echo "<script>alert('ลบรายการ " . $teacher_id . " สำไม่สำเร็จ เนื่องจากคำสั่งไม่ถูกต้อง')</script>";
        }
    }
    ?>
    <div class="container my-5">
        <div class="header">
            <h1>จัดการ บุคลากร ครู อาจารย์</h1>
        </div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="row mb-3">
                <label for="" class="form-label">ชื่อ</label>
                <input type="text" name="name" id="" class="form-control" value="<?php echo $row['name'] ?>" required>
            </div>
            <div class="row mb-3">
                <label for="" class="form-label">นามสกุล</label>
                <input type="text" value="<?php echo $row['surname']?>" class="form-control" name="surname">
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