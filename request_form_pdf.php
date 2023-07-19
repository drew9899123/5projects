<?php
include 'connect.php';
include 'admin/libary/convertToThaiDate.php';

echo $date = date("Y-m-d");
echo $std_id = $_SESSION['std_id'];
echo $gpa = $_GET['gpa'];
echo $vac_id = $_GET['vac_id'];
echo $pat_id = $_GET['pat_id'];
echo $est_name = $_GET['est_name'];
echo $coord_name = $_GET['coord_name'];
echo $coord_tel = $_GET['coord_tel'];
echo $address_no = $_GET['address_no'];
echo $address_moo = $_GET['address_moo'];
echo $address_road = $_GET['address_road'];
echo $subd_id = $_GET['subd_id'];
$sql_address = "SELECT 
                            subdistrict.subd_id, 
                            subdistrict.name_th as subd_name,
                            subdistrict.zip_code,
                            district.dis_id,
                            district.name_th as dis_name,
                            provinces.pro_id,
                            provinces.name_th as pro_name
                        FROM subdistrict
                        LEFT JOIN district
                        ON subdistrict.dis_id = district.dis_id
                        LEFT JOIN provinces
                        ON district.pro_id = provinces.pro_id
                        WHERE subdistrict.subd_id = '$subd_id'";
$row_address = mysqli_fetch_array($result_address = $con->query($sql_address));
echo $tel = $_GET['tel'];
echo $fax = $_GET['fax'];
echo $email = $_GET['email'];

//std data
$sql_std = "SELECT
                student.std_id,
                student.prefix,
                student.name,
                student.surname,
                student.level,
                COALESCE(studyfield.field_name, 'NULL') AS field_name,
                department.dept_id,
                COALESCE(department.dept_name, 'NULL') AS dept_name,
                COALESCE(study_group.group_name, 'NULL') AS group_name,
                student.system,
                student.tel,
                student.progression_id,
                COALESCE(progression.progression_name, 'NULL') AS progression_name,
                COALESCE(CONCAT(teacher.name, ' ', teacher.surname), 'ยังไม่มีครูที่ปรึกษาในระบบ') AS teacher_fullname
                FROM student
                LEFT JOIN studyfield
                ON student.field_id = studyfield.field_id
                LEFT JOIN study_group
                ON student.group_id = study_group.group_id
                LEFT JOIN progression
                ON student.progression_id = progression.progression_id
                LEFT JOIN teacher
                ON study_group.teacher_id = teacher.teacher_id
                LEFT JOIN department
                ON studyfield.dept_id = department.dept_id
                WHERE student.std_id = '$std_id';";
$row_std = mysqli_fetch_array($result_std = $con->query($sql_std));

$sql_schd = "SELECT *
                FROM schedule
                WHERE schedule_id = (
                    SELECT MAX(schedule_id)
                    FROM schedule
                );
                ";
$row_schd = mysqli_fetch_array($result_schd = $con->query($sql_schd));
$schd_id = $row_schd['schedule_id'];

$dept_id = $row_std['dept_id'];

$sql_spv = "SELECT
                    department.dept_name,
                    COALESCE(CONCAT(teacher.name, ' ', teacher.surname), 'ยังไม่มีครูนิเทศในระบบ') AS teacher_fullname
                    FROM supervision
                    LEFT JOIN department
                    ON department.dept_id = supervision.dept_id
                    LEFT JOIN teacher
                    ON supervision.teacher_id = teacher.teacher_id
                    WHERE supervision.dept_id = '$dept_id';";
$row_spv = mysqli_fetch_array($result_spv = $con->query($sql_spv));

$sql_dept = "SELECT 
                    department.dept_name,
                    COALESCE(CONCAT(teacher.name, ' ', teacher.surname), 'ยังไม่มีครูนิเทศในระบบ') AS teacher_fullname
                    FROM department
                    LEFT JOIN teacher
                    ON department.teacher_id = teacher.teacher_id
                    WHERE department.dept_id = '$dept_id';
                ";
