<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);

$id = $_GET['id'];

// Выполнение запроса к базе данных
$sql = "SELECT * FROM cars WHERE id = $id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $car = array(
    'name' => $row['name'],
    'horsepower' => $row['horsepower'],
    'year' => $row['year'],
    'color' => $row['color'],
    'photo' => $row['photo'],
    'vin' => $row['vin'],
    'description' => $row['description']
  );
}

$sql1 = "UPDATE cars SET views = views + 1 WHERE id = $id";
mysqli_query($conn, $sql1);
// Закрытие соединения
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/main.css" />
  <title><?php echo $car['name'] ?></title>
</head>

<body>
  <header class="header">
    <div class="header-logo">
      <a href="/" class="header-logo__name"> MyCars </a>
    </div>
    <div class="header-navbar">
      <div class="header-navbar-search">
        <form method="get" action="/search.php" class="form-search">
          <input type="text" name="query" class="header-navbar-search__input" placeholder="Поиск">
        </form>
      </div>
      <ul class="header-navbar-menu">
        <li class="header-navbar-menu__element">
          <a href="/manufactures.php">Производители</a>
        </li>
        <li class="header-navbar-menu__element">
          <a href="/models.php">Модели</a>
        </li>
      </ul>
    </div>
    <div class="header-social">
      <div class="social">
        <div class="social-element">
          <img src="img/telegramm.svg" alt="" />
          <img src="img/vk.svg" alt="" />
        </div>
      </div>
    </div>
  </header>
  <main class="main main-car">
    <div class="car">
      <img src="<?php echo $car['photo'] ?>" alt="" class="img" />
      <h1><?php echo $car['name'] ?></h1>
      <p>Год выпуска: <?php echo $car['year'] ?></p>
      <p>Цвет: <?php echo $car['color'] ?></p>
      <p>Мощность: <?php echo $car['horsepower'] ?> л.с</p>
      <p>VIN: <?php echo $car['vin'] ?></p>
      <p class="description">
        <?php echo $car['description'] ?>
      </p>
    </div>
  </main>
  <footer class="footer">
    <div class="footer-menu">
      <span>(c) 2023 MyCars</span>
      <span>+7 999 99 99</span>
    </div>
  </footer>
  <script src="js/script.js"></script>
</body>

</html>