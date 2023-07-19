<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>แผนกวิชาและหน่วยงาน</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">

        <?php
        // define how many results you want per page
        $results_per_page = isset($_GET['results_per_page']) ? $_GET['results_per_page'] : 10;

        // connect to the database
        // include '../connect.php';

        // determine which page number visitor is currently on
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        // determine the sql LIMIT starting number for the results on the displaying page
        $this_page_first_result = ($page - 1) * $results_per_page;

        // retrieve selected results from the database and display them on the page
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'dept_id'; //change this in different pages 
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        //check if has searching...
        if (isset($_GET['keyword'])) {
            $_SESSION['keyword'] = $_GET['keyword'];
            $keyword = $_SESSION['keyword'];
            //change these sql commands in different page
            $count_sql = "  SELECT COUNT(*) AS total FROM 
                                (SELECT
                                    department.dept_id,
                                    department.dept_name,
                                    department.teacher_id,
                                    CONCAT_WS(' ', name, surname) AS teacher_fullname
                                FROM department
                                LEFT OUTER JOIN teacher
                                    ON department.teacher_id = teacher.teacher_id
                                WHERE 
                                    department.dept_id LIKE '%$keyword%' OR
                                    department.dept_name LIKE '%$keyword%' OR
                                    CONCAT_WS(' ', name, surname) LIKE '%$keyword%'
                                ) AS subquery;
                            ";
            $search_sql = " SELECT
                                department.dept_id,
                                department.dept_name,
                                department.teacher_id,
                                CONCAT_WS(' ', name, surname) AS teacher_fullname
                            FROM department
                            LEFT OUTER JOIN teacher
                                ON department.teacher_id = teacher.teacher_id
                            WHERE 
                                department.dept_id LIKE '%$keyword%' OR
                                department.dept_name LIKE '%$keyword%' OR
                                CONCAT_WS(' ', name, surname) LIKE '%$keyword%'
                            ORDER BY 
                                $sortColumn $sortOrder
                            LIMIT 
                                $this_page_first_result, $results_per_page
                            ";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
        } else {
            //change these sql commands in different page
            $count_sql = "SELECT COUNT(*) AS total FROM department";
            $search_sql = " SELECT
                                department.dept_id,
                                department.dept_name,
                                department.teacher_id,
                                CONCAT_WS(' ', name, surname) AS teacher_fullname
                            FROM department
                            LEFT OUTER JOIN teacher
                                ON department.teacher_id = teacher.teacher_id
                            ORDER BY $sortColumn $sortOrder
                            LIMIT $this_page_first_result, $results_per_page";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
        }

        //if add record
        if (isset($_POST['add'])) {
            'dept : ' . $dept_name = $_POST['dept_name'];
            'teacher : ' . $teacher_id = $_POST['teacher_id'];
            if(($teacher_id = $_POST['teacher_id'])==NULL){
                $sql_insert = "INSERT INTO department (dept_name, teacher_id) VALUES ('$dept_name', NULL)";
            }else{
                $sql_insert = "INSERT INTO department (dept_name, teacher_id) VALUES ('$dept_name', '$teacher_id')";
            }
            // $sql_insert = "INSERT INTO department (dept_name, teacher_id) VALUES ('$dept_name', '$teacher_id')";
            $result_insert = $con->query($sql_insert);

            if ($result_insert) {
                echo "<script>alert('เพิ่มข้อมูล แผนก " . $dept_name . " สำเร็จ')</script>";
                echo "<script>window.location.href = window.location.href;</script>";
            } else {
                echo "<script>alert('ไม่สามารถเพิ่ม แผนก " . $dept_name . "')</script>";
                echo "<script>window.location.href = window.location.href;</script>"; // Add this line to redirect after displaying the error message
            }
        }



        // determine number of total pages available
        $number_of_pages = ceil($number_of_results / $results_per_page);
        ?>

        <!-- change this header-->
        <div class="header">
            <h1>แผนกวิชาและหน่วยงาน</h1>
        </div>

        <!-- MODAL -->
        <div class="modal fade position-fixed modal-xl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มรายการ</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- END OF FORM -->
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="" class="form-label">ชื่อแผนก</label>
                                    <input type="text" name="dept_name" id="" class="form-control" required>
                                </div>
                                <div class="col-sm">
                                    <label for="" class="form-label">หัวหน้าแผนก</label>
                                    <select name="teacher_id" id="" class="form-select">
                                        <option value="">ไม่มีหัวหน้าแผนก</option>
                                        <?php
                                        include 'libary/teacher_selection.php';
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <input type="submit" value="เพิ่ม" class="btn btn-success" name="add">
                        </div>
                    </form>
                    <!-- END OF FORM -->
                </div>
            </div>
        </div>
        <!-- END OF MODAL -->

        <!-- SEARCH RESULT -->
        <?php
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
        ?>
            <div class="alert alert-success p-2" role="alert">
                <?php
                echo '<i class="bi bi-check-circle-fill"></i> ' . $_GET['keyword'] . ' (' . $number_of_results . '  รายการ)';
                ?>
            </div>
        <?php
        }
        ?>
        <?php
        //items per page & search bar
        include 'components/toolbar.php';

        //change these lines in different page
        echo '<table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" class="form-check-input" id="headerCheckbox"></th>
                        <th scope="col"><a href="?page=' . $page . '&sort=dept_id&order=' . ($sortColumn == 'dept_id' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '&keyword=' . $keyword . '">รหัส</a></th>
                        <th scope="col"><a href="?page=' . $page . '&sort=dept_name&order=' . ($sortColumn == 'dept_name' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '&keyword=' . $keyword . '">ชื่อแผนก</a></th>
                        <th scope="col"><a href="?page=' . $page . '&sort=teacher_fullname&order=' . ($sortColumn == 'teacher_fullname' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '&keyword=' . $keyword . '">หัวหน้าแผนก</a></th>
                        <th scope="col"><a href="#">จัดการ</a></th>
                        <!-- Add more column headers with their respective sort links -->
                    </tr>
                </thead>
                <tbody>';

        while ($row = mysqli_fetch_array($result)) {
            //change these line in different page
            echo '<tr>
                    <td><input type="checkbox" class="bodyCheckbox form-check-input"></td>
                    <td>' . $row['dept_id'] . '</td>
                    <td>' . $row['dept_name'] . '</td>
                    <td>' . $row['teacher_fullname'] . '</td>
                    <td class="d-flex justify-content-center">
                        <a href="edit_dept.php?dept_id=' . $row['dept_id'] . '" type="submit" class="btn btn-warning"><i class="bi bi-pen-fill"></i></a>
                    </td>
                    <!-- Add more table cells for additional columns -->
                </tr>';
        }

        echo '</tbody>
            </table>';

        include 'components/pagination.php';
        include 'components/bottom-floating.php';

        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>

</html>