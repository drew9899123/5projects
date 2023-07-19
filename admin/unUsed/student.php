<?php
include '../connect.php';

if (isset($_POST['addRecord'])) {
    $group_id = $_POST['group_id'];
    $group_name = $_POST['group_name'];
    $teacher_id = $_POST['teacher_id'];

    // Check if $group_id already exists
    $checkQuery = "SELECT * FROM study_group WHERE group_id = '$group_id'";
    $checkResult = $con->query($checkQuery);

    if ($checkResult->num_rows == 0) {
        // $group_id is unique, proceed with the insert operation

        $sql = "INSERT INTO study_group VALUES ('$group_id', '$group_name', '$teacher_id')";
        $result = $con->query($sql);

        if ($result) {
            header('location: study_group.php');
        } else {
            echo "<script>alert('ไม่สามารถเพิ่มข้อมูลได้')</script>";
        }
    } else {
        echo "<script>alert('รหัสกลุ่ม $group_id มีอยู่ในฐานข้อมูลแล้ว')</script>";
    }
}

if (isset($_POST['import'])) {
    $filename = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {
        $file = fopen($filename, "r");
        fgetcsv($file); //ไม่เอาหัวคอลัมน์
        $sql = "create table tmp(
                        group_id varchar(9) not null,
                        group_name varchar(100) not null,
                        teacher_name varchar(100) null
                        )";
        $result = $con->query($sql);
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sqlInsert = "Insert into tmp values('" . $column[17] . "','" . $column[18] . "','" . $column[36] . "')";
            $result = $con->query($sqlInsert);
        }
        if ($result) {
            $sql = "INSERT INTO study_group SELECT tmp.group_id, tmp.group_name, subquery.teacher_id
                    FROM tmp
                    LEFT OUTER JOIN (
                        SELECT teacher.teacher_id, CONCAT(teacher.name, ' ', teacher.surname) AS teacher_name
                        FROM teacher
                    ) AS subquery ON tmp.teacher_name = subquery.teacher_name group by tmp.group_id;";
            $result = $con->query($sql);
            if ($result) {
                $sql = "drop table tmp;";
                $result = $con->query($sql);
                echo "<script>alert('เพิ้มข้อมูล study_group สำเร็จ')</script>";
                header('location:study_group.php');
            } else {
                echo "<script>alert('study_group error!')</script>";
            }
        } else {
            echo "<script>alert('tmp error!')</script>";
        }
    }
}
if (isset($_POST['drop'])) {
    $sql = "delete from study_group";
    $result = $con->query($sql);
    if ($result) {
        header('location:study_group.php');
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด')</script>";
    }
}

