<?php
include 'connect.php';

$districtId = $_POST['districtId'];

// Query the database to fetch the subdistricts and zip code based on the selected district
$sql_subdistrict = "SELECT * FROM subdistrict WHERE dis_id = $districtId";
$result_subdistricts = $con->query($sql_subdistrict);

// Generate the HTML options for subdistricts
$options = '<option value="">เลือก...</option>';
while ($row_subdistrict = mysqli_fetch_array($result_subdistricts)) {
    $options .= '<option value="' . $row_subdistrict['subd_id'] . '">' . $row_subdistrict['name_th'] . '</option>';
}

$response = array(
    'subdistrictOptions' => $options,
);

echo json_encode($response);
