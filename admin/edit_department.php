<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>จัดการแผนก</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php
    include 'navbar.php';
    // include '../connect.php';

    $message = ""; // Initialize an empty message variable

    if (isset($_POST['save'])) {
        $teacher_ids = $_POST['dept_teacher_id']; // Retrieve the updated teacher IDs array

        foreach ($teacher_ids as $dept_id => $teacher_id) {
            if (empty($teacher_id)) {
                $sqlUpdate = "UPDATE department SET teacher_id = NULL WHERE dept_id = '$dept_id'";
            } else {
                $sqlUpdate = "UPDATE department SET teacher_id = '$teacher_id' WHERE dept_id = '$dept_id'";
            }
            $result = $con->query($sqlUpdate);

            if (!$result) {
                $message = "เกิดข้อผิดพลาดในการแก้ไข $dept_id" . mysqli_error($con); // Set the error message
                break; // Exit the loop if an error occurs
            }
        }

        $teacher_ids = $_POST['sup_teacher_id']; // Retrieve the updated teacher IDs array
        foreach ($teacher_ids as $dept_id => $teacher_id) {
            if (empty($teacher_id)) {
                $sqlUpdate = "UPDATE supervision SET teacher_id = NULL WHERE dept_id = '$dept_id'";
            } else {
                $sqlUpdate = "UPDATE supervision SET teacher_id = '$teacher_id' WHERE dept_id = '$dept_id'";
            }
            $result = $con->query($sqlUpdate);

            if (!$result) {
                $message = "เกิดข้อผิดพลาดในการแก้ไข $dept_id" . mysqli_error($con); // Set the error message
                break; // Exit the loop if an error occurs
            }
        }

        if (empty($message)) {
            echo "<script>alert('บันทึกการแก้ไขสำเร็จ')</script>";
            echo "<script>window.location.href='supervision.php?keyword='</script>";
        }
    }

    if (isset($_POST['delete'])) {
        $sqlSelect = "SELECT dept_id FROM supervision";
        $resultSelect = $con->query($sqlSelect);

        while ($row = mysqli_fetch_array($resultSelect)) {
            $dept_id = $row['dept_id'];

            $sqlUpdate = "UPDATE supervision SET teacher_id = NULL WHERE dept_id = '$dept_id'";
            $resultUpdate_sup = $con->query($sqlUpdate);

            $sqlUpdate = "UPDATE department SET teacher_id = NULL WHERE dept_id = '$dept_id'";
            $resultUpdate_dep = $con->query($sqlUpdate);
        }

        if (!$resultUpdate_sup) {
            echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูลหัวหน้าแผนก')</script>";
        }

        if (!$resultUpdate_sup) {
            echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูลครูนิเทศ')</script>";
        }

        if ($resultUpdate_sup && $resultUpdate_dep) {
            echo "<script>alert('อัปเดตรายการทั้งหมดเป็นค่า NULL แล้ว')</script>";
            echo "<script>window.location.href='department.php?keyword='</script>";
        }
    }

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
            LEFT OUTER JOIN teacher AS t ON t.teacher_id = department.teacher_id;
            ";
    $result = $con->query($sql);

    ?>
    <div class="container my-5">
        <div class="header">
            <h1>จัดการการเป็นครูนิเทศ</h1>
        </div>
        <?php
            //count null of dept teacher_id
            $sql_dept_null = "SELECT COUNT(*) as total FROM department WHERE teacher_id IS NULL";
            $result_dept_null = $con->query($sql_dept_null);
            $row_dept_null = mysqli_fetch_array($result_dept_null);
            $dept_total_null = $row_dept_null['total'];

            //count null of supervision teacher_id
            $sql_sup_null = "SELECT COUNT(*) as total FROM supervision WHERE teacher_id IS NULL";
            $result_sup_null = $con->query($sql_sup_null);
            $row_sup_null = mysqli_fetch_array($result_sup_null);
            $sup_total_null = $row_sup_null['total'];
            
            if (!empty($dept_total_null) && !empty($sup_total_null)) {
            ?>
                <div class="alert alert-danger p-2" role="alert">
                    <?php
                    echo '<i class="bi bi-exclamation-triangle-fill"></i> ยังไม่ได้กำหนดหัวหน้าแผนก จำนวน ' . $dept_total_null . ' รายการ และครูนิเทศ จำนวน ' . $sup_total_null . '  รายการ';
                    ?>
                </div>
            <?php
            }
        ?>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ลำดับที่</th>
                        <th>ชื่อแผนก</th>
                        <th>หัวหน้าแผนก</th>
                        <th>ครูนิเทศ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // Initialize counter variable
                    while ($row = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td><?php echo $counter++; ?></td> <!-- Display the counter value -->
                            <td><?php echo $row['dept_name']; ?></td>
                            <td>
                                <!-- หัวหน้าแผนก -->
                                <select name="dept_teacher_id[<?php echo $row['dept_id']; ?>]" class="form-select">
                                    <option value=""></option>
                                    <?php
                                    $sql = "SELECT teacher_id, CONCAT(name, ' ', surname) AS teacher_name FROM teacher ORDER BY teacher_name ASC;";
                                    $result2 = $con->query($sql);
                                    while ($row2 = mysqli_fetch_array($result2)) {
                                        $selected = ($row2['teacher_id'] == $row['dept_teacher_id']) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $row2['teacher_id']; ?>" <?php echo $selected; ?>>
                                            <?php echo $row2['teacher_name']; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <!-- ครูนิเทศ -->
                                <select name="sup_teacher_id[<?php echo $row['dept_id']; ?>]" class="form-select">
                                    <option value=""></option>
                                    <?php
                                    $sql = "SELECT teacher_id, CONCAT(name, ' ', surname) AS teacher_name FROM teacher ORDER BY teacher_name ASC;";
                                    $result2 = $con->query($sql);
                                    while ($row2 = mysqli_fetch_array($result2)) {
                                        $selected = ($row2['teacher_id'] == $row['sup_teacher_id']) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $row2['teacher_id']; ?>" <?php echo $selected; ?>>
                                            <?php echo $row2['teacher_name']; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php if (!empty($message)) { ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php } ?>
            <div class="row mb-3 gap-3 d-flex flex-row">
                <input class="btn btn-warning w-25" type="submit" name="save" value="บันทึกการแก้ไข">
                <input class="btn btn-danger w-25" type="submit" name="delete" value="ล้างรายการทั้งหมด" onclick="return confirm('คุณต้องกำหนดให้ทุกแผนกไม่มีครูนิเทศใช่หรือไม่?')">
            </div>
        </form>
        <?php

        ?>

    </div>
</body>

</html>