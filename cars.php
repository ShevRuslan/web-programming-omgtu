<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);

// Получение значения параметра manufacturer
$manufacturer = $_GET['manufacturer'];

// Выполнение запроса к базе данных
$sql = "SELECT * FROM cars WHERE manufacturer_id = (SELECT id FROM manufacturer WHERE name = '$manufacturer')";
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
  <link rel="stylesheet" href="/css/main.css" />
  <title><?php echo $manufacturer; ?></title>
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
          <img src="/img/telegramm.svg" alt="" />
          <img src="/img/vk.svg" alt="" />
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
  </main>
  <footer class="footer">
    <div class="footer-menu">
      <span>(c) 2023 MyCars</span>
      <span>+7 999 99 99</span>
    </div>
  </footer>
  <script src="/js/script.js"></script>
</body>

</html>