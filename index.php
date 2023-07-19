<?php include 'navbar.php'; ?>
<?php
$status2 = 'เกรดเฉลี่ยไม่ผ่านเกณฑ์'; #red
$status3 = 'รออนุมัติคำร้อง'; #yellow
$status4 = 'อนุมัติคำร้อง'; #green
$status5 = 'ไม่อนุมัติคำร้อง'; #red
$status6 = 'รออนุมัติโดยสถานประกอบการ'; #yellow
$status7 = 'สถานประกอบการรับเข้าฝึกงาน'; #green
$status8 = 'สถานประกอบการไม่รับเข้าฝึกงาน'; #red
$status9 = "แผนที่และสัญญาค้ำประกันสำเร็จ";  #green
$status10 = "แผนที่และสัญญาค้ำประกันไม่สำเร็จ";  #red
$status11 = "พร้อมออกฝึกงาน";  #green

$request_form = array('ใบคำร้องขอออกฝึกงาน', 'request_form.php');
$out_form = array('หนังสือออกและหนังสือตอบรับ', 'out_form.php');
$map_form = array('แผนที่ และ สัญญาค้ำประกัน', 'map_form.php');
$ready = array('พร้อมออกฝึกงาน', '#');
//get user data
$sql = "SELECT
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
                progression.color,
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
if ($result = $con->query($sql)) {
    $row = mysqli_fetch_array($result);
    $std_status = $row['progression_id'];
} else {
    echo "เกิดข้อผิดพลาดด้าน sql";
}

