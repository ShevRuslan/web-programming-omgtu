<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "web";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql_manufacturer = "SELECT * FROM cars";
$result_manufacturer = mysqli_query($conn, $sql_manufacturer);

$cars = array();
if (mysqli_num_rows($result_manufacturer) > 0) {
  while ($row_manufacturer = mysqli_fetch_assoc($result_manufacturer)) {
    $cars[] = array(
      'id' => $row_manufacturer['id'],
      'name' => $row_manufacturer['name'],
      'image' => $row_manufacturer['photo'],
      'vin' =>  $row_manufacturer['vin'],
      'horsepower' =>  $row_manufacturer['horsepower'],
      'year' =>  $row_manufacturer['year'],
      'color' =>  $row_manufacturer['color'],
      'description' =>  $row_manufacturer['description'],
    );
  }
}

header('Content-Type: application/json');
echo json_encode($cars);

mysqli_close($conn);
