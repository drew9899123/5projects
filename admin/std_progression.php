<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>การดำเนินการของ นักเรียน นักศึกษา</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        #addRecord {
            visibility: hidden;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">

        <?php

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
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
                                    tel,
                                    student.progression_id,
                                    progression.progression_name,
                                    progression.color
                                FROM student
                                INNER JOIN studyfield ON student.field_id = studyfield.field_id
                                INNER JOIN study_group ON student.group_id = study_group.group_id
                                INNER JOIN progression ON student.progression_id = progression.progression_id
                                WHERE
                                    (std_id LIKE '%$keyword%'
                                    OR password LIKE '%$keyword%'
                                    OR prefix LIKE '%$keyword%'
                                    OR CONCAT_WS(' ', name, surname) LIKE '%$keyword%'
                                    OR CONCAT('(', SUBSTRING_INDEX(level, '(', -1)) LIKE '%$keyword%'
                                    OR study_group.group_name LIKE '%$keyword%'
                                    OR system LIKE '%$keyword%'
                                    OR tel LIKE '%$keyword%')
                                    AND student.progression_id <> 2
                                    ) AS subquery;
                                    
                                ";
            $search_sql = " SELECT
                                    std_id,
                                    password,
                                    prefix,
                                    CONCAT_WS(' ', name, surname) AS full_name,
                                    CONCAT('(', SUBSTRING_INDEX(level, '(', -1)) AS level,
                                    field_name,
                                    study_group.group_name,
                                    system,
                                    tel,
                                    student.progression_id,
                                    progression.progression_name,
                                    progression.color
                                FROM student
                                INNER JOIN studyfield ON student.field_id = studyfield.field_id
                                INNER JOIN study_group ON student.group_id = study_group.group_id
                                INNER JOIN progression ON student.progression_id = progression.progression_id
                                WHERE
                                    (
                                    std_id LIKE '%$keyword%'
                                    OR password LIKE '%$keyword%'
                                    OR prefix LIKE '%$keyword%'
                                    OR CONCAT_WS(' ', name, surname) LIKE '%$keyword%'
                                    OR CONCAT('(', SUBSTRING_INDEX(level, '(', -1)) LIKE '%$keyword%'
                                    OR study_group.group_name LIKE '%$keyword%'
                                    OR system LIKE '%$keyword%'
                                    OR tel LIKE '%$keyword%'
                                    )
                                    AND student.progression_id <> 2
                                ORDER BY $sortColumn $sortOrder
                                LIMIT $this_page_first_result, $results_per_page";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
        } else {
            //change these sql commands in different page
            $count_sql = "SELECT COUNT(*) AS total FROM student WHERE student.progression_id <> 2";
            $search_sql = "SELECT
                                    std_id,
                                    password,
                                    prefix,
                                    CONCAT_WS(' ', name, surname) AS full_name,
                                    CONCAT('(', SUBSTRING_INDEX(level, '(', -1)) AS level,
                                    field_name,
                                    group_name,
                                    system,
                                    tel,
                                    student.progression_id,
                                    progression.progression_name,
                                    progression.color
                                FROM student
                                INNER JOIN studyfield ON student.field_id = studyfield.field_id
                                INNER JOIN study_group ON student.group_id = study_group.group_id
                                INNER JOIN progression ON student.progression_id = progression.progression_id
                                WHERE
                                    student.progression_id <> 2
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
            if ($result = $con->query($sqlInsert)) {
                echo "<script>alert('เพิ่มข้อมูล " . $std_id . " สำเร็จ')</script>";
                echo "<script>window.location.href = window.location.href;</script>";
            } else {
                echo "<script>alert('ไม่สามารถเพิ่มรายการ " . $std_id . " ได้')</script>";
                // echo "<script>window.location.href = window.location.href;</script>";
            }
        }



        // determine number of total pages available
        $number_of_pages = ceil($number_of_results / $results_per_page);



        // Check if the "updateProgression" button is clicked
        if (isset($_POST['updateProgression'])) {
            // Retrieve the selected progression_id from the form
            $selectedProgressionId = $_POST['progression_id'];

            // Retrieve the selected std_id values from the session
            $selectedStdIds = isset($_SESSION['selectedStdIds']) ? $_SESSION['selectedStdIds'] : array();

            // Update the progression_id for each selected std_id in the database
            if (!empty($selectedStdIds)) {
                $selectedStdIdsString = implode("','", $selectedStdIds);
                $updateSql = "UPDATE student SET progression_id = '$selectedProgressionId' WHERE std_id IN ('$selectedStdIdsString')";

                if ($result = $con->query($updateSql)) {
                    echo "<script>alert('อัปเดตสถานะเรียบร้อยแล้ว')</script>";
                    unset($_SESSION['selectedStdIds']); // Clear the selectedStdIds session
                    echo "<script>window.location.href = window.location.href;</script>";
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ')</script>";
                }
            }
        }

        ?>


        <!-- change this header-->
        <div class="header">
            <h1>สถานะการดำเนินการของ นักเรียน นักศึกษา</h1>

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

        <?php
        //items per page & search bar
        include 'components/progression-toolbar.php';
        include 'components/toolbar.php';
        ?>

        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" class="form-check-input" id="headerCheckbox"></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=std_id&order=<?php echo ($sortColumn == 'std_id' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">รหัสนักศึกษา</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=prefix&order=<?php echo ($sortColumn == 'prefix' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">คำนำหน้า</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=full_name&order=<?php echo ($sortColumn == 'full_name' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">ชื่อ-สกุล</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=group_name&order=<?php echo ($sortColumn == 'group_name' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">ชื่อกลุ่ม</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=system&order=<?php echo ($sortColumn == 'system' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">ระบบ</a></th>
                    <th scope="col"><a href="?page=<?php echo $page; ?>&sort=progression_id&order=<?php echo ($sortColumn == 'progression_id' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&keyword=<?php echo $keyword; ?>">สถานะ</a></th>
                    <!-- Add more column headers with their respective sort links -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><input type="checkbox" class="bodyCheckbox form-check-input" value="<?php echo $row['std_id']; ?>"></td>
                        <td><?php echo $row['std_id']; ?></td>
                        <td><?php echo $row['prefix']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['group_name']; ?></td>
                        <td><?php echo $row['system']; ?></td>
                        <td class="d-flex align-items-center gap-2">
                            <!-- <div class="<?php echo $row['color']; ?>" style="height: 25px; aspect-ratio:1/1; border-radius: 50%;"></div> -->
                            <h5><span class="badge text-<?php echo $row['color']; ?>"><?php echo $row['progression_name'] ?></span></h5>

                            <!-- <select name="" id="" class="form-select" disabled>
                                <?php
                                $sql2 = "SELECT * FROM progression";
                                $result2 = $con->query($sql2);
                                while ($row2 = mysqli_fetch_array($result2)) {
                                    $selected = '';
                                    if ($row2['progression_id'] == $row['progression_id']) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $row2['progression_id'] ?>" <?php echo $selected ?>>
                                        <?php echo $row2['progression_name'] ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select> -->
                        </td>
                        <!-- Add more table cells for additional columns -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php include 'components/pagination.php'; ?>
        
    </div>
    <?php include 'components/bottom-floating.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- each checkbox value store in session -->
    <script>
        $(document).ready(function() {
            // Retrieve the selected std_id values from the session
            <?php
            $selectedStdIds = isset($_SESSION['selectedStdIds']) ? $_SESSION['selectedStdIds'] : array();
            $selectedStdIdsJson = json_encode($selectedStdIds);
            ?>

            var selectedStdIds = <?php echo $selectedStdIdsJson; ?>;

            // Pre-check the checkboxes for the selected std_id values
            selectedStdIds.forEach(function(stdId) {
                $('.bodyCheckbox[value="' + stdId + '"]').prop('checked', true);
            });
        });
    </script>

    <!-- checkall uncheckall and update session -->
    <script>
        $(document).ready(function() {
            // Array to store selected std_id values
            let selectedStdIds = [];

            // Function to update the selectedStdIds array and session data
            function updateSelectedIds() {
                selectedStdIds = [];
                $(".bodyCheckbox:checked").each(function() {
                    selectedStdIds.push($(this).val());
                });
                // Update the session with selectedStdIds
                $.ajax({
                    type: "POST",
                    url: "update_session.php",
                    data: {
                        selectedIds: selectedStdIds
                    },
                    success: function(response) {
                        // Session updated
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

            // Function to clear the selectedStdIds session
            function clearSelectedIdsSession() {
                // Clear the selectedStdIds session
                $.ajax({
                    type: "POST",
                    url: "clear_session.php",
                    success: function(response) {
                        // Session cleared
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

            // Show Selected button click event
            $("#showSelectedBtn").click(function() {
                // Update the selectedStdIds array and session before showing the modal
                updateSelectedIds();

                // Update the modal with selected std_id values
                let selectedStdIdsHtml = "";
                selectedStdIds.forEach(function(id) {
                    selectedStdIdsHtml += "<p>" + id + "</p>";
                });
                $("#selectedStdIds").html(selectedStdIdsHtml);
            });

            // Checkbox change event
            $(".bodyCheckbox").change(function() {
                // Update the selectedStdIds array and session when checkboxes are checked/unchecked
                updateSelectedIds();

                // Check if all checkboxes are unchecked
                if ($(".bodyCheckbox:checked").length === 0) {
                    // Clear the selectedStdIds session
                    clearSelectedIdsSession();
                }
            });

            // Header checkbox change event
            $("#headerCheckbox").change(function() {
                let isChecked = $(this).is(":checked");
                $(".bodyCheckbox").prop("checked", isChecked);

                // Update the selectedStdIds array and session when header checkbox is checked/unchecked
                updateSelectedIds();

                if (!isChecked) {
                    // Uncheck all checked body checkboxes if the header checkbox is unchecked
                    $(".bodyCheckbox:checked").each(function() {
                        $(this).prop("checked", false);
                        let stdId = $(this).val();
                        let index = selectedStdIds.indexOf(stdId);
                        if (index > -1) {
                            selectedStdIds.splice(index, 1);
                        }
                    });

                    // Clear the selectedStdIds session
                    clearSelectedIdsSession();
                }
            });
        });
    </script>


    <script src="script.js"></script>
</body>

</html>