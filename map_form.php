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
    <div class="container my-5">
        <div class="title mb-5">
            <h1>แผนที่สถานประกอบการ</h1>
            <h class="fw-light">เส้นทางจากวิทยาลัยฯ - สถานประกอบการ</h>
        </div>

        <div class="row">
            <div class="col-md">
                <div class="content mb-3 d-flex flex-column">
                    <b>ชื่อสถานประกอบการ</b>
                    <h class="fw-normal">บริษัท เอส อาร์ ซี จำกัด มหาชน</h>
                </div>

                <div class="content mb-3 d-flex flex-column">
                    <b>ที่ตั้ง</b>
                    <h class="fw-normal">
                        193 หมู่ 3 ต.นาจอมเทียน
                        อ.สัตหีบ จ.ชลบุรี 20250 </h>
                </div>

                <div class="content mb-3 d-flex flex-column">
                    <b>ติดต่อ</b>
                    <h class="fw-normal">
                        โทรศัพท์ 038-238398,038-238527
                        โทรสาร 038-237268</h>
                </div>

                <div class="content mb-3 d-flex flex-column">
                    <b>นักศึกษาที่จะเข้ารับการฝึกงาน</b>
                    <div class="student d-flex align-items-center gap-3">
                        <img src="img/IMG_9678 2.JPG" style="aspect-ratio:1/1; object-fit:cover; object-position: 0px 0px; border-radius: 100%; width:100px;" alt="">
                        <h class="fw-normal">นายพัลลภ บุญเหลือ</h>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="content mb-3 d-flex flex-column">
                    <div class="alert alert-warning" role="alert">
                        โปรดคัดลอก&#160;<b>ละติจูด และ ลองจิจูด</b>&#160;ของสถานประกอบการจาก Google Map และกรอกในช่องต่อไปนี้ <b>ตัวอย่าง</b>&#160;12.80927, 100.91795
                    </div>
                    <form action="<?php $_SERVER['PHP_SELF']; ?>">
                        <div class="mb-2">
                            <label for="" class="form-label">ละติจูด และ ลองจิจูด</label>
                            <input type="text" name="" id="" class="form-control w-50">
                        </div>
                        <div class="mb-2">
                            <input type="button" value="พิมพ์เอกสาร" name="" class="btn btn-success">
                        </div>
                    </form>
                </div>
            </div>



        </div>
</body>

</html>