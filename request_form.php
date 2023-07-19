<?php
include 'navbar.php';

$sql_province = "SELECT * FROM provinces";
$result_provinces = $con->query($sql_province);

// if (isset($_POST['submit'])) {
//     echo $date = date("Y-m-d");
//     $std_id = $_SESSION['std_id'];
//     $gpa = $_POST['gpa'];
//     $vac_id = $_POST['vac_id'];
//     $pat_id = $_POST['pat_id'];
//     $est_name = $_POST['est_name'];
//     $coord_name = $_POST['coord_name'];
//     $coord_tel = $_POST['coord_tel'];
//     $address_no = $_POST['address_no'];
//     $address_moo = $_POST['address_moo'];
//     $address_road = $_POST['address_road'];
//     $subd_id = $_POST['subd_id'];
//     $tel = $_POST['tel'];
//     $fax = $_POST['fax'];
//     $email = $_POST['email'];
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
</head>

<body>
    <div class="container-sm my-5">
        <div class="title">
            <h3>แบบคำร้องขอฝึกงาน/ฝึกอาชีพในสถานประกอบการ</h3>
        </div>
        <form action="request_form_pdf.php" method="GET">
            <div class="card mb-3">
                <div class="card-header">
                    <b>เกี่ยวกับนักเรียน นักศึกษา</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3 mb-3">
                            <label for="" class="form-label">ผลการเรียนเฉลี่ยสะสม</label>
                            <input type="text" name="gpa" id="" class="form-control" required="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="" class="form-label">สถานะการรับวัคซีน</label><br>
                            <?php
                            $sql_vac = "SELECT * FROM vacinated_status";
                            $result_vac = $con->query($sql_vac);
                            while ($row_vac = mysqli_fetch_array($result_vac)) {
                            ?>
                                <!-- <div class="form-check"> -->
                                <input class="form-check-input" type="radio" name="vac_id" id="flexRadioDefault1" value="<?php echo $row_vac['vac_id'] ?>" required="">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <?php echo $row_vac['vac_name'] ?>
                                </label>
                                <!-- </div> -->
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="" class="form-label">สถานะการเป็นผู้ป่วย</label><br>
                            <?php
                            $sql_pat = "SELECT * FROM patien_status";
                            $result_pat = $con->query($sql_pat);
                            while ($row_pat = mysqli_fetch_array($result_pat)) {
                            ?>
                                <!-- <div class="form-check"> -->
                                <input class="form-check-input" type="radio" name="pat_id" id="flexRadioDefault1" value="<?php echo $row_pat['pat_id'] ?>" required="">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <?php echo $row_pat['pat_name'] ?>
                                </label>
                                <!-- </div> -->
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <b>เกี่ยวกับสถานประกอบการ</b>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-sm-12 mb-3">
                            <label for="" class="form-label">ชื่อสถานประกอบการ</label>
                            <input type="text" class="form-control" name="est_name" required="">
                        </div>
                        <div class="col-sm mb-3">
                            <label for="" class="form-label">ชื่อผู้ประสานงาน</label>
                            <input type="text" class="form-control" name="coord_name" required="">
                        </div>
                        <div class="col-sm mb-3">
                            <label for="" class="form-label">เบอร์โทรฯ ผู้ประสานงาน</label>
                            <input type="text" class="form-control" name="coord_tel" required="">
                        </div>
                    </div>
                    <b>ที่ตั้ง</b>
                    <div class="row">
                        <div class="col-sm-3 mb-3">
                            <label for="" class="form-label">เลขที่</label>
                            <input type="text" class="form-control" name="address_no" required="">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label for="" class="form-label">หมู่ที่</label>
                            <input type="text" class="form-control" name="address_moo" required="">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label for="" class="form-label">ถนน</label>
                            <input type="text" class="form-control" name="address_road" required="">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="" class="form-label">จังหวัด</label>
                            <select class="form-select" name="province" id="province" required="">
                                <option value="">เลือก...</option>
                                <?php
                                while ($row_province = mysqli_fetch_array($result_provinces)) {
                                    echo '<option value="' . $row_province['pro_id'] . '">' . $row_province['name_th'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="" class="form-label">อำเภอ</label>
                            <select class="form-select" name="district" id="district" required="">
                                <option value="">เลือก...</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="" class="form-label">ตำบล</label>
                            <select class="form-select" name="subd_id" id="subdistrict" required="">
                                <option value="">เลือก...</option>
                            </select>
                        </div>

                        <div class="col-sm-3 mb-3">
                            <label for="" class="form-label">รหัสไปรษณีย์</label>
                            <input type="text" class="form-control" name="zip_code" id="zip_code" required="">
                        </div>
                    </div>
                    <b>ติดต่อ</b>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="" class="form-label">โทรศัพท์</label>
                            <input type="text" class="form-control" name="tel" required="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="" class="form-label">โทรสาร</label>
                            <input type="text" class="form-control" name="fax" required="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="" class="form-label">อี-เมลล์</label>
                            <input type="email" class="form-control" name="email" required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning" role="alert">
                โปรดพิมพ์&#160;<b>แบบคำร้องขอฝึกงาน/ฝึกอาชีพในสถานประกอบการ</b>&#160;และเซ็นให้เรียบร้อย จึงนำส่งให้กับงานทวิภาคี
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">ข้อมูลที่กรอก ถูกต้องทุกประการ</label>
            </div>
            <input type="submit" class="btn btn-success" value="บันทึกและพิมพ์เอกสาร" name="submit">
        </form>
    </div>
    <script>
        $(document).ready(function() {
            // When the province selection changes
            $('#province').change(function() {
                var provinceId = $(this).val();

                // Make an AJAX request to fetch the districts based on the selected province
                $.ajax({
                    url: 'get_districts.php',
                    type: 'POST',
                    data: {
                        provinceId: provinceId
                    },
                    success: function(response) {
                        $('#district').html(response);
                        $('#subdistrict').html('<option value="">เลือก...</option>');
                        $('#zip_code').val(''); // Reset the zip_code input field
                    }
                });
            });

            // When the district selection changes
            $('#district').change(function() {
                var districtId = $(this).val();

                // Make an AJAX request to fetch the subdistricts and zip code based on the selected district
                $.ajax({
                    url: 'get_subdistricts.php',
                    type: 'POST',
                    data: {
                        districtId: districtId
                    },
                    success: function(response) {
                        console.log(response); // Check the response in the browser console
                        var data = JSON.parse(response);
                        console.log(data); // Check the parsed data in the browser console

                        $('#subdistrict').html(data.subdistrictOptions);
                    }
                });
            });

            // When the subdistrict selection changes
            $('#subdistrict').change(function() {
                var subdistrictId = $(this).val();

                // Make an AJAX request to fetch the zip code based on the selected subdistrict
                $.ajax({
                    url: 'get_zipcode.php',
                    type: 'POST',
                    data: {
                        subdistrictId: subdistrictId
                    },
                    success: function(response) {
                        console.log(response); // Check the response in the browser console
                        $('#zip_code').val(response);
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>