$row_dept = mysqli_fetch_array($result_dept = $con->query($sql_dept));

// insert
$sql_id =   "SELECT COALESCE(MAX(est_id) + 1, 1) AS est_id
                    FROM est
                ";
$result_id = $con->query($sql_id);
$row_id = mysqli_fetch_array($result_id);
$est_id = $row_id['est_id'];

$sql = "INSERT INTO est
            VALUES(
                '$est_id',
                '$est_name',
                NULL,
                '$coord_name',
                '$coord_tel',
                '$address_no',
                '$address_moo',
                '$address_road',
                '$subd_id',
                NULL,
                NULL,
                '$tel',
                '$fax',
                '$email',
                NULL
            )
            ";
$result_est = $con->query($sql);
if ($result_est) {
    echo "<script>alert('บันทึกข้อมูล '" . $est_id . "'สำเร็จ')</script>";
    // echo "<script>window.location.href:std_profile.php</script>";
} else {
    echo "<script>alert('บันทึก '" . $est_id . "'ไม่สำเร็จ')</script>";
}

$sql = "INSERT INTO requesting VALUES(
                NULL,
                '$date',
                '$std_id',
                '$est_id',
                '$schd_id',
                NULL,
                NULL
            )";
$result_requesting = $con->query($sql);
if ($result_requesting) {
    echo "<script>alert('บันทึกข้อมูล requesting สำเร็จ')</script>";
    // echo "<script>window.location.href:std_profile.php</script>";
} else {
    echo "<script>alert('บันทึก requesting ไม่สำเร็จ')</script>";
}

if ($result_est && $result_requesting) {
    $sql_update =   "UPDATE student
                        SET progression_id = 3
                        WHERE std_id = '$std_id';
                    ";
    $result = $con->query($sql_update);
    if ($result) {
        echo "<script>alert('บันทึกข้อมูล student สำเร็จ')</script>";
    } else {
        echo "<scrip>alert('บันทึกข้อมูล student ไม่สำเร็จ')</script>";
    }
}



//วันที่เริ่ม และ จบ
//แปลงวันที่เริ่มฝึกงาน และ แยกตัวแปล
$start_date_th = convertToThaiDate($row_schd['start_date']);
preg_match('/(\d+)\s+([ก-๙]+)\s+(\d+)/u', $start_date_th, $matches);
$start_day_th = $matches[1];
$start_month_th = $matches[2];
$start_year_th = $matches[3];
//แปลงวันที่จบฝึกงาน และ แยกตัวแปล
$finish_date_th = convertToThaiDate($row_schd['finish_date']);
preg_match('/(\d+)\s+([ก-๙]+)\s+(\d+)/u', $finish_date_th, $matches);
$finish_day_th = $matches[1];
$finish_month_th = $matches[2];
$finish_year_th = $matches[3];

$thaiDate = convertToThaiDate($date);

// Extract day, month, and year using regular expressions
preg_match('/(\d+)\s+([ก-๙]+)\s+(\d+)/u', $thaiDate, $matches);
$day = $matches[1];
$month = $matches[2];
$year = $matches[3];

echo "Day: " . $day . "\n";
echo "Month: " . $month . "\n";
echo "Year: " . $year . "\n";

//Level checking
if (strpos($row_std['group_name'], 'ปวช.') !== false) {
    $level = 'ปวช.';
}
if (strpos($row_std['group_name'], 'ปวส.') !== false) {
    $level = 'ปวส.';
}

//ค้นหาหัวหน้างานอาชีวศึกษาระบบทวิภาคี
$sql_dut = "SELECT 
                    department.teacher_id,
                    COALESCE(CONCAT(teacher.name, ' ', teacher.surname), 'ยังไม่มีหัวหน้าแผนกในระบบ') AS teacher_fullname
                    FROM department 
                    LEFT JOIN teacher
                    ON department.teacher_id = teacher.teacher_id
                    WHERE dept_name = 'งานอาชีวศึกษาระบบทวิภาคี'";
