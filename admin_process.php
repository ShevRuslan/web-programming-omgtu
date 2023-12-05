<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);

// Обработка добавления новой машины
if (isset($_POST['add_car'])) {
  $car_name = $_POST['car_name'];
  $vin = $_POST['vin'];
  $horsepower = $_POST['horsepower'];
  $year = $_POST['year'];
  $color = $_POST['color'];
  $car_photo = $_FILES['car_photo']['name'];
  $car_photo_tmp = $_FILES['car_photo']['tmp_name'];
  $car_photo_path = 'img/' . $car_photo;
  move_uploaded_file($car_photo_tmp, $car_photo_path);
  $description = $_POST['description'];
  $manufacturer_id = $_POST['manufacturer_id'];

  $sql = "INSERT INTO cars (name, vin, horsepower, year, color, photo, views, description, manufacturer_id) 
          VALUES ('$car_name', '$vin', $horsepower, $year, '$color', '$car_photo_path', 0, '$description', $manufacturer_id)";
  mysqli_query($conn, $sql);
  // // Перенаправление обратно на страницу администратора
  echo json_encode(['success' => true]);
  mysqli_close($conn);
  exit;
}


// Обработка добавления нового производителя
if (isset($_POST['add_manufacturer'])) {
  $manufacturer_name = $_POST['manufacturer_name'];

  // Обработка загрузки фотографии
  $manufacturer_photo = $_FILES['manufacturer_photo']['name'];
  $manufacturer_photo_tmp = $_FILES['manufacturer_photo']['tmp_name'];
  $manufacturer_photo_path = $manufacturer_photo;
  move_uploaded_file($manufacturer_photo_tmp, $manufacturer_photo_path);

  // Добавьте остальные поля производителя по необходимости

  $sql = "INSERT INTO manufacturer (name, photo) VALUES ('$manufacturer_name', '$manufacturer_photo_path')";
  mysqli_query($conn, $sql);
  // // Перенаправление обратно на страницу администратора
  echo json_encode(['success' => true]);
  mysqli_close($conn);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
  $carId = $_POST['car_id'];
  $manufacturer_id = $_POST["manufacturer_id"];
  if (isset($carId)) {
    $sql = "DELETE FROM cars WHERE id = $carId";
    mysqli_query($conn, $sql);

    // Верните какой-то ответ (например, JSON)
    echo json_encode(['success' => true]);
    mysqli_close($conn);
    exit;
  } else if (isset($manufacturer_id)) {
    $sql = "DELETE FROM manufacturer WHERE id = $manufacturer_id";
    mysqli_query($conn, $sql);

    // Верните какой-то ответ (например, JSON)
    echo json_encode(['success' => true]);
    mysqli_close($conn);
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
  // Получаем данные из формы
  $carId = $_POST['id'];
  if (isset($carId)) {
    $carName = $_POST['name'];
    $vin = $_POST['vin'];
    $color = $_POST['color'];
    $horsepower = $_POST['horsepower'];
    $year = $_POST['year'];

    // Подготавливаем SQL-запрос для обновления данных
    $sql = "UPDATE cars SET 
              name = '$carName', 
              vin = '$vin', 
              color = '$color', 
              horsepower = '$horsepower', 
              year = '$year' 
            WHERE id = $carId";

    // Выполняем запрос к базе данных
    if (mysqli_query($conn, $sql)) {
      // Если запрос успешен, отправляем успешный ответ
      header('Content-Type: application/json');
      echo json_encode(['success' => true]);
      exit;
    }
  } else if (isset($_POST['manufacturer_id'])) {
    $carName = $_POST['name'];
    $manufacturer_id = $_POST['manufacturer_id'];
    // Подготавливаем SQL-запрос для обновления данных
    $sql = "UPDATE manufacturer SET 
            name = '$carName'
          WHERE id = $manufacturer_id";

    // Выполняем запрос к базе данных
    if (mysqli_query($conn, $sql)) {
      // Если запрос успешен, отправляем успешный ответ
      header('Content-Type: application/json');
      echo json_encode(['success' => true]);
      exit;
    } else {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Error updating car']);
      exit;
    }
  } else {
    // Если произошла ошибка, отправляем ответ с ошибкой
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error updating car']);
    exit;
  }
}