if($row['color']=='bg-warning'){
    $bgcolor = 'img/bg-yellow.svg';
}elseif($row['color']=='bg-danger'){
    $bgcolor = 'img/bg-red.svg';
}else{
    $bgcolor = 'img/bg-green.svg';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>บัญชีนักศึกษา</title>
    <style>
        * {
            font-family: Prompt;
        }

        p {
            font-weight: 300;
            font-size: 1rem;
        }

        @media only screen and (max-width: 600px) {
            .status-container-mobile {
                display: unset;
            }

            .status-container-desktop {
                display: none;
            }
        }

        .progression .content {
            /* Used to position the left vertical line */
            position: relative;

            margin: auto 0;
        }

        .progression .container__line {
            /* Border */
            border-right: 4px solid #c4c4c4;

            /* Positioned at the left */
            left: 14px;
            position: absolute;
            top: 0px;
            z-index: -1;

            /* Take full height */
            height: 100%;
        }

        .progression .container__items {
            /* Reset styles */
            list-style-type: none;
            margin: 0px;
            padding: 0px;
        }

        .progression .container__item {
            margin-bottom: 30px;
        }

        .progression .container__top {
            /* Center the content horizontally */
            align-items: center;
            display: flex;
        }

        .progression .container__circle {
            /* Rounded border */
            background-color: #c4c4c4;
            border-radius: 9999px;

            /* Size */
            height: 32px;
            width: 32px;
        }

        .progression .container__title {
            /* Take available width */
            flex: 1;
            margin-left: 0.5rem;
        }

        .progression .container__desc {
            /* Make it align with the title */
            margin-left: 48px;
        }

        <?php if ($std_status == 2) {
            $destination = $request_form[1];
            $btn_name = $request_form[0];
            $btn_disabled = 'enabled';
        ?>

        /* รอดำเนินการ */
        .waiting {
            background-color: #ffc108 !important;
        }

        <?php } elseif ($std_status == 3) {
            $destination = $out_form[1];
            $btn_name = $out_form[0];
            $btn_disabled = 'disabled';
        ?>

        /* ดำเนินการแล้ว รออนุมัติคำร้อง */
        .waiting {
            background-color: #28a745 !important;
        }

        .request_check {
            background-color: #ffc108 !important;
        }

        <?php } elseif ($std_status == 4) {
            $destination = $out_form[1];
            $btn_name = $out_form[0];
            $btn_disabled = 'enabled';
        ?>

        /* ดำเนินการแล้ว อนุมัติคำร้อง */
        .waiting {
            background-color: #28a745 !important;
        }

        .request_check {
            background-color: #28a745 !important;
        }

        <?php } elseif ($std_status == 5) {
            $destination = $request_form[1];
            $btn_name = $request_form[0];
            $btn_disabled = 'enabled';
        ?>

        /* ดำเนินการแล้ว ไม่อนุมัติคำร้อง */
        .waiting {
            background-color: #28a745 !important;
        }

        .request_check {
            background-color: #dc3545 !important;
        }

        <?php } elseif ($std_status == 6) {
            $destination = $map_form[1];
            $btn_name = $map_form[0];
            $btn_disabled = 'disabled';
        ?>

        /* ดำเนินการแล้ว ผ่านคำร้อง รออนุมัติโดยสถานประกอบการ */
        .waiting {
            background-color: #28a745 !important;
        }

        .request_check {
            background-color: #28a745 !important;
        }

        .outin_check {
            background-color: #ffc108 !important;
        }

        <?php } elseif ($std_status == 7) {
            $destination = $map_form[1];
            $btn_name = $map_form[0];
            $btn_disabled = 'enabled'
        ?>

        /* ดำเนินการแล้ว ผ่านคำร้อง สถานประกอบการรับ */
        .waiting {
            background-color: #28a745 !important;
        }

        .request_check {
            background-color: #28a745 !important;
        }

        .outin_check {
            background-color: #28a745 !important;
        }

        <?php } elseif ($std_status == 8) {
            $destination = $request_form[1];
            $btn_name = $request_form[0];
            $btn_disabled = 'enabled';
        ?>

        /* ดำเนินการแล้ว ผ่านคำร้อง สถานประกอบการไม่รับ */
        .waiting {
            background-color: #28a745 !important;
        }

        .request_check {
            background-color: #28a745 !important;
        }

        .outin_check {
            background-color: #dc3545 !important;
        }

        <?php } elseif ($std_status == 9) {
            $destination = $request_form[1];
            $btn_name = $request_form[0];
            $btn_disabled = 'enabled';
        ?>

        /* ดำเนินการแล้ว ผ่านคำร้อง สถานประกอบการรับ แผนที่และสัญญาสำเร็จ*/
        .waiting {
            background-color: #28a745 !important;
        }

        .request_check {
            background-color: #28a745 !important;
        }

        .outin_check {
            background-color: #28a745 !important;
        }

        .map_surety_check {
            background-color: #28a745 !important;
        }

        <?php } elseif ($std_status == 10) {
            $destination = $map_form[1];
            $btn_name = $map_form[0];
            $btn_disabled = 'enabled';
        ?>

        /* ดำเนินการแล้ว ผ่านคำร้อง สถานประกอบการรับ แผนที่และสัญญาไม่สำเร็จ*/
        .waiting {
            background-color: #28a745 !important;
        }

        .request_check {
            background-color: #28a745 !important;
        }

        .outin_check {
            background-color: #28a745 !important;
        }

        .map_surety_check {
            background-color: #dc3545 !important;
        }

        <?php } elseif ($std_status == 11) {
            $destination = $ready[1];
            $btn_name = $ready[0];
            $btn_disabled = 'disabled';
        ?>

        /* ดำเนินการแล้ว ผ่านคำร้อง สถานประกอบการรับ แผนที่และสัญญาสำเร็จ พร้อมออกฝึกงาน*/
        .waiting {
            background-color: #28a745 !important;
        }

        .request_check {
            background-color: #28a745 !important;
        }

        .outin_check {
            background-color: #28a745 !important;
        }

        .map_surety_check {
            background-color: #28a745 !important;
        }

        .ready_check {
            background-color: #28a745 !important;
        }


        <?php } ?>.profile {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            padding: 2rem;
            border-radius: 15px;
            transition: 0.5s;
            background-color: rgba(255, 255, 255, 0.8);
            /* Set the desired background color */
            backdrop-filter: blur(5px);
        }

        .profile:hover {
            box-shadow: rgba(0, 0, 0, 0.25) 0px 25px 50px -12px;
            transform: translateY(5px);
            transition: 0.5s;
        }

        .bg{
            width: 50%;
            filter: blur(3px);
            opacity: 90%;
            position: absolute;
            z-index: -1;
            transform: translateX(-200px);
        }

        body {
            background-image: url(<?php echo $bgcolor?>);
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>

</head>

<body>
    <!-- <img class="bg" src="img/TATC.ong.gif" alt=""> -->
    <div class="container-lg d-flex flex-column justify-content-center" style="height: 90vh;">
        <div class="row d-flex align-items-center">
            <div class="col-md-8 mb-5 d-flex justify-content-center">
                <div class="d-flex flex-column text-left profile">
                    <h6><i class="bi bi-mortarboard-fill"></i> นักศึกษา</h6>
                    <h1><?php echo $row['name'] . ' ' . $row['surname'] ?></h1>
                    <div>
                        <h6>รหัสนักศึกษา <?php echo $row['std_id'] ?>
                    </div>
                    <div>
                        <h><?php echo $row['group_name']; ?></h>
                    </div>
                    <div class="div">
                        <h>อาจารย์ที่ปรึกษา <?php echo $row['teacher_fullname']; ?></h>
                    </div>
                    <div class="status">
                        <span class="badge rounded-pill <?php echo $row['color'] ?>"><?php echo $row['progression_name'] ?></span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="progression">

                    <div class="header">
                        <h4>สถานะของนักศึกษา</h4>
                    </div>
                    <div class="content">

                        <!-- Left vertical line -->
                        <div class="container__line"></div>

                        <!-- The timeline items container -->
                        <ul class="container__items">
                            <!-- Each timeline item -->
                            <li class="container__item">
                                <!-- The circle and title -->
                                <div class="container__top">
                                    <!-- The circle -->
                                    <div class="container__circle waiting"></div>

                                    <!-- The title -->
                                    <div class="container__title">
                                        <h>รอดำเนินการ</h>
                                    </div>
                                </div>

                                <!-- The description -->
                                <div class="container__desc">

                                </div>
                            </li>

                            <li class="container__item">
                                <!-- The circle and title -->
                                <div class="container__top">
                                    <!-- The circle -->
                                    <div class="container__circle request_check"></div>

                                    <!-- The title -->
                                    <div class="container__title">
                                        <a href="request_form.php">ยื่นเอกสารคำร้องขอออกฝึกงาน</a>
                                    </div>
                                </div>

                                <!-- The description -->
                                <div class="container__desc">

                                </div>
                            </li>

                            <li class="container__item">
                                <!-- The circle and title -->
                                <div class="container__top">
                                    <!-- The circle -->
                                    <div class="container__circle outin_check"></div>

                                    <!-- The title -->
                                    <div class="container__title">
                                        <a href="out_form.php">หนังสือออกและหนังสือตอบรับ</a>
                                    </div>
                                </div>

                                <!-- The description -->
                                <div class="container__desc">

                                </div>
                            </li>

                            <li class="container__item">
                                <!-- The circle and title -->
                                <div class="container__top">
                                    <!-- The circle -->
                                    <div class="container__circle map_surety_check"></div>

                                    <!-- The title -->
                                    <div class="container__title">
                                        <a href="map_form.php">แผนที่ และ สัญญาค้ำประกัน</a>
                                    </div>
                                </div>

                                <!-- The description -->
                                <div class="container__desc">

                                </div>
                            </li>

                            <li class="container__item">
                                <!-- The circle and title -->
                                <div class="container__top">
                                    <!-- The circle -->
                                    <div class="container__circle ready_check"></div>

                                    <!-- The title -->
                                    <div class="container__title">
                                        <h>พร้อมออกฝึกงาน</ย>
                                    </div>
                                </div>

                                <!-- The description -->
                                <div class="container__desc">

                                </div>
                            </li>
                        </ul>
                    </div>
                    <a href="<?php echo $destination ?>" class="btn btn-primary " style="width: 100%;" <?php echo $btn_disabled ?>><?php echo $btn_name ?></a>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>