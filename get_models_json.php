<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "web";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Получение данных производителей
$sql_manufacturer = "SELECT * FROM manufacturer";
$result_manufacturer = mysqli_query($conn, $sql_manufacturer);

// Создание массива с данными
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

// Получение данных по автомобилям
$sql_cars = "SELECT * FROM cars";
$result_cars = mysqli_query($conn, $sql_cars);

$cars = array();
if (mysqli_num_rows($result_cars) > 0) {
  while ($row_car = mysqli_fetch_assoc($result_cars)) {
    $cars[] = array(
      'id' => $row_car['id'],
      'name' => $row_car['name'],
      'vin' => $row_car['vin'],
      'horsepower' => $row_car['horsepower'],
      'year' => $row_car['year'],
      'color' => $row_car['color'],
      'photo' => 'img/' . strtolower($row_car['photo']),
      'description' => $row_car['description'],
      'manufacturer_id' => $row_car['manufacturer_id']
    );
  }
}

// Закрытие соединения
mysqli_close($conn);
