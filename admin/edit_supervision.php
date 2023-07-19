<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>จัดการการเป็นครูนิเทศ</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php
    include 'navbar.php';
    // include '../connect.php';

    //count null of teacher_id
    $sql_null = "SELECT COUNT(*) as total FROM supervision WHERE teacher_id IS NULL";
    $result_null = $con->query($sql_null);
    $row_null = mysqli_fetch_array($result_null);
    $total_null = $row_null['total'];

    //count all rows
    $count_sql = "SELECT COUNT(*) AS total FROM supervision";
    $count_result = mysqli_query($con, $count_sql);
    $count_row = mysqli_fetch_assoc($count_result);
    $number_of_results = $count_row['total'];

    $message = ""; // Initialize an empty message variable

    if (isset($_POST['save'])) {
        $teacher_ids = $_POST['teacher_id']; // Retrieve the updated teacher IDs array

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
            $resultUpdate = $con->query($sqlUpdate);

            if (!$resultUpdate) {
                echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดต $dept_id')</script>";
                break;
            }
        }

        if ($resultUpdate) {
            echo "<script>alert('อัปเดตรายการทั้งหมดเป็นค่า NULL แล้ว')</script>";
            echo "<script>window.location.href='supervision.php?keyword='</script>";
        }
    }

    $sql = "SELECT 
                department.dept_id,
                department.dept_name, 
                CONCAT_WS(' ', teacher.name, teacher.surname) AS teacher_fullname,
                supervision.teacher_id
            FROM department
            LEFT OUTER JOIN supervision ON department.dept_id = supervision.dept_id
            LEFT OUTER JOIN teacher ON teacher.teacher_id = supervision.teacher_id;
            ";
    $result = $con->query($sql);

    ?>
    <div class="container my-5">
        <div class="header">
            <h1>จัดการการเป็นครูนิเทศ</h1>
        </div>
        <?php
        if (!empty($total_null)) {
        ?>
            <div class="alert alert-danger p-2" role="alert">
                <?php
                echo '<i class="bi bi-exclamation-triangle-fill"></i> ยังไม่ได้กำหนดครูนิเทศจำนวน ' . $row_null['total'] . '  รายการ จากทั้งหมด ' . $number_of_results . ' รายการ';
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
                                <select name="teacher_id[<?php echo $row['dept_id']; ?>]" class="form-select">
                                    <option value="">ไม่มีครูนิเทศ</option>
                                    <?php
                                    $sql = "SELECT teacher_id, CONCAT(name, ' ', surname) AS teacher_name FROM teacher ORDER BY teacher_name ASC;";
                                    $result2 = $con->query($sql);
                                    while ($row2 = mysqli_fetch_array($result2)) {
                                        $selected = ($row2['teacher_id'] == $row['teacher_id']) ? 'selected' : '';
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