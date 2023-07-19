<?php
include 'connect.php';

if (isset($_POST['drop'])) {
    $sql1 = "DELETE FROM studyfield;";
    $sql2 = "DELETE FROM department;";
    $sql3 = "DELETE FROM student;";

    if ($con->query($sql1) && $con->query($sql2) && $con->query($sql3)) {
        // Success
    } else {
        echo "Delete error!";
    }
}


if (isset($_POST['import'])) {
    $filename = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {

        $file = fopen($filename, "r");
        fgetcsv($file); //ไม่เอาหัวคอลัมน์
        $sql = "create table tmp(
                    dept_name varchar(100),
                    field_name varchar(100)
                    )";
        $result = $con->query($sql);
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sqlInsert = "INSERT INTO tmp values('" . $column[20] . "','" . $column[21] . "')";
            $result = $con->query($sqlInsert);
        }
        if ($result) {
            $sqlInsert = "INSERT INTO department (dept_name) SELECT dept_name from tmp GROUP BY dept_name";
            $result = $con->query($sqlInsert);
            if ($result) {
                $sqlInsert = "INSERT INTO studyfield (field_name,dept_id) SELECT tmp.field_name, department.dep_id FROM tmp INNER JOIN department on tmp.dept_name = department.dept_name GROUP BY tmp.field_name;";
                $result = $con->query($sqlInsert);
                if ($result) {
                    $sqlDrop = "DROP TABLE tmp;";
                    $result = $con->query($sqlDrop);
                    if ($result) {
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
                        fgetcsv($file); //ไม่เอาหัวคอลัมน์
                        $result = $con->query($sql);
                        if ($result) {
                            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                                $sqlInsert = "INSERT INTO tmp values('" . $column[1] . "','" . $column[14] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[19] . "','" . $column[21] . "','" . $column[17] . "', NULL,'" . $column[16] . "', NULL, NULL, 2)";
                                $result = $con->query($sqlInsert);
                            }
                            if ($result) {
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
                                    tmp.level != 'ประกาศนียบัตรวิชาชีพ (ปวช.) ชั้นปีที่ 1';
                                ";
                                $result = $con->query($sqlInsert);
                                if ($result) {
                                    echo "นำเข้าข้อมูล field, department, student สำเร็จ";
                                    $sqlDrop = "DROP TABLE tmp";
                                    $result=$con->query($sqlDrop);
                                } else {
                                    echo "Insert std error!";
                                    $error_message = mysqli_error($con);
                                    echo "SQL Error: " . $error_message;
                                }
                            } else {
                                echo "insert into tmp error!";
                            }
                        } else {
                            echo "tmp for std error!";
                        }
                    } else {
                        echo "Drop tmp error!";
                    }
                } else {
                    echo "insert studyfield error!";
                }
            } else {
                echo "insert dept error!";
            }
        } else {
            echo "tmp Error!";
        }
    }

    // $sqlDrop = "DROP TABLE tmp;";
    // $result = $con->query($sqlDrop);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="control">
                <label for="" class="form-label">เลือกไฟล์</label>
                <input type="file" class="form-control" name="file" accept=".csv">
            </div>
            <div class="control">
                <input type="submit" value="Import" class="btn btn-success" name="import">
                <input type="submit" value="Clear" class="btn btn-danger" name="drop">
            </div>
        </form>
    </div>
</body>

</html>