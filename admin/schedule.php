<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>ตารางกำหนดการ</title>
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
        include 'libary/convertToThaiDate.php';

        // determine which page number visitor is currently on
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        // determine the sql LIMIT starting number for the results on the displaying page
        $this_page_first_result = ($page - 1) * $results_per_page;

        // retrieve selected results from the database and display them on the page
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'term_year';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        //check if has searching...
        if (isset($_GET['keyword'])) {
            $_SESSION['keyword'] = $_GET['keyword'];
            $keyword = $_SESSION['keyword'];
            //change these sql commands in different page
            $count_sql = "SELECT COUNT(*) AS total FROM schedule 
                            WHERE term_year LIKE '%$keyword%' 
                            OR start_date LIKE '%$keyword%' 
                            OR finish_date LIKE '%$keyword%' 
                            OR doc_return_date LIKE '%$keyword%'
                            ";
            $search_sql = "SELECT * FROM schedule 
                            WHERE term_year LIKE '%$keyword%' 
                            OR start_date LIKE '%$keyword%' 
                            OR finish_date LIKE '%$keyword%' 
                            OR doc_return_date LIKE '%$keyword%'
                          ORDER BY 
                            $sortColumn $sortOrder
                          LIMIT 
                            $this_page_first_result, $results_per_page";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
            if (!$result) {
                echo 'error!';
            }
        } else {
            //change these sql commands in different page
            $count_sql = "SELECT COUNT(*) AS total FROM schedule";
            $search_sql = "SELECT * FROM schedule
                          ORDER BY $sortColumn $sortOrder
                          LIMIT $this_page_first_result, $results_per_page";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
        }

        if (isset($_POST['add'])) {
            echo $term_year = $_POST['term'] . '/' . $_POST['year'];
            echo $start_date = $_POST['start_date'];
            echo $finish_date = $_POST['finish_date'];
            echo $doc_return_date = $_POST['doc_return_date'];
            $sqlInsert = "INSERT INTO schedule
                            VALUES(
                                null
                                '$term_year',
                                '$start_date',
                                '$finish_date',
                                '$doc_return_date'
                            )";
            if ($result = $con->query($sqlInsert)) {
                echo "<script>alert('เพิ่มข้อมูล " . $term_year . " สำเร็จ')</script>";
                echo "<script>window.location.href = window.location.href;</script>";
            } else {
                echo "<script>alert('ไม่สามารถเพิ่มรายการ " . $term_year . " ได้')</script>";
                // echo "<script>window.location.href = window.location.href;</script>";
            }
        }

        // determine number of total pages available
        $number_of_pages = ceil($number_of_results / $results_per_page);
        ?>

        <!-- change this header-->
        <div class="header">
            <h1>ตารางกำหนดการฝึกงาน</h1>

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
                                <!-- เทอม -->
                                <div class="col-sm-2">
                                    <label for="" class="form-label">เทอม</label>
                                    <input type="number" name="term" id="" class="form-control" required>
                                </div>
                                <!-- ปีการศึกษา -->
                                <div class="col-sm-2">
                                    <label for="" class="form-label">ปีการศึกษา</label>
                                    <input type="number" name="year" id="" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <!-- วันที่เริ่มฝึกงาน -->
                                <div class="col-sm">
                                    <label for="" class="form-label">วันที่เริ่มฝึกงาน</label>
                                    <input type="date" name="start_date" id="" class="form-control" required>
                                </div>
                                <!-- วันที่จบฝึกงาน -->
                                <div class="col-sm">
                                    <label for="" class="form-label">วันที่จบฝึกงาน</label>
                                    <input type="date" name="finish_date" id="" class="form-control" required>
                                </div>
                                <!-- วันที่ต้องส่งเอกสาร -->
                                <div class="col-sm">
                                    <label for="" class="form-label">วันที่สถานประกอบการต้องส่งคืนเอกสาร</label>
                                    <input type="date" name="doc_return_date" id="" class="form-control" required>
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
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=term_year&order=<?php echo ($sortColumn == 'term_year' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">เทอม/ปีการศึกษา</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=start_date&order=<?php echo ($sortColumn == 'start_date' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">วันที่เริ่มฝึกงาน</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=finish_date&order=<?php echo ($sortColumn == 'finish_date' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">วันที่จบฝึกงาน</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=doc_return_date&order=<?php echo ($sortColumn == 'doc_return_date' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">ส่งเอกสารคืนภายในวันที่</a></th>
                    <th scope="col">จัดการ</th>
                    <!-- Add more column headers with their respective sort links -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><input type="checkbox" class="bodyCheckbox form-check-input"></td>
                        <td><?php echo $row['term_year']; ?></td>
                        <td><?php echo convertToThaiDate($row['start_date']); ?></td>
                        <td><?php echo convertToThaiDate($row['finish_date']); ?></td>
                        <td><?php echo convertToThaiDate($row['doc_return_date']); ?></td>
                        <td class="d-flex justify-content-center">
                            <a href="edit_std.php?std_id=<?php echo $row['std_id']; ?>" type="submit" class="btn btn-warning"><i class="bi bi-pen-fill"></i></a>
                        </td>
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