//ถ้ากดปุ่มค้นหา
if (isset($_POST['search'])) {
    $keyword = $_POST['keyword'];
    header("location:$_SERVER[PHP_SELF]?keyword=$keyword");
}
//ถ้ามีการค้นหา
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $sql = "SELECT
            student.std_id,
            student.password,
            student.prefix,
            CONCAT(student.name, ' ', student.surname) AS full_name,
            CONCAT('(', SUBSTRING_INDEX(student.level, '(', -1)) AS level,
            studyfield.field_name,
            student.system,
            student.tel
        FROM
            student
        INNER JOIN
            studyfield ON student.field_id = studyfield.field_id
        INNER JOIN
            study_group ON student.group_id = study_group.group_id
        WHERE
            student.std_id LIKE '%$keyword%'
            OR student.password LIKE '%$keyword%'
            OR student.prefix LIKE '%$keyword%'
            OR CONCAT(student.name, ' ', student.surname) LIKE '%$keyword%'
            OR CONCAT('(', SUBSTRING_INDEX(student.level, '(', -1)) LIKE '%$keyword%'
            OR studyfield.field_name LIKE '%$keyword%'
            OR student.system LIKE '%$keyword%'
            OR student.tel LIKE '%$keyword%'
        ";
    $result = $con->query($sql);

    $sql = "SELECT COUNT(*) AS total
            FROM (
                SELECT
                    student.std_id,
                    student.password,
                    student.prefix,
                    CONCAT(student.name, ' ', student.surname) AS full_name,
                    CONCAT('(', SUBSTRING_INDEX(student.level, '(', -1)) AS level,
                    studyfield.field_name,
                    student.system,
                    student.tel
                FROM
                    student
                INNER JOIN
                    studyfield ON student.field_id = studyfield.field_id
                INNER JOIN
                    study_group ON student.group_id = study_group.group_id
                WHERE
                    student.std_id LIKE '%$keyword%'
                    OR student.password LIKE '%$keyword%'
                    OR student.prefix LIKE '%$keyword%'
                    OR CONCAT(student.name, ' ', student.surname) LIKE '%$keyword%'
                    OR CONCAT('(', SUBSTRING_INDEX(student.level, '(', -1)) LIKE '%$keyword%'
                    OR studyfield.field_name LIKE '%$keyword%'
                    OR student.system LIKE '%$keyword%'
                    OR student.tel LIKE '%$keyword%'
            ) AS subquery;
            ";
    $result_count = $con->query($sql);
    $row_count = mysqli_fetch_array($result_count);
    $count = $row_count['total'];
    if (!$result) {
        echo "error!";
    }
} else {
    $keyword = "";
    $sql = "SELECT
                student.std_id,
                student.password,
                student.prefix,
                CONCAT(student.name, ' ', student.surname) AS full_name,
                CONCAT('(', SUBSTRING_INDEX(student.level, '(', -1)) AS level,
                studyfield.field_name,
                student.system,
                student.tel
            FROM
                student
            INNER JOIN
                studyfield ON student.field_id = studyfield.field_id
            INNER JOIN
                study_group ON student.group_id = study_group.group_id
            ";
    $result = $con->query($sql);

    $sql = "SELECT COUNT(*) AS total 
            FROM student";
    $result_count = $con->query($sql);
    $row_count = mysqli_fetch_array($result_count);
    $count = $row_count['total'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <!-- partial:index.partial.html -->

    <div class="container my-5">
        <h1 class="mb-5">นักเรียน นักศึกษา</h1>

        <!-- Adding Components -->
        <!-- <div class="card mb-3">
            <div class="card-header">เพิ่มข้อมูล</div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="row mb-3">
                        <div class="col-sm-2">
                            <label for="" class="form-label">รหัสกลุ่ม</label>
                            <input type="text" name="group_id" id="" class="form-control" maxlength="9">
                        </div>
                        <div class="col-sm-5">
                            <label for="" class="form-label">ชื่อเต็มกลุ่ม</label>
                            <input type="text" name="group_name" id="" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <label for="" class="form-label">ครูที่ปรึกษา</label>
                            <select name="teacher_id" id="" class="form-select">
                                <option value="">ไม่มีที่ปรึกษา</option>
                                <?php
                                $sql2 = "SELECT teacher.teacher_id, CONCAT(teacher.name, ' ', teacher.surname) AS teacher_name FROM teacher;";
                                $result2 = $con->query($sql2);
                                while ($row2 = mysqli_fetch_array($result2)) {
                                ?>
                                    <option value="<?php echo $row2['teacher_id']; ?>">
                                        <?php echo $row2['teacher_name']; ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-1 d-flex flex-column justify-content-end">
                            <input type="submit" value="เพิ่ม" class="btn btn-success" name="addRecord">
                        </div>
                    </div>
                </form>
                <form action="" method="POST" enctype="multipart/form-data" class="d-flex gap-2 mb-3 align-items-center">
                    <label for="" class="form-label">นำเข้าไฟล์ <i class="bi bi-filetype-csv"></i></label>
                    <input type="file" class="form-control w-25" name="file" accept=".csv" required>
                    <input type="submit" value="นำเข้า" class="btn btn-success" name="import">
                    <input type="submit" value="ล้างข้อมูล" class="btn btn-danger" onclick="return confirm('คุณต้องการล้างข้อมูลทั้งหมดใช่หรือไม่?')" name="drop">
                </form>
            </div>
        </div> -->

        <div class="row mb-3">
            <div class="col-sm-4 d-flex gap-3 align-items-center">
                <h>แสดง</h>
                <select class="form-control w-25" name="state" id="maxRows" width="">
                    <option value="5000">ทั้งหมด</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="70">70</option>
                    <option value="100">100</option>
                </select>
                <h>รายการ จาก <?php echo $count ?> รายการ </h>
            </div>
            
            <form action="" method="post" class="col-sm-3 d-flex gap-2">
                <input class="form-control" id="myInput" type="text" placeholder="ค้นหา" name="keyword" value="<?php echo $keyword; ?>">
                <button type="submit" class="btn btn-primary" name="search" id="searchButton">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <div class="col-sm-6 d-flex align-items-center gap-3">
                <!-- <div class="category-filter">
                    <select id="categoryFilter" class="form-control" width="max-content">
                        <option value="">แผนกทั้งหมด</option>
                        <option value="Accountant">Accountant</option>
                        <option value="Chief Executive Officer (CEO)">Chief Executive Officer (CEO)</option>
                        <option value="Developer">Developer</option>
                    </select>
                </div> -->
                <!-- <div class="category-filter">
                    <select id="officeFilter" class="form-control">
                        <option value="">all office</option>
                        <option value="Tokyo">Tokyo</option>
                        <option value="Chief Executive Officer (CEO)">Chief Executive Officer (CEO)</option>
                        <option value="Developer">Developer</option>
                    </select>
                </div> -->
            </div>
        </div>
        <!-- loading spinner -->
        <table class="table table table-bordered table-striped mb-2" id="table-id" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="checkbox" name="select_all" value="1" id="headerCheckbox" class="form-check-input">
                        </div>
                    </th>
                    <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <b>รหัสนักศึกษา</b>
                            <i class="bi bi-arrow-down-up sort" onclick="sortTable(2)"></i>
                        </div>
                    </th>
                    <!-- <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <b>คำนำหน้า</b>
                            <i class="bi bi-arrow-down-up sort" onclick="sortTable(3)"></i>
                        </div>
                    </th> -->
                    <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <b>ชื่อ-สกุล</b>
                            <i class="bi bi-arrow-down-up sort" onclick="sortTable(3)"></i>
                        </div>
                    </th>
                    <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <b>ระดับชั้น</b>
                            <i class="bi bi-arrow-down-up sort" onclick="sortTable(4)"></i>
                        </div>
                    </th>
                    <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <b>สาขาวิชา</b>
                            <i class="bi bi-arrow-down-up sort" onclick="sortTable(5)"></i>
                        </div>
                    </th>
                    <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <b>ระบบ</b>
                            <i class="bi bi-arrow-down-up sort" onclick="sortTable(6)"></i>
                        </div>
                    </th>
                    <!-- <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <b>โทร</b>
                            <i class="bi bi-arrow-down-up sort" onclick="sortTable(8)"></i>
                        </div>
                    </th> -->
                    <th>
                        <div class="d-flex justify-content-between align-items-center">
                            <b>การดำเนินการ</b>
                            <i class="bi bi-arrow-down-up sort" onclick="sortTable(7)"></i>
                        </div>
                    </th>
                    <th>
                        <b>จัดการ</b>
                    </th>
                </tr>

            </thead>
            <tbody id="myTable">
                <?php
                while ($row = mysqli_fetch_array($result)) {
                ?>
                    <tr>
                        <td><input type="checkbox" name="group_id[]" value="<?php echo $row['group_id']; ?>" id="" class="bodyCheckbox form-check-input"></td>
                        <td><?php echo $row['std_id']; ?></td>
                        <!-- <td><?php echo $row['prefix']; ?></td> -->
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['level']; ?></td>
                        <td><?php echo $row['field_name']; ?></td>
                        <td><?php echo $row['system']; ?></td>
                        <!-- <td><?php echo $row['tel']; ?></td> -->
                        <td>
                            <select name="" id="" class="form-select">
                                <?php
                                $sql = "SELECT progression_name FROM progression";
                                $result2 = $con->query($sql);
                                while ($row2 = mysqli_fetch_array($result2)) {
                                    $progressionName = $row2['progression_name'];
                                    echo '<option value="' . $progressionName . '">' . $progressionName . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <a href="edit_study_group.php?group_id=<?php echo $row['group_id'] ?>" class="btn btn-warning"><i class="bi bi-pen-fill"></i></a>
                            <a href="" class="btn btn-danger"><i class="bi bi-trash-fill"></i></i></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="tfooter d-flex align-items-center justify-content-between">
            <div class='pagination-container' style="cursor:pointer;">
                <nav>
                    <ul class="pagination">

                        <li class="page-item" data-page="prev">
                            <span>
                                <span class="page-link sr-only">ก่อนหน้า</span>
                            </span>
                        </li>

                        <!--	Here the JS Function Will Add the Rows -->
                        <li data-page="next" class="page-item" id="prev">
                            <span>
                                <span class="page-link sr-only">ถัดไป</span>
                            </span>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- bottom floating components -->
        <div class="d-flex align-items-center gap-2" style=" position: fixed; bottom: 20px; right: 20px;">
            <div class="counter bg-secondary rounded p-2 text-light">เลือก <h id="countDisplay">0</h> รายการ จากทั้งหมด <?php echo $count ?> รายการ</div>
            <button type="submit" id="deleteBtn" name="" class="btn btn-danger" style="display: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                ดูรายการที่เลือก
            </button>
            <button type="submit" id="bringToTopBtn" class="btn btn-primary" name="">
                <i class="bi bi-arrow-up-circle-fill"></i>
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">รายการที่เลือก</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul id="checkedDataList"></ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="button" id="deleteButton" class="btn btn-secondary" data-bs-dismiss="modal">ลบข้อมูลที่เลือก</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End of Container -->


    <!-- filter script -->
    <script>
        // search
        // $(document).ready(function() {
        //     $("#myInput").on("keyup", function() {
        //         var value = $(this).val().toLowerCase();
        //         $("#myTable tr").filter(function() {
        //             $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        //         });
        //     });
        // });
        // dropdown department category
        $(document).ready(function() {
            $("#categoryFilter").change(function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
        // dropdown office category
        $(document).ready(function() {
            $("#officeFilter").change(function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

    <!-- sort table script -->
    <script>
        function sortTable(n) {
            // Change cursor to waiting state
            document.documentElement.style.cursor = "wait";

            // Delay before executing the sorting code (e.g., 500 milliseconds)
            setTimeout(function() {
                var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;

                table = document.getElementById("myTable");
                switching = true;
                // Set the sorting direction to ascending:
                dir = "asc";
                /* Make a loop that will continue until
                no switching has been done: */
                while (switching) {
                    // Start by saying: no switching is done:
                    switching = false;
                    rows = table.rows;
                    /* Loop through all table rows (except the
                    first, which contains table headers): */
                    for (i = 0; i < (rows.length - 1); i++) {
                        // Start by saying there should be no switching:
                        shouldSwitch = false;
                        /* Get the two elements you want to compare,
                        one from the current row and one from the next: */
                        x = rows[i].getElementsByTagName("TD")[n];
                        y = rows[i + 1].getElementsByTagName("TD")[n];
                        /* Check if the two rows should switch place,
                        based on the direction, asc or desc: */
                        if (dir == "asc") {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                // If so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        } else if (dir == "desc") {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                // If so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }
                    if (shouldSwitch) {
                        /* If a switch has been marked, make the switch
                        and mark that a switch has been done: */
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                        // Each time a switch is done, increase this count by 1:
                        switchcount++;
                    } else {
                        /* If no switching has been done AND the direction is "asc",
                        set the direction to "desc" and run the while loop again. */
                        if (switchcount == 0 && dir == "asc") {
                            dir = "desc";
                            switching = true;
                        }
                    }
                }

                // Delay after executing the sorting code (e.g., 500 milliseconds)
                setTimeout(function() {
                    // Revert cursor back to the default state
                    document.documentElement.style.cursor = "default";
                }, 500);
            }, 500);
        }
    </script>

    <!-- pagination script -->
    <script>
        getPagination('#table-id');
        //getPagination('.table-class');
        //getPagination('table');

        /*					PAGINATION 
        - on change max rows select options fade out all rows gt option value mx = 5
        - append pagination list as per numbers of rows / max rows option (20row/5= 4pages )
        - each pagination li on click -> fade out all tr gt max rows * li num and (5*pagenum 2 = 10 rows)
        - fade out all tr lt max rows * li num - max rows ((5*pagenum 2 = 10) - 5)
        - fade in all tr between (maxRows*PageNum) and (maxRows*pageNum)- MaxRows 
        */


        function getPagination(table) {
            var lastPage = 1;

            $('#maxRows')
                .on('change', function(evt) {
                    //$('.paginationprev').html('');						// reset pagination

                    lastPage = 1;
                    $('.pagination')
                        .find('li')
                        .slice(1, -1)
                        .remove();
                    var trnum = 0; // reset tr counter
                    var maxRows = parseInt($(this).val()); // get Max Rows from select option

                    if (maxRows == 5000) {
                        $('.pagination').hide();
                    } else {
                        $('.pagination').show();
                    }

                    var totalRows = $(table + ' tbody tr').length; // numbers of rows
                    $(table + ' tr:gt(0)').each(function() {
                        // each TR in  table and not the header
                        trnum++; // Start Counter
                        if (trnum > maxRows) {
                            // if tr number gt maxRows

                            $(this).hide(); // fade it out
                        }
                        if (trnum <= maxRows) {
                            $(this).show();
                        } // else fade in Important in case if it ..
                    }); //  was fade out to fade it in
                    if (totalRows > maxRows) {
                        // if tr total rows gt max rows option
                        var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
                        //	numbers of pages
                        for (var i = 1; i <= pagenum;) {
                            // for each page append pagination li
                            $('.pagination #prev')
                                .before(
                                    '<li class="page-item" data-page="' + i + '">\
                                        <span>\
                                            <span class="page-link sr-only">' + i++ + '</span>\
                                        </span>\
								    </li>'
                                )
                                .show();
                        } // end for i
                    } // end if row count > max rows
                    $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
                    $('.pagination li').on('click', function(evt) {
                        // on click each page
                        evt.stopImmediatePropagation();
                        evt.preventDefault();
                        var pageNum = $(this).attr('data-page'); // get it's number

                        var maxRows = parseInt($('#maxRows').val()); // get Max Rows from select option

                        if (pageNum == 'prev') {
                            if (lastPage == 1) {
                                return;
                            }
                            pageNum = --lastPage;
                        }
                        if (pageNum == 'next') {
                            if (lastPage == $('.pagination li').length - 2) {
                                return;
                            }
                            pageNum = ++lastPage;
                        }

                        lastPage = pageNum;
                        var trIndex = 0; // reset tr counter
                        $('.pagination li').removeClass('active'); // remove active class from all li
                        $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // add active class to the clicked
                        // $(this).addClass('active');					// add active class to the clicked
                        limitPagging();
                        $(table + ' tr:gt(0)').each(function() {
                            // each tr in table not the header
                            trIndex++; // tr index counter
                            // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
                            if (
                                trIndex > maxRows * pageNum ||
                                trIndex <= maxRows * pageNum - maxRows
                            ) {
                                $(this).hide();
                            } else {
                                $(this).show();
                            } //else fade in
                        }); // end of for each tr in table
                    }); // end of on click pagination list
                    limitPagging();
                })
                .val(5)
                .change();

            // end of on select change

            // END OF PAGINATION
        }

        function limitPagging() {
            // alert($('.pagination li').length)

            if ($('.pagination li').length > 7) {
                if ($('.pagination li.active').attr('data-page') <= 3) {
                    $('.pagination li:gt(5)').hide();
                    $('.pagination li:lt(5)').show();
                    $('.pagination [data-page="next"]').show();
                }
                if ($('.pagination li.active').attr('data-page') > 3) {
                    $('.pagination li:gt(0)').hide();
                    $('.pagination [data-page="next"]').show();
                    for (let i = (parseInt($('.pagination li.active').attr('data-page')) - 2); i <= (parseInt($('.pagination li.active').attr('data-page')) + 2); i++) {
                        $('.pagination [data-page="' + i + '"]').show();

                    }

                }
            }
        }

        $(function() {
            // Just to append id number for each row
            $('table tr:eq(0)').prepend('<th> ลำดับ </th>');

            var no = 0;

            $('table tr:gt(0)').each(function() {
                no++;
                $(this).prepend('<td>' + no + '</td>');
            });
        });

        //  Developed By Yasser Mas
        // yasser.mas2@gmail.com
    </script>

    <!-- checkbox, counter, checked list script -->
    <script>
        // Get reference to the header checkbox
        var headerCheckbox = document.getElementById('headerCheckbox');

        // Get reference to all body checkboxes
        var bodyCheckboxes = document.querySelectorAll('.bodyCheckbox');

        // Get reference to the count display element
        var countDisplay = document.getElementById('countDisplay');

        var deleteBtn = document.getElementById("deleteBtn");

        // Add event listener to the header checkbox
        headerCheckbox.addEventListener('change', function() {
            // Get the checked status of the header checkbox
            var isChecked = headerCheckbox.checked;

            // Set the checked status of all body checkboxes accordingly
            bodyCheckboxes.forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });

            // Update the count display
            updateCountDisplay();
        });

        // Add event listener to each body checkbox
        bodyCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Update the count display
                updateCountDisplay();
            });
        });

        // Function to update the count display
        function updateCountDisplay() {
            // Get the count of selected checkboxes
            var selectedCount = document.querySelectorAll('.bodyCheckbox:checked').length;

            // Display the count
            countDisplay.textContent = selectedCount;

            // Show/hide deleteBtn based on the count value
            if (selectedCount > 0) {
                deleteBtn.style.display = "block";
            } else {
                deleteBtn.style.display = "none";
            }
        }

        // Add event listener to the delete button
        deleteBtn.addEventListener('click', function() {
            // Get all the checked body checkboxes
            var checkedCheckboxes = document.querySelectorAll('.bodyCheckbox:checked');

            // Create an array to store the selected group_id values
            var selectedGroupIds = [];

            // Iterate over the checked checkboxes and extract the group_id values
            for (var i = 0; i < checkedCheckboxes.length; i++) {
                var checkbox = checkedCheckboxes[i];
                var group_id = checkbox.value; // Assuming the value of the checkbox is the group_id
                selectedGroupIds.push(group_id);
            }

            // Populate the modal with the checked data
            for (var j = 0; j < selectedGroupIds.length; j++) {
                var listItem = document.createElement("li");
                listItem.textContent = selectedGroupIds[j];
                checkedDataList.appendChild(listItem);
            }

        });
    </script>

    <!-- bring to top btn script -->
    <script>
        function bringToTop() {
            // Scroll the page to the top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Hide the button when it is clicked
            bringToTopBtn.classList.add("d-none");
        }

        var bringToTopBtn = document.getElementById("bringToTopBtn");
        bringToTopBtn.addEventListener("click", bringToTop);

        // Check the scroll position on page load
        window.addEventListener("load", function() {
            // Hide the button if the scroll position is already at the top
            if (window.pageYOffset === 0) {
                bringToTopBtn.classList.add("d-none");
            }
        });

        // Check the scroll position on scroll event
        window.addEventListener("scroll", function() {
            // Show or hide the button based on the scroll position
            if (window.pageYOffset > 0) {
                bringToTopBtn.classList.remove("d-none");
                bringToTopBtn.classList.add("show");
            } else {
                bringToTopBtn.classList.remove("show");
                bringToTopBtn.classList.add("d-none");
            }
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Delete button click event
            $('#deleteButton').on('click', function() {
                var checkedIds = [];
                // Loop through the checkboxes and get the checked group_ids
                $('.bodyCheckbox:checked').each(function() {
                    checkedIds.push($(this).val());
                });

                // AJAX request to delete the selected rows
                $.ajax({
                    url: 'delete_rows.php',
                    type: 'POST',
                    data: {
                        groupIds: checkedIds
                    },
                    success: function(response) {
                        console.log(response); // You can display a success message or perform any other actions here
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText); // You can handle the error here
                    }
                });
            });
        });
    </script>
</body>

</html>