$row_dut = mysqli_fetch_array($result_dut = $con->query($sql_dut));

//extract ปีที่.../...

// Extract 'x/y' pattern using regular expressions
preg_match('/(\d+\/\d+)/', $row_std['group_name'], $matches);
$std_year = $matches[1];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบฟอร์มคำร้องขอฝึกงาน/ฝึกอาชีพในสถานประกอบการ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-Regular.ttf) format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-MediumItalic.ttf) format('truetype');
            font-weight: 500;
            font-style: italic;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-Medium.ttf) format('truetype');
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-LightItalic.ttf) format('truetype');
            font-weight: 300;
            font-style: italic;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-Light.ttf) format('truetype');
            font-weight: 300;
            font-style: normal;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-Italic.ttf) format('truetype');
            font-weight: normal;
            font-style: italic;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-ExtraLightItalic.ttf) format('truetype');
            font-weight: 200;
            font-style: italic;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-ExtraLight.ttf) format('truetype');
            font-weight: 200;
            font-style: normal;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-ExtraBoldItalic.ttf) format('truetype');
            font-weight: 800;
            font-style: italic;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-ExtraBold.ttf) format('truetype');
            font-weight: 800;
            font-style: normal;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-BoldItalic.ttf) format('truetype');
            font-weight: bold;
            font-style: italic;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url(Sarabun/Sarabun-Bold.ttf) format('truetype');
            font-weight: bold;
            font-style: normal;
        }


        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        u {
            border-bottom: 1px dotted #000;
            text-decoration: none;
        }


        body {
            font-family: 'Sarabun';
            font-weight: 200 !important;
            font-size: 12px !important;
        }

        b {
            font-weight: 500;
        }

        .a4 {
            width: 793.7007874px;
            height: 1122.519685px;
            margin: 0 auto;
            border: 1px solid black;
            /* padding: 1.8897637795 1.8897637795 3.7795275591 2.8346456693; */
        }
    </style>
</head>

