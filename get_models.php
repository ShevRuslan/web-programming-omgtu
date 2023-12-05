<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);

// Получение значения параметра brand_id
$brandId = $_GET['brand_id'];

// Выполнение запроса к базе данных
$sql = "SELECT cars.id, cars.name FROM cars JOIN manufacturer ON cars.manufacturer_id = manufacturer.id WHERE manufacturer.name = '$brandId'";
$result = $conn->query($sql);

// Формирование списка моделей в формате JSON
$models = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $models[] = array("id" => $row["id"], "name" => $row["name"]);
  }
}
echo json_encode($models);

$conn->close();
