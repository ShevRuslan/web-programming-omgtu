<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 6; // Количество записей на странице

$start = ($page - 1) * $itemsPerPage;

$sql_manufacturer = "SELECT * FROM cars LIMIT $start, $itemsPerPage";
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
$total_count_query = "SELECT COUNT(*) as total FROM cars";
$total_count_result = mysqli_query($conn, $total_count_query);
$total_count = mysqli_fetch_assoc($total_count_result)['total'];

header('Content-Type: application/json');
echo json_encode(['cars' => $cars, 'totalCount' => $total_count]);


mysqli_close($conn);
?>
