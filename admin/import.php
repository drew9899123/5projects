<?php

if (isset($_POST['import'])) {

    $filename = $_FILES['teacher_file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {
        $file = fopen($filename, "r");
        $sql = "create table tmp(
                    id int,
                    name varchar(100),
                    surname varchar(100)
                    )";
        $result = $con->query($sql);
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sqlInsert = "Insert into teacher values('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "')";
            $result = $con->query($sqlInsert);
        }
        if ($result) {
            echo "<script>alert('เพิ้มข้อมูล บุคลากร สำเร็จ')</script>";
            $sql = "DROP TABLE tmp";
            $result = $con->query($sql);
        } else {
            echo "insert Error!";
        }
    }

    $filename = $_FILES['file']['tmp_name'];

    //PROCESS 1
    if ($_FILES['file']['size'] > 0) {
        $file = fopen($filename, "r");
        fgetcsv($file); // Skip header row

        // Create tmp table for department data
        $sql = "CREATE TABLE tmp(
                    dept_name varchar(100),
                    field_name varchar(100)
                )";
        $result = $con->query($sql);

        // Insert department data into tmp table
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sqlInsert = "INSERT INTO tmp VALUES('" . $column[20] . "','" . $column[21] . "')";
            $result = $con->query($sqlInsert);
        }

        if ($result) {
            // Insert unique department names into department table
            $sql_dept_insert = "INSERT INTO department (dept_name) SELECT dept_name FROM tmp GROUP BY dept_name";
            $result_dept = $con->query($sql_dept_insert);
            
            $sql_sup_insert = "INSERT INTO supervision (dept_id) SELECT dept_id FROM department";
            $result_sup = $con->query($sql_sup_insert);

            if ($result_dept && $result_sup) {
                // Insert studyfield data into studyfield table
                $sqlInsert = "INSERT INTO studyfield (field_name, dept_id)
                                SELECT tmp.field_name, department.dept_id
                                FROM tmp
                                INNER JOIN department ON tmp.dept_name = department.dept_name
                                GROUP BY tmp.field_name";
                $result = $con->query($sqlInsert);

                if ($result) {
                    // Drop temporary table 'tmp' for department and studyfield
                    $sqlDrop = "DROP TABLE tmp";
                    $result = $con->query($sqlDrop);

                    // Proceed to the next process
                    // ...
                } else {
                    echo "Error inserting data into the studyfield table";
                }
            } else {
                echo "Error inserting data into the department table";
            }
        } else {
            echo "Error creating temporary table 'tmp' for department and studyfield";
        }
    }
    // END OF PROCESS 1    

    // PROCESS 2
    // After the completion of Process 1
    if ($result) {
        $file = fopen($filename, "r");
        fgetcsv($file); // Skip header row
        // Create tmp table for study_group data
        $sql = "CREATE TABLE tmp(
                group_id varchar(9) not null,
                group_name varchar(100) not null,
                teacher_name varchar(100) null
            )";
        $result = $con->query($sql);

        // Insert study_group data into tmp table
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sqlInsert = "INSERT INTO tmp VALUES('" . $column[17] . "','" . $column[18] . "','" . $column[36] . "')";
            $result = $con->query($sqlInsert);
        }

        if ($result) {
            // Insert study_group data into study_group table
            $sql = "INSERT INTO study_group(group_id, group_name, teacher_id)
                SELECT tmp.group_id, tmp.group_name, teacher.teacher_id
                FROM tmp
                LEFT JOIN teacher ON tmp.teacher_name = CONCAT(teacher.name, ' ', teacher.surname)
                GROUP BY tmp.group_id";
            $result = $con->query($sql);

            if ($result) {
                // Drop temporary table 'tmp' for study_group
                $sql = "DROP TABLE tmp";
                $result = $con->query($sql);

                // Proceed to the next process
                // ...
            } else {
                echo "Error inserting data into the study_group table";
            }
        } else {
            echo "Error creating temporary table 'tmp' for study_group";
        }
    }

    // END OF PROCESS 2

    // PROCESS 3
    // After the completion of Process 2
    if ($result) {
        // Create temporary table 'tmp' for student data
        $sql = "CREATE TABLE tmp(
                std_id varchar(11),
                password varchar(12),
                prefix_name varchar(10),
                name varchar(30),
                surname varchar(30),
                level varchar(100),
                field_name varchar(100),
                group_id varchar(9),
                system varchar(30),
                tel varchar(100),
                vac_id int,
                pat_id int,
                progression_id int
            )";
        $file = fopen($filename, "r");
        fgetcsv($file); // Skip header row
        $result = $con->query($sql);

        if ($result) {
            // Insert student data into temporary table 'tmp'
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                $sqlInsert = "INSERT INTO tmp VALUES('" . $column[1] . "','" . $column[14] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[19] . "','" . $column[21] . "','" . $column[17] . "', NULL,'" . $column[16] . "', NULL, NULL, 2)";
                $result = $con->query($sqlInsert);
            }

            if ($result) {
                // Insert data into the student table using temporary table 'tmp' and JOINs
                $sqlInsert = "INSERT INTO student
                            SELECT
                                tmp.std_id,
                                tmp.password,
                                tmp.prefix_name,
                                tmp.name,
                                tmp.surname,
                                tmp.level,
                                studyfield.field_id,
                                tmp.group_id,
                                CASE
                                    WHEN study_group.group_name LIKE '%(ทวิ)%' THEN 'ทวิภาคี'
                                    ELSE 'ปกติ'
                                END AS system,
                                REPLACE(tmp.tel, '\"', '') AS tel,
                                tmp.vac_id,
                                tmp.pat_id,
                                tmp.progression_id
                            FROM
                                tmp
                            INNER JOIN
                                studyfield ON tmp.field_name = studyfield.field_name
                            INNER JOIN
                                study_group ON study_group.group_id = tmp.group_id
                            WHERE
                                tmp.level != 'ประกาศนียบัตรวิชาชีพ (ปวช.) ชั้นปีที่ 1'";
                $result = $con->query($sqlInsert);

                if ($result) {
                    echo "<script>alert('เพิ่มข้อมูล แผนก, สาขางาน, นักเรียน, กลุ่มเรียน สำเร็จ')</script>";

                    // Drop temporary table 'tmp' for student
                    $sqlDrop = "DROP TABLE tmp";
                    $result = $con->query($sqlDrop);
                } else {
                    echo "Error inserting data into the student table";
                }
            } else {
                echo "Error inserting data into temporary table 'tmp' for student";
            }
        } else {
            echo "Error creating temporary table 'tmp' for student";
        }
    }
    // END OF PROCESS 3
}

