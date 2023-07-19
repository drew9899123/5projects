<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container-sm my-5">
        <div class="row mb-5">


            <div class="title mb-5">
                <h1>หนังสือออก</h1>
                <h class="fw-light">ขอความอนุเคราะห์รับนักเรียน นักศึกษาเข้ารับการฝึกงาน</h>
            </div>
            <div class="col-sm">

                <div class="content mb-3 d-flex flex-column">
                    <b>ชื่อสถานประกอบการ</b>
                    <h class="fw-normal">บริษัท เอส อาร์ ซี จำกัด มหาชน</h>
                </div>

                <div class="content mb-3 d-flex flex-column">
                    <b>เรียน</b>
                    <h class="fw-normal">ผู้จัดการฝ่ายทรัพยากรบุคคล บรัษัท เอส อาร์ ซี จำกัด มหาชน</h>
                </div>

                <div class="content mb-3 d-flex flex-column">
                    <b>สิ่งที่ส่งมาด้วย</b>
                    <h class="fw-normal">แบบฟอร์มพิจารณารับนักศึกษาฝึกงาน จำนวน 1 ฉบับ</h>
                    <h class="fw-normal">ใบรับรองผลการเรียน จำนวน 1 ฉบับ</h>
                </div>
            </div>
            <div class="col-sm">
                <div class="content mb-3 d-flex flex-column">
                    <b>ระหว่างวันที่</b>
                    <h class="fw-normal">15 พฤษภาคม 2566 - 15 กุมพาพันธ์ 2567</h>
                </div>

                <div class="content mb-3 d-flex flex-column">
                    <b>นักศึกษาที่จะเข้ารับการฝึกงาน</b>
                    <div class="student d-flex align-items-center gap-3">
                        <img src="img/IMG_9678 2.JPG" style="aspect-ratio:1/1; object-fit:cover; object-position: 0px 0px; border-radius: 100%; width:100px;" alt="">
                        <h class="fw-normal">นายพัลลภ บุญเหลือ</h>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning" role="alert">
            โปรดพิมพ์&#160; <b>ใบขอความอนุเคราะห์รับนักเรียน นักศึกษาเข้ารับการฝึกงาน </b> และ <b>ใบพิจารณารับนักเรียน นักศึกษาเข้ารับการฝึกปฏิบัติงาน</b>&#160; เพื่อนำส่งให้กับสถานประกอบการ
        </div>
        <input class="btn btn-success" type="button" value="พิมพ์เอกสาร" name="submit">


    </div>
</body>

</html>