<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);

// Получение параметров из GET-запроса
$query = $_GET['query'];
$title = "";
if ($query) {
  // Выполняем поиск по марке, модели и описанию
  $sql = "SELECT cars.*, manufacturer.name AS manufacturer_name FROM cars JOIN manufacturer ON cars.manufacturer_id = manufacturer.id WHERE cars.name LIKE '%$query%' OR manufacturer.name LIKE '%$query%' OR cars.description LIKE '%$query%'";
} else {
  // Выполняем поиск по параметрам
  $manufacturer = $_GET['manufacturer'];
  $model = $_GET['model'];
  $yearStart = $_GET['yearStart'];
  $yearEnd = $_GET['yearEnd'];
  $horseStart = $_GET['horseStart'];
  $horseEnd = $_GET['horseEnd'];

  $sql = "SELECT cars.*, manufacturer.name AS manufacturer_name FROM cars JOIN manufacturer ON cars.manufacturer_id = manufacturer.id WHERE 1=1";
  if (!empty($manufacturer)) {
    $sql .= " AND manufacturer.name LIKE '%$manufacturer%'";
    $title .= " " . $manufacturer;
  }
  if (!empty($model)) {
    $sql .= " AND cars.name LIKE '%$model%'";
    $title .= " " . $model;
  }
  if (!empty($yearStart)) {
    $sql .= " AND cars.year >= $yearStart";
    $title .= " " . $yearStart;
  }
  if (!empty($yearEnd)) {
    $sql .= " AND cars.year <= $yearEnd";
    $title .= " " . $yearEnd;
  }
  if (!empty($horseStart)) {
    $sql .= " AND cars.horsepower >= $horseStart";
    $title .= " " . $horseStart;
  }
  if (!empty($horseEnd)) {
    $sql .= " AND cars.horsepower <= $horseEnd";
    $title .= " " . $horseEnd;
  }
}

$result = mysqli_query($conn, $sql);

// Создание массива с данными
$cars = array();
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $cars[] = array(
      'name' => $row['name'],
      'horsepower' => $row['horsepower'],
      'year' => $row['year'],
      'color' => $row['color'],
      'photo' => $row['photo'],
      'id' => $row['id'],
    );
  }
}


$sql1 = "SELECT * FROM manufacturer";
$result1 = mysqli_query($conn, $sql1);
// Создание массива с данными
$cars1 = array();
if (mysqli_num_rows($result1) > 0) {
  while ($row = mysqli_fetch_assoc($result1)) {
    $cars1[] = array(
      'name' => $row['name'],
      'image' => 'img/' . strtolower($row['photo'])
    );
  }
}


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
  <title><?php echo ($query) ? "Поиск: " . $query : "Поиск по характеристикам"; ?></title>
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
  <main class="main">
    <section class="search section">
      <div class="search-select-brand">
        <div class="custom-select">
          <select id="brand-select">
            <option value="0">Марка</option>
            <?php foreach ($cars1 as $car) : ?>
              <option value="<?php echo $car['name']; ?>"><?php echo $car['name']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="search-select-model">
        <div class="custom-select">
          <select id="model-select">
            <option value="0">Модель</option>
          </select>
        </div>
      </div>
      <div class="search-year">
        <div class="search-year-start">
          <input type="number" class="search-year__input" placeholder="Год от" />
        </div>
        <div class="search-year-end">
          <input type="number" class="search-year__input" placeholder="Год до" />
        </div>
      </div>
      <div class="search-year">
        <div class="search-year-start search-horse-start">
          <input type="number" class="search-year__input" placeholder="Л.с от" />
        </div>
        <div class="search-year-end search-horse-end">
          <input type="number" class="search-year__input" placeholder="Л.с до" />
        </div>
      </div>
      <div class="search-submit">
        <button type="submit" class="search-submit__button">ПОИСК</button>
      </div>
    </section>
    <?php if (empty($cars)) { ?>
      <h1>Ничего не найдено!</h1>
    <?php } else { ?>
      <h1>Модели:</h1>
      <section class="cars">
        <?php foreach ($cars as $car) : ?>
          <div class="cars-car">
            <a href="/car.php?id=<?php echo $car['id'] ?>">
              <img src="<?php echo $car['photo']; ?>" alt="" />
            </a>
            <h2><?php echo $car['name']; ?></h2>
            <p>Год выпуска: <?php echo $car['year']; ?></p>
            <p>Цвет: <?php echo $car['color']; ?></p>
            <p>Мощность: <?php echo $car['horsepower']; ?> л.с</p>
          </div>
        <?php endforeach; ?>
      </section>
    <?php } ?>
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