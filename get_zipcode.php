<?php
include 'connect.php';

$subdistrictId = $_POST['subdistrictId'];

// Query the database to fetch the zip code based on the selected subdistrict
$sql_zipcode = "SELECT zip_code FROM subdistrict WHERE subd_id = $subdistrictId";
$result_zipcode = $con->query($sql_zipcode);

if ($result_zipcode && $result_zipcode->num_rows > 0) {
    $row_zipcode = $result_zipcode->fetch_assoc();
    $zipcode = $row_zipcode['zip_code'];
} else {
    $zipcode = ''; // Set the zip code as empty if not found
}

echo $zipcode;
?>
