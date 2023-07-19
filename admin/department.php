<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>การเป็นครูนิเทศ</title>
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
                                    supervision.dept_id,
                                    department.dept_name, 
                                    CONCAT_WS(' ', teacher.name, teacher.surname) AS sup_teacher_fullname,
                                    CONCAT_WS(' ', t.name, t.surname) AS dept_teacher_fullname
                                FROM supervision
                                LEFT OUTER JOIN department ON department.dept_id = supervision.dept_id
                                LEFT OUTER JOIN teacher ON teacher.teacher_id = supervision.teacher_id
                                LEFT OUTER JOIN teacher as t ON t.teacher_id = department.teacher_id
                                WHERE
                                    department.dept_name LIKE '%$keyword%'
                                    OR CONCAT_WS(' ', teacher.name, teacher.surname) LIKE '%$keyword%'
                                    OR CONCAT_WS(' ', t.name, t.surname) LIKE '%$keyword%'
                                ) AS subquery;
                            ";
            $search_sql = " SELECT 
                                supervision.dept_id,
                                department.dept_name, 
                                CONCAT_WS(' ', teacher.name, teacher.surname) AS sup_teacher_fullname,
                                CONCAT_WS(' ', t.name, t.surname) AS dept_teacher_fullname
                            FROM supervision
                            LEFT OUTER JOIN department ON department.dept_id = supervision.dept_id
                            LEFT OUTER JOIN teacher ON teacher.teacher_id = supervision.teacher_id
                            LEFT OUTER JOIN teacher as t ON t.teacher_id = department.teacher_id
                            WHERE
                                department.dept_name LIKE '%$keyword%'
                                OR CONCAT_WS(' ', teacher.name, teacher.surname) LIKE '%$keyword%'
                                OR CONCAT_WS(' ', t.name, t.surname) LIKE '%$keyword%'
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
                                supervision.dept_id,
                                department.dept_name, 
                                CONCAT_WS(' ', teacher.name, teacher.surname) AS sup_teacher_fullname,
                                CONCAT_WS(' ', t.name, t.surname) AS dept_teacher_fullname
                            FROM supervision
                            LEFT OUTER JOIN department ON department.dept_id = supervision.dept_id
                            LEFT OUTER JOIN teacher ON teacher.teacher_id = supervision.teacher_id
                            LEFT OUTER JOIN teacher as t ON t.teacher_id = department.teacher_id
                            ORDER BY $sortColumn $sortOrder
                            LIMIT $this_page_first_result, $results_per_page";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
        }

        //if add record
        if (isset($_POST['add'])) {
            $dept_name = $_POST['dept_name'];
            $dept_teacher_id = $_POST['dept_teacher_id'];
            $sup_teacher_id = $_POST['sup_teacher_id'];

            $sql_getId = "SELECT MAX(dept_id)+1 as new_dept_id from department";
            $result_getId = $con->query($sql_getId);
            $row_getId = mysqli_fetch_array($result_getId);
            $dept_id = $row_getId['new_dept_id'];

            if ($dept_teacher_id == NULL) {
                $sql_dept_insert = "INSERT INTO department VALUES ('$dept_id', '$dept_name', NULL)";
            } else {
                $sql_dept_insert = "INSERT INTO department VALUES ('$dept_id','$dept_name', '$dept_teacher_id')";
            }
            $result_dept_insert = $con->query($sql_dept_insert);

            if ($sup_teacher_id == NULL) {
                $sql_sup_insert = "INSERT INTO supervision VALUES ('$dept_id', NULL)";
            } else {
                $sql_sup_insert = "INSERT INTO supervision VALUES ('$dept_id', '$sup_teacher_id')";
            }
            $result_sup_insert = $con->query($sql_sup_insert);

            if (!$result_dept_insert && !$result_sup_insert) {
                echo "<script>alert('ไม่สามารถเพิ่ม แผนก " . $dept_name . " ได้สำเร็จ')</script>";
                // echo "<script>window.location.href = window.location.href;</script>";
            } 
            else {
                echo "<script>alert('เพิ่มข้อมูล แผนก " . $dept_name . " สำเร็จ')</script>";
                echo "<script>window.location.href = window.location.href;</script>"; // Add this line to redirect after displaying the error message
            }
        }

        // determine number of total pages available
        $number_of_pages = ceil($number_of_results / $results_per_page);
        ?>

        <!-- change this header-->
        <div class="header">
            <h1>แผนก หัวหน้าแผนก และครูนิเทศ</h1>
        </div>

        <!-- MODAL -->
        <div class="modal fade position-fixed modal-xl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มรายการ</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- FORM -->
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="" class="form-label">ชื่อแผนก</label>
                                    <input type="text" name="dept_name" id="" class="form-control" required>
                                </div>
                                <div class="col-sm">
                                    <label for="" class="form-label">หัวหน้าแผนก</label>
                                    <select name="dept_teacher_id" id="" class="form-select">
                                        <option value="">ไม่มีหัวหน้าแผนก</option>
                                        <?php
                                        include 'libary/teacher_selection.php';
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <label for="" class="form-label">ครูนิเทศ</label>
                                    <select name="sup_teacher_id" id="" class="form-select">
                                        <option value="">ไม่มีครูนิเทศ</option>
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

        <!-- top toolbar of supervision -->
        <div class="d-flex gap-3 ">
            <div>
                <a href="edit_department.php" class="btn btn-primary p-2">จัดการหัวหน้าแผนกและครูนิเทศ</a>
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
        </div>
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
                        <th scope="col"><a href="?page=' . $page . '&sort=dept_teacher_fullname&order=' . ($sortColumn == 'dept_teacher_fullname' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '&keyword=' . $keyword . '">หัวหน้าแผนก</a></th>
                        <th scope="col"><a href="?page=' . $page . '&sort=sup_teacher_fullname&order=' . ($sortColumn == 'sup_teacher_fullname' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '&keyword=' . $keyword . '">ครูนิเทศ</a></th>
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
                    <td>' . $row['dept_teacher_fullname'] . '</td>
                    <td>' . $row['sup_teacher_fullname'] . '</td>
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