<body>
    <!-- <a onclick="previewPDF('MyReport.pdf')" class="btn btn-primary">Export (pdf)</a> -->
    <div class="a4">
        <form id="pdfForm" class="container">
            <div class="title text-center">
                <b>วิทยาลัยเทคนิคสัตหีบ</b>
            </div>
            <div class="sub-title text-center">
                <b>แบบคำร้องขอฝึกงาน/ฝึกอาชีพในสถานประกอบการ</b>
            </div>
            <div class="d-flex justify-content-end">
                <div class="d-flex w- justify-content-between">
                    <h>วันที่</h>
                    &#160;&#160;&#160;<?php echo $day ?>&#160;&#160;&#160;
                    <h>เดือน</h>
                    &#160;&#160;&#160;<?php echo $month ?>&#160;&#160;&#160;
                    <h>พ.ศ</h>
                    &#160;&#160;&#160;<?php echo $year ?>&#160;&#160;&#160;
                </div>
            </div>
            <div class="row">
                <div class="col-1">
                    <b>เรื่อง</b>
                </div>
                <div class="col-11">
                    <h>ขออนุญาตฝึกงาน/ฝึกอาชีพในสถานประกอบการ</h>
                </div>
            </div>
            <div class="row">
                <div class="col-1">
                    <b>เรียน</b>
                </div>
                <div class="col-11">
                    <h>หัวหน้างานอาชีวศึกษาระบบทวิภาคี</h>
                </div>
            </div>
            <div class="row">
                <div class="col-1">

                </div>
                <div class="col-6">
                    <h>ข้าพเจ้า</h>
                    <?php echo $row_std['prefix'] . '  ' . $row_std['name'] . '  ' . $row_std['surname'] ?>
                </div>
                <div class="col-5">
                    <h>รหัสประจำตัว</h>
                    <?php echo $std_id ?>
                </div>
            </div>
            <!-- NEWLINE -->
            <div class="row">
                <div class="col-5">
                    <h>นักเรียน/นักศึกษา ระดับ</h>
                    <input type="radio" name="level" id="level_puachot" value="ปวช." <?php if ($level === 'ปวช.') echo 'checked'; ?>>
                    <label for="level_puachot">ปวช.</label>
                    <input type="radio" name="level" id="level_puasor" value="ปวส." <?php if ($level === 'ปวส.') echo 'checked'; ?>>
                    <label for="level_puasor">ปวส.</label>
                </div>
                <div class="col-2">
                    <h>ปีที่</h>
                    <?php echo $std_year ?>
                </div>
                <div class="col-5">
                    <h>สาขาวิชา</h>
                    <?php echo $row_std['dept_name'] ?>
                </div>
            </div>
            <!-- NEWLINE -->
            <div class="row">
                <div class="col gap-3 d-flex flex-row align-items-center">
                    <div class="gap-2">
                        <h>สาขางาน</h>
                        <?php echo $row_std['field_name'] ?>
                    </div>
                    <div class="gap-2">
                        <input type="radio" name="" id="" value="ปกติ" <?php if ($row_std['system'] === 'ปกติ') echo 'checked'; ?>>
                        <label for="">ระบบปกติ</label>
                        <input type="radio" name="" id="" value="ทวิภาคี" <?php if ($row_std['system'] === 'ทวิภาคี') echo 'checked'; ?>>
                        <label for="">ระบบทวิภาคี</label>
                    </div>
                    <div class="gap-2">
                        <h>มีผลการเรียนเฉลี่ยสะสม</h>
                        <?php echo $gpa; ?>
                    </div>
                </div>
            </div>
            <!-- NEWLINE -->
            <div class="row">
                <div class="col-6  d-flex gap-2">
                    <h>มีความประสงค์ขอฝึกงาน/ฝึกอาชีพในภาคเรียนที่</h>
                    <?php echo $row_schd['term_year']; ?>
                </div>
                <div class="col-6  d-flex gap-2">
                    <h>ระหว่างวันที่</h>
                    <?php echo $start_day_th ?>
                    <h>เดือน</h>
                    <?php echo $start_month_th ?>
                    <h>พ.ศ</h>
                    <?php echo $start_year_th ?>
                </div>
            </div>
            <!-- NEWLINE -->
            <div class="col-12 d-flex gap-2">
                <h>ถึงวันที่</h>
                <?php echo $finish_day_th ?>
                <h>เดือน</h>
                <?php echo $finish_month_th ?>
                <h>พ.ศ</h>
                <?php echo $finish_year_th ?>
                <h>โทรศัพท์</h>
                <h><?php echo $row_std['tel'] ?></h>
            </div>




            <div class="covid row">
                <div class="col-12">
                    <b>เกี่ยวกับไวรัส โคโรน่า ๒๐๑๙ (COVID - ๑๙)</b>
                    <div>
                        <h>สถานะการรับวัคซีน</h>
                        <?php
                        $sql_vac = "SELECT * FROM vacinated_status";
                        $result_vac = $con->query($sql_vac);
                        while ($row_vac = mysqli_fetch_array($result_vac)) {
                        ?>
                            <!-- <div class="form-check"> -->
                            <input class="form-check-input" type="radio" name="vac_id" id="flexRadioDefault1" value="<?php echo $row_vac['vac_id'] ?>" <?php if ($vac_id === $row_vac['vac_id']) echo 'checked'; ?>>
                            <label class="form-check-label" for="flexRadioDefault1">
                                <?php echo $row_vac['vac_name'] ?>
                            </label>
                            <!-- </div> -->
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col-12">
                    <h>สถานะการเป็นผู้ป่วย</h>
                    <?php
                    $sql_pat = "SELECT * FROM patien_status";
                    $result_pat = $con->query($sql_pat);
                    while ($row_pat = mysqli_fetch_array($result_pat)) {
                    ?>
                        <!-- <div class="form-check"> -->
                        <input class="form-check-input" type="radio" name="pat_id" id="flexRadioDefault1" value="<?php echo $row_pat['pat_id'] ?>" <?php if ($pat_id === $row_pat['pat_id']) echo 'checked'; ?>>
                        <label class="form-check-label" for="flexRadioDefault1">
                            <?php echo $row_pat['pat_name'] ?>
                        </label>
                        <!-- </div> -->
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="est">
                <div class="header"><b>รายละเอียดสถานประกอบการ</b></div>
                <div class="row">
                    <div class="col-12">
                        <h>ชื่อสถานประกอบการ บริษัท/ห้างหุ้นส่วน/ร้าน</h>
                        <h class="text-center"></h>
                        <?php echo $est_name ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <h>ชื่อผู้ประสานงาน</h>
                        <?php echo $coord_name ?>
                    </div>
                    <div class="col-4">
                        <h>โทรศัพท์</h>
                        <?php echo $coord_tel ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-5">
                        <h>ที่ตั้ง เลขที่</h>
                        <?php echo $address_no ?>
                        <h>หมู่ที่</h>
                        <?php echo $address_moo ?>
                        <h>ถนน</h>
                        <?php echo $address_road ?>
                    </div>
                    <div class="col-6">
                        <h>ตำบล</h>
                        <?php echo $row_address['subd_name']; ?>
                        <h>อำเภอ</h>
                        <?php echo $row_address['dis_name']; ?>
                    </div>
                </div>
                <!-- NEWLINE -->
                <div class="row">
                    <div class="col-3">
                        <h>จังหวัด</h>
                        <?php echo $row_address['pro_name'] ?>
                    </div>
                    <div class="col-3">
                        <h>รหัสไปรษณีย์</h>
                        <?php echo $row_address['zip_code'] ?>
                    </div>
                    <div class="col-3">
                        <h>โทรศัพท์</h>
                        <?php echo $tel ?>
                    </div>
                    <div class="col-3">
                        <h>โทรสาร</h>
                        <?php echo $fax ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h>Email</h>
                        <?php echo $email ?>
                    </div>
                </div>
            </div>
            <div class="complementary row">
                <div class="col-5 text-center">
                    <h>จึงเรียนมาเพื่อโปรดพิจารณาอนุญาต</h>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <h>ลงชื่อ................................................นักเรียน/นักศึกษา</h>
            </div>
            <div class="d-flex justify-content-end" style="padding-right: 80px;">
                <h>( <?php echo $row_std['prefix'] . '  ' . $row_std['name'] . '  ' . $row_std['surname'] ?> )</h>
            </div>
            <div class="d-flex justify-content-end" style="padding-right: 80px;">
                <h>แผนกวิชา <?php echo $row_std['dept_name'] ?></h>

            </div>
            <div class="container">
                <div class="row">
                    <div class="col-6 border border-dark border-end-0 d-flex flex-column">
                        <h>๑.ความเห็นผู้ปกครอง</h>
                        <h class="ps-3">(&nbsp;&nbsp;&nbsp;) อนุญาต</h>
                        <h class="ps-3">(&nbsp;&nbsp;&nbsp;) ไม่อนุญาต เนื่องจาก.........................................</h>
                        <br>
                        <br>
                        <h>ลงชื่อ.............................................................................................................</h>
                        <h class="ps-5">(..................................................................................................)</h>
                        <h class="ps-5">............................/......................................./...............................</h>
                        <h class="ps-5">เบอร์โทรผู้ปกครอง.................................................................</h>

                        <h>๓.ความเห็นครูนิเทศ</h>
                        <h class="ps-3">(&nbsp;&nbsp;&nbsp;) อนุญาต</h>
                        <h class="ps-3">(&nbsp;&nbsp;&nbsp;) ไม่อนุญาต เนื่องจาก.........................................</h>
                        <br>
                        <br>
                        <h>ลงชื่อ.............................................................................................................</h>
                        <div class="ps-5 d-flex flex-column text-center">
                            <h>( <?php echo $row_spv['teacher_fullname'] ?> )</h>
                            <h>แผนกวิชา <?php echo $row_spv['dept_name'] ?></h>
                            <h>............................/......................................./...............................</h>
                        </div>

                        <h>๕.ความเห็นหัวหน้างานอาชีวศึกษาระบบทวิภาคี</h>
                        <h>เห็นสมควรพิจารณาอนุญาต</h>
                        <br>
                        <br>
                        <br>
                        <h>ลงชื่อ.............................................................................................................</h>
                        <div class="ps-5 d-flex flex-column text-center">
                            <h>( <?php echo $row_dut['teacher_fullname'] ?> )</h>
                            <h>หัวหน้างานอาชีวศึกษาระบบทวิภาคี</h>
                            <h>............................/......................................./...............................</h>
                        </div>
                    </div>
                    <div class="col-6 border border-dark p-0">
                        <div class="px-2 d-flex flex-column">
                            <h>๒.ความเห็นครูที่ปรึกษา</h>
                            <h class="ps-3">(&nbsp;&nbsp;&nbsp;) อนุญาต</h>
                            <h class="ps-3">(&nbsp;&nbsp;&nbsp;) ไม่อนุญาต เนื่องจาก.........................................</h>
                            <br>
                            <br>
                            <h>ลงชื่อ.............................................................................................................</h>
                            <div class="ps-5 d-flex flex-column text-center">
                                <h>( <?php echo $row_std['teacher_fullname'] ?> )</h>
                                <h>แผนกวิชา <?php echo $row_std['dept_name'] ?></h>
                                <h>............................/......................................./...............................</h>
                            </div>
                        </div>

                        <div class="px-2 d-flex flex-column">
                            <h>๔.ความเห็นหัวหน้าแผนกวิชา</h>
                            <h>เห็นสมควรพิจารณาอนุญาต</h>
                            <br>
                            <br>
                            <br>
                            <h>ลงชื่อ.............................................................................................................</h>
                            <div class="ps-5 d-flex flex-column text-center">
                                <h>( <?php echo $row_dept['teacher_fullname'] ?> )</h>
                                <h>แผนกวิชา <?php echo $row_dept['dept_name'] ?></h>
                                <h>............................/......................................./...............................</h>
                            </div>
                        </div>

                        <div class="text-center border border-dark border-end-0 border-start-0 border-bottom-0">
                            <br>
                            <br>
                            <br>
                            <br>
                            <h6>สำหรับติดตามบัตรสถานประกอบการ</h6>
                            <br>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-2">
                        <h6><u>หมายเหตุ</u> : </h6>
                    </div>
                    <div class="col-10">
                        <p>
                            ๑. หลังจากนักเรียนนักศึกษายืนใบตอบรับจากสถานประกอบการแล้ว <b><u>จะไม่มีการย้ายสถานประกอบการ</u></b><br>
                            ๒. แบบคำร้องขอฝึกงาน/ฝึกอาชีพนี้เป็นเอกสารที่ใช้ยื่นต่อวิทยาลัยฯ ห้ามนำไปใช้ติดต่อสถานประกอบการ<br>
                            ๓. เอกสารราชการ กรุณาเขียนตัวบรรจงและกรอกข้อมูลให้ครบถ้วน<br>
                        </p>
                    </div>
                </div>
            </div>


        </form>
    </div>

    <script>
        function previewPDF(filenameInput) {
            var element = document.getElementById("pdfForm");

            var opt = {
                margin: [0.25, 1, 0.25, 0.75],
                filename: filenameInput,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).outputPdf('datauristring').then(function(pdfDataUri) {
                var newTab = window.open();
                newTab.document.open();
                newTab.document.write('<iframe src="' + pdfDataUri + '" width="100%" height="100%"></iframe>');
                newTab.document.close();

                window.location.href = 'std_profile.php';
            });

        }
        window.onload = function() {
            previewPDF('MyReport.pdf');
        };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</div>