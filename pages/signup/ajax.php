<?php
include_once "../../includes/db.inc.php";
include_once "../../includes/functions.inc.php";
include_once "Common.php";

if (isset($_POST['getCityByDepartment']) == "getCityByDepartment") {
    $department = $_POST['department'];
    $cities = getCityByDepartment($conn, $department);
    $cityData = '<option value="">Municipality</option>';
    foreach($cities as $city) {
        $cityData .= '<option value="' . $city['municipality'] . '">' . $city['municipality'] . '</option>';
    }
    echo "test^" . $cityData;
}
