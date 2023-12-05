<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql_manufacturer = "SELECT * FROM manufacturer";
$result_manufacturer = mysqli_query($conn, $sql_manufacturer);

$manufacturers = array();
if (mysqli_num_rows($result_manufacturer) > 0) {
  while ($row_manufacturer = mysqli_fetch_assoc($result_manufacturer)) {
    $manufacturers[] = array(
      'id' => $row_manufacturer['id'],
      'name' => $row_manufacturer['name'],
      'image' => 'img/' . strtolower($row_manufacturer['photo'])
    );
  }
}

header('Content-Type: application/json');
echo json_encode($manufacturers);

mysqli_close($conn);
