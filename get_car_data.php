<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем, передан ли идентификатор машины
if (isset($_GET['car_id'])) {
  $id = $_GET['car_id'];

  // Выполняем запрос к базе данных
  $sql = "SELECT * FROM cars WHERE id = $id";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Формируем массив с данными
    $car = array(
      'name' => $row['name'],
      'horsepower' => $row['horsepower'],
      'year' => $row['year'],
      'color' => $row['color'],
      'photo' => $row['photo'],
      'vin' => $row['vin'],
      'description' => $row['description']
    );

    // Обновляем счетчик просмотров
    $sql1 = "UPDATE cars SET views = views + 1 WHERE id = $id";
    mysqli_query($conn, $sql1);

    // Возвращаем данные в формате JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'car' => $car]);
    exit;
  } else {
    // Если машина не найдена, возвращаем ошибку
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Car not found']);
    exit;
  }
} else if (isset($_GET['manufacturer_id'])) {
  $id = $_GET['manufacturer_id'];

  // Выполняем запрос к базе данных
  $sql = "SELECT * FROM manufacturer WHERE id = $id";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Формируем массив с данными
    $car = array(
      'name' => $row['name'],
      'photo' => $row['photo'],
    );

    // Обновляем счетчик просмотров
    $sql1 = "UPDATE cars SET views = views + 1 WHERE id = $id";
    mysqli_query($conn, $sql1);

    // Возвращаем данные в формате JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'car' => $car]);
    exit;
  } else {
    // Если машина не найдена, возвращаем ошибку
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Car not found']);
    exit;
  }
} else {
  // Если идентификатор машины не передан, возвращаем ошибку
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Car ID not provided']);
  exit;
}

// Закрытие соединения
mysqli_close($conn);
