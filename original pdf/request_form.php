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
            WHERE student.std_id = '$std_id';";
$row_std = mysqli_fetch_array($result_std = $con->query($sql_std));

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="admin/libary/convertToThaiDate.php"></script>
    <style>
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
            font-family: 'sarabun';
            font-weight: 300;
            font-size: 12px !important;
        }

        .a4 {
            width: 793.7007874px;
            height: 1122.519685px;
            margin: 0 auto;
            border: 1px solid black;
            /* padding: 3rem; */


        }
    </style>
</head>

<body>
    <a onclick="previewPDF('MyReport.pdf')" class="btn btn-primary">Export (pdf)</a>
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
                <div class="col-4">
                    <h>นักเรียน/นักศึกษา ระดับ</h>
                    <input type="radio" name="level" id="level_puachot" value="ปวช." <?php if ($level === 'ปวช.') echo 'checked'; ?>>
                    <label for="level_puachot">ปวช.</label>
                    <input type="radio" name="level" id="level_puasor" value="ปวส." <?php if ($level === 'ปวส.') echo 'checked'; ?>>
                    <label for="level_puasor">ปวส.</label>
                </div>
                <div class="col-2">
                    <h>ปีที่</h>
                    <?php echo $std_year?>
                </div>
                <div class="col-6">
                    <h>สาขาวิชา</h>
                </div>
            </div>
            <!-- NEWLINE -->
            <div class="row">
                <div class="col-4">
                    <h>สาขางาน.............................</h>
                </div>
                <div class="col-3">
                    <input type="radio" name="" id="" value="">
                    <label for="">ระบบปกติ</label>
                    <input type="radio" name="" id="" value="">
                    <label for="">ระบบทวิภาคี</label>
                </div>
                <div class="col-5">
                    <h>มีผลการเรียนเฉลี่ยสะสม..................</h>
                </div>
            </div>
            <!-- NEWLINE -->
            <div class="row">
                <div class="col-6">
                    <h>มีความประสงค์ขอฝึกงาน/ฝึกอาชีพในภาคเรียนที่........../..........</h>
                </div>
                <div class="col-6">
                    <h>ระหว่างวันที่........เดือน..........พ.ศ............</h>
                </div>
            </div>
            <!-- NEWLINE -->
            <div class="d-flex">
                <h>ถึงวันที่........เดือน..........พ.ศ.........</h>
                <h>โทรศัพท์.................................</h>
            </div>




            <div class="covid">
                <b>เกี่ยวกับไวรัส โคโรน่า ๒๐๑๙ (COVID - ๑๙)</b>
                <div>
                    <h>สถานะการรับวัคซีน</h>&#160;
                    <input type="radio" name="vac_id" value="" checked>&#160;
                    <label for="">รับแล้ว ๑ เข็ม</label>&#160;
                    <input type="radio" name="vac_id" value="">&#160;
                    <label for="">รับแล้ว ๒ เข็ม</label>&#160;
                    <input type="radio" name="vac_id" value="">&#160;
                    <label for="">รับแล้ว ๓ เข็ม</label>&#160;
                    <input type="radio" name="vac_id" value="">&#160;
                    <label for="">รับแล้ว ๔ เข็ม</label>
                </div>
                <div>
                    <h>สถานะการเป็นผู้ป่วย</h>&#160;
                    <input type="radio" name="vac_id" value="" checked>&#160;
                    <label for="">ไม่เคยเป็นผู้ติดเชื้อ</label>&#160;
                    <input type="radio" name="vac_id" value="">&#160;
                    <label for="">ผู้ป่วยใหม่</label>&#160;
                    <input type="radio" name="vac_id" value="">&#160;
                    <label for="">ผู้ป่วยในระหว่างการรักษา</label>&#160;
                    <input type="radio" name="vac_id" value="">&#160;
                    <label for="">ได้รับการรักษาแล้ว</label>
                </div>
            </div>
            <div class="est">
                <div class="header"><b>รายละเอียดสถานประกอบการ</b></div>
                <div class="d-flex">
                    <h>ชื่อสถานประกอบการ บริษัท/ห้างหุ้นส่วน/ร้าน</h>
                    <h class="text-center" style="border-bottom: 1px dotted #000; text-decoration:none; width: 60%;">ข้อความ</h>
                </div>
                <div class="row">
                    <div class="col-8">
                        <h>ชื่อผู้ประสานงาน</h>
                        <h class="text-center" style="border-bottom: 1px dotted #000; text-decoration:none; width: 100%;">ข้อความ</h>
                    </div>
                    <div class="col-4">
                        <h>โทรศัพท์</h>
                        <h class="text-center" style="border-bottom: 1px dotted #000; text-decoration:none; width: 100%;">ข้อความ</h>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-5">
                        <h>ที่ตั้ง เลขที่.........หมู่ที่...............ถนน.................</h>
                    </div>
                    <div class="col-6">
                        <h>ตำบล...................อำเภอ.....................</h>
                    </div>
                </div>
                <!-- NEWLINE -->
                <div class="row">
                    <div class="col-3">
                        <h>จังหวัด......................</h>
                    </div>
                    <div class="col-3">
                        <h>รหัสไปรษณีย์......................</h>
                    </div>
                    <div class="col-3">
                        <h>โทรศัพท์......................</h>
                    </div>
                    <div class="col-3">
                        <h>โทรสาร......................</h>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h>Email.................................................</h>
                    </div>
                </div>
            </div>
            <div class="complementary row">
                <div class="col-5 text-center">
                    <h>จึงเรียนมาเพื่อโปรดพิจารณาอณุญาต</h>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <h>ลงชื่อ................................................นักเรียน/นักศึกษา</h>
            </div>
            <div class="d-flex justify-content-end" style="padding-right: 80px;">
                <h>(...............................................)</h>
            </div>
            <div class="d-flex justify-content-end" style="padding-right: 80px;">
                <h>แผนกวิชา........................................</h>
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
                            <h>(..................................................................................................)</h>
                            <h>แผนกวิชา.................................................................................</h>
                            <h>............................/......................................./...............................</h>
                        </div>

                        <h>๕.ความเห็นหัวหน้างานอาชีวศึกษาระบบทวิภาคี</h>
                        <h>เห็นสมควรพิจารณาอนุญาต</h>
                        <br>
                        <br>
                        <br>
                        <h>ลงชื่อ.............................................................................................................</h>
                        <div class="ps-5 d-flex flex-column text-center">
                            <h>(..................................................................................................)</h>
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
                                <h>(..................................................................................................)</h>
                                <h>แผนกวิชา.................................................................................</h>
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
                                <h>(..................................................................................................)</h>
                                <h>แผนกวิชา.................................................................................</h>
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
                <div class="row pt-3">
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
                margin: [0.25, 1, 0.25, 0.5],
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
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</div>