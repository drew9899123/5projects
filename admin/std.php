<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>นักเรียน นักศึกษา</title>
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
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'std_id';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        //check if has searching...
        if (isset($_GET['keyword'])) {
            $_SESSION['keyword'] = $_GET['keyword'];
            $keyword = $_SESSION['keyword'];
            //change these sql commands in different page
            $count_sql = "SELECT COUNT(*) AS total FROM 
                            (SELECT
                                std_id,
                                password,
                                prefix,
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
                              std_id LIKE '%$keyword%'
                              OR password LIKE '%$keyword%'
                              OR prefix LIKE '%$keyword%'
                              OR CONCAT_WS(' ', name, surname) LIKE '%$keyword%'
                              OR CONCAT('(', SUBSTRING_INDEX(level, '(', -1)) LIKE '%$keyword%'
                              OR study_group.group_name LIKE '%$keyword%'
                              OR system LIKE '%$keyword%'
                              OR tel LIKE '%$keyword%'
                            ) AS subquery;
                            ";
            $search_sql = "SELECT
                              std_id,
                              password,
                              prefix,
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
                              std_id LIKE '%$keyword%'
                              OR password LIKE '%$keyword%'
                              OR prefix LIKE '%$keyword%'
                              OR CONCAT_WS(' ', name, surname) LIKE '%$keyword%'
                              OR CONCAT('(', SUBSTRING_INDEX(level, '(', -1)) LIKE '%$keyword%'
                              OR study_group.group_name LIKE '%$keyword%'
                              OR system LIKE '%$keyword%'
                              OR tel LIKE '%$keyword%'
                          ORDER BY $sortColumn $sortOrder
                          LIMIT $this_page_first_result, $results_per_page";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
        } else {
            //change these sql commands in different page
            $count_sql = "SELECT COUNT(*) AS total FROM student";
            $search_sql = "SELECT
                              std_id,
                              password,
                              prefix,
                              CONCAT_WS(' ', name, surname) AS full_name,
                              CONCAT('(', SUBSTRING_INDEX(level, '(', -1)) AS level,
                              field_name,
                              group_name,
                              system,
                              tel
                          FROM student
                          INNER JOIN studyfield ON student.field_id = studyfield.field_id
                          INNER JOIN study_group ON student.group_id = study_group.group_id
                          ORDER BY $sortColumn $sortOrder
                          LIMIT $this_page_first_result, $results_per_page";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
        }

        if (isset($_POST['add'])) {
            $std_id = $_POST['std_id'];
            $password = $_POST['password'];
            $prefix = $_POST['prefix'];
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $level = $_POST['level'];
            $field_id = $_POST['field_id'];
            $group_id = $_POST['group_id'];
            $system = $_POST['system'];
            $tel = $_POST['tel'];
            $sqlInsert = "INSERT INTO student
                            VALUES(
                                '$std_id',
                                '$password',
                                '$prefix',
                                '$name',
                                '$surname',
                                '$level',
                                '$field_id',
                                '$group_id',
                                '$system',
                                '$tel',
                                NULL,
                                NULL,
                                NULL
                            )";
            if($result=$con->query($sqlInsert)){
                echo "<script>alert('เพิ่มข้อมูล ". $std_id ." สำเร็จ')</script>";
                echo "<script>window.location.href = window.location.href;</script>";
            }else{
                echo "<script>alert('ไม่สามารถเพิ่มรายการ ". $std_id ." ได้')</script>";
                // echo "<script>window.location.href = window.location.href;</script>";
            }
        }

        // determine number of total pages available
        $number_of_pages = ceil($number_of_results / $results_per_page);
        ?>

        <!-- change this header-->
        <div class="header">
            <h1>นักเรียน นักศึกษา</h1>

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
                            <div class="row mb-3">
                                <!-- รหัสนักศึกษา -->
                                <div class="col-sm">
                                    <label for="" class="form-label">รหัสนักศึกษา</label>
                                    <input type="text" name="std_id" id="" class="form-control" maxlength="11" required>
                                </div>
                                <!-- รหัสผ่าน -->
                                <div class="col-sm">
                                    <label for="" class="form-label">รหัสผ่าน</label>
                                    <input type="text" name="password" id="" class="form-control" maxlength="12" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <!-- คำนำหน้า -->
                                <div class="col-sm-2">
                                    <label for="" class="form-label">คำนำหน้า</label>
                                    <select name="prefix" id="" class="form-select" required>
                                        <option value="">เลือก...</option>
                                        <?php include 'libary/prefix_selection.php' ?>
                                    </select>
                                </div>
                                <!-- ชื่อ -->
                                <div class="col-sm-5">
                                    <label for="" class="form-label">ชื่อ</label>
                                    <input type="text" name="name" id="" class="form-control" required>
                                </div>
                                <!-- สกุล -->
                                <div class="col-sm-5">
                                    <label for="" class="form-label">สกุล</label>
                                    <input type="text" name="surname" id="" class="form-control" required>
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
                                    <input type="tel" class="form-control" name="tel" id="" required>
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

        <?php
        //items per page & search bar
        include 'components/toolbar.php';
        ?>

        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" class="form-check-input" id="headerCheckbox"></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=std_id&order=<?php echo ($sortColumn == 'std_id' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">รหัสนักศึกษา</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=password&order=<?php echo ($sortColumn == 'password' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">รหัสผ่าน</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=prefix&order=<?php echo ($sortColumn == 'prefix' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">คำนำหน้า</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=full_name&order=<?php echo ($sortColumn == 'full_name' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">ชื่อ-สกุล</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=group_name&order=<?php echo ($sortColumn == 'group_name' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">ชื่อกลุ่ม</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=system&order=<?php echo ($sortColumn == 'system' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">ระบบ</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=tel&order=<?php echo ($sortColumn == 'tel' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">โทร</a></th>
                    <th scope="col">จัดการ</th>
                    <!-- Add more column headers with their respective sort links -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><input type="checkbox" class="bodyCheckbox form-check-input"></td>
                        <td><?php echo $row['std_id']; ?></td>
                        <td><?php echo $row['password']; ?></td>
                        <td><?php echo $row['prefix']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['group_name']; ?></td>
                        <td><?php echo $row['system']; ?></td>
                        <td><?php echo $row['tel']; ?></td>
                        <td class="d-flex justify-content-center">
                            <a href="edit_std.php?std_id=<?php echo $row['std_id']; ?>" type="submit" class="btn btn-warning"><i class="bi bi-pen-fill"></i></a>
                        </td>
                        <!-- Add more table cells for additional columns -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php include 'components/pagination.php'; ?>
        <?php include 'components/bottom-floating.php'; ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>

</html>