if (isset($_POST['confirmDelete'])) {

    $psw = "1234";
    $postPsw = $_POST['password'];
    if ($postPsw == $psw) {
        $sqldelete = "DELETE FROM student;";
        $result = $con->query($sqldelete);
        $messages = ($result) ? "ลบข้อมูลนักศึกษาสำเร็จ" : "เกิดข้อผิดพลาดในการลบข้อมูลนักศึกษา!";
        echo "<script>alert('" . addslashes($messages) . "');</script>";

        $sqldelete = "DELETE FROM study_group;";
        $result = $con->query($sqldelete);
        $messages = ($result) ? "ลบข้อมูลกลุ่มการเรียนสำเร็จ" : "เกิดข้อผิดพลาดในการลบข้อมูลกลุ่มการเรียน!";
        echo "<script>alert('" . addslashes($messages) . "');</script>";

        $sqldelete = "DELETE FROM studyfield;";
        $result = $con->query($sqldelete);
        $messages = ($result) ? "ลบข้อมูลสาขาวิชาสำเร็จ" : "เกิดข้อผิดพลาดในการลบข้อมูลสาขาวิชา!";
        echo "<script>alert('" . addslashes($messages) . "');</script>";

        $sqldelete = "DELETE FROM department;";
        $result = $con->query($sqldelete);
        $messages = ($result) ? "ลบข้อมูลภาควิชาสำเร็จ" : "เกิดข้อผิดพลาดในการลบข้อมูลภาควิชา!";
        echo "<script>alert('" . addslashes($messages) . "');</script>";

        $sqldelete = "DELETE FROM teacher;";
        $result = $con->query($sqldelete);
        $messages = ($result) ? "ลบข้อมูลอาจารย์สำเร็จ" : "เกิดข้อผิดพลาดในการลบข้อมูลอาจารย์!";
        echo "<script>alert('" . addslashes($messages) . "');</script>";

        $sqldelete = "DROP TABLE tmp;";
        $result = $con->query($sqldelete);
        $messages = ($result) ? "ลบตาราง tmp สำเร็จ" : "เกิดข้อผิดพลาดในการลบตาราง tmp!";
        echo "<script>alert('" . addslashes($messages) . "');</script>";
    } else {
        echo "<script>alert('รหัสผ่านของคุณในยืนยันการล้างข้อมูลไม่ถูกต้อง')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php
    include 'navbar.php';
    // include '../connect.php';
    ?>
    <div class="container my-5">
        <div class="header">
            <h1>นำเข้าข้อมูล</h1>
        </div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" class="card mb-3">
            <div class="card-header">
                <b>นำเข้าข้อมูลหลัก</b>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>&#160
                        <div>
                            คำเตือนในการนำเข้าข้อมูล
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label"><i class="bi bi-people-fill"></i> ไฟล์บุคลากร</label>
                    <input class="form-control w-50" type="file" name="teacher_file" id="" accept=".csv" required>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label"><i class="bi bi-mortarboard-fill"></i> ไฟล์ข้อมูลนักศึกษา</label>
                    <input class="form-control w-50" type="file" name="file" id="" accept=".csv" required>
                </div>
            </div>
            <div class="card-footer">
                <input type="submit" value="นำเข้าข้อมูล" class="btn btn-success" name="import">
            </div>
        </form>

        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" class="card mb-3">
            <div class="card-header">
                <b>นำเข้าข้อมูลการเป็นครูนิเทศ</b>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>&#160
                        <div>
                            คำเตือนในการนำเข้าข้อมูล
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">ไฟล์การเป็นครูนิเทศ</label>
                    <input type="file" class="form-control" name="file" accept=".csv" required>
                </div>
            </div>
            <div class="card-footer">
                <input type="submit" value="นำเข้าข้อมูล" class="btn btn-success" name="import_supervision">
            </div>
        </form>

        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
            ล้างข้อมูลในระบบ
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">ยืนยันการล้างข้อมูล</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i>&#160
                                <div>
                                    หากล้างข้อมูลในระบบแล้ว จะไม่สามารถกู้คืนข้อมูลได้ ยืนยันการล้างข้อมูลด้วยการกรอกรหัสผ่านของคุณ
                                </div>
                            </div>

                            <label for="" class="form-label">รหัสผ่าน</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <input type="submit" name="confirmDelete" value="ยืนยัน" class="btn btn-danger"></input>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>