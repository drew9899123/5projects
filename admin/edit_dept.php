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
    // include '../connect.php';

    if (!isset($_GET['dept_id'])) {
        header('location:dept.php?keyword=');
    } else {
        $dept_id = $_GET['dept_id'];
        $sql = "SELECT 
                    supervision.dept_id,
                    department.dept_name, 
                    supervision.teacher_id AS sup_teacher_id,
                    t.teacher_id AS dept_teacher_id,
                    CONCAT_WS(' ', teacher.name, teacher.surname) AS sup_teacher_fullname,
                    CONCAT_WS(' ', t.name, t.surname) AS dept_teacher_fullname
                FROM supervision
                LEFT OUTER JOIN department ON department.dept_id = supervision.dept_id
                LEFT OUTER JOIN teacher ON teacher.teacher_id = supervision.teacher_id
                LEFT OUTER JOIN teacher AS t ON t.teacher_id = department.teacher_id
                WHERE supervision.dept_id = '$dept_id';
                ";
        $result = $con->query($sql);
        $row = mysqli_fetch_array($result);
    }

    if (isset($_POST['save'])) {
        $dept_name = $_POST['dept_name'];
        $dept_teacher_id = $_POST['dept_teacher_id'];
        $sup_teacher_id = $_POST['sup_teacher_id'];
        // หัวหน้าแผนก
        if (empty($dept_teacher_id)) {
            //Teacher has selected
            $sqlUpdate = "UPDATE department SET dept_name = '$dept_name', teacher_id = NULL
                             WHERE dept_id = '$dept_id'";
        } else {
            //Teacher has not selected
            $sqlUpdate = "UPDATE department SET dept_name = '$dept_name', teacher_id = '$dept_teacher_id'
                        WHERE dept_id = '$dept_id'";
        }
        $result_dept = $con->query($sqlUpdate);

        // ครูนิเทศ
        if (empty($sup_teacher_id)) {
            //Teacher has selected
            $sqlUpdate = "UPDATE supervision SET teacher_id = NULL
                             WHERE dept_id = '$dept_id'";
        } else {
            //Teacher has not selected
            $sqlUpdate = "UPDATE supervision SET teacher_id = '$sup_teacher_id'
                        WHERE dept_id = '$dept_id'";
        }
        $result_sup = $con->query($sqlUpdate);

        
        if (!$result_sup && !$result_dept) {
            echo "<script>alert('ไม่สามารถแก้ไขได้รายการ " . $dept_name . " ได้')</script>";
        } else {
            echo "<script>alert('บันทึกการแก้ไขสำเร็จ')</script>";
            echo "<script>window.location.href = window.location.href</script>";
        }
    }

    if (isset($_POST['delete'])) {
        $sql_sup = "DELETE FROM supervision WHERE dept_id = '$dept_id'";
        $sql_dept = "DELETE FROM department WHERE dept_id = '$dept_id'";
        $result_sup = $con->query($sql_sup);
        $result_dept = $con->query($sql_dept);
        if(!$result_dept){
            echo "<script>alert('ลบรายการ table department ไม่สำเร็จ " . $dept_id . "')</script>";
        }else
        if(!$result_sup){
            echo "<script>alert('ลบรายการ table supervision ไม่สำเร็จ " . $dept_id . "')</script>";
        }else{
            echo "<script>window.location.href='department.php?keyword='</script>";
        }
    }
    ?>
    <div class="container my-5">
        <div class="header">
            <h1>จัดการ แผนก หน่วยงานและสาขาวิชา</h1>
        </div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="row mb-3">
                <label for="" class="form-label">ชื่อแผนก</label>
                <input type="text" name="dept_name" id="" class="form-control" value="<?php echo $row['dept_name'] ?>">
            </div>
            <div class="row mb-3">
                <label for="" class="form-label">หัวหน้าแผนก</label>
                <select name="dept_teacher_id" id="" class="form-select">
                    <option value="">ไม่มีหัวหน้าแผนก</option>
                    <?php
                    $sql = "SELECT teacher.teacher_id, CONCAT(teacher.name, ' ', teacher.surname) AS teacher_name FROM teacher ORDER BY teacher_name ASC;";
                    $result = $con->query($sql);
                    while ($row2 = mysqli_fetch_array($result)) {
                    ?>
                        <option value="<?php echo $row2['teacher_id']; ?>" <?php if ($row2['teacher_id'] == $row['dept_teacher_id']) {
                                                                                echo ' selected';
                                                                            } ?>>
                            <?php echo $row2['teacher_name']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="row mb-3">
                <label for="" class="form-label">ครูนิเทศ</label>
                <select name="sup_teacher_id" id="" class="form-select">
                    <option value="">ไม่มีครูนิเทศ</option>
                    <?php
                    $sql = "SELECT teacher.teacher_id, CONCAT(teacher.name, ' ', teacher.surname) AS teacher_name FROM teacher ORDER BY teacher_name ASC;";
                    $result = $con->query($sql);
                    while ($row2 = mysqli_fetch_array($result)) {
                    ?>
                        <option value="<?php echo $row2['teacher_id']; ?>" <?php if ($row2['teacher_id'] == $row['sup_teacher_id']) {
                                                                                echo ' selected';
                                                                            } ?>>
                            <?php echo $row2['teacher_name']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
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