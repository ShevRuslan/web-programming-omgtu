<?php
session_start();

// Проверка, если пользователь уже вошел, перенаправьте его на admin.php
if (isset($_SESSION['user'])) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Статически заданные учетные данные
    $staticUsername = 'admin';
    $staticPassword = '12345';

    // Проверка учетных данных
    if ($username == $staticUsername && $password == $staticPassword) {
        // Учетные данные верны, устанавливаем сеанс
        $_SESSION['user'] = $username;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Неверные учетные данные";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css" />
    <title>Login</title>
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
    <h1>Авторизация</h1>
    <form method="post" action="" class="form">
        <div class="wrapper-input">
        <label for="username">Логин:</label>
        <input type="text" name="username" required>
        </div>
<div class="wrapper-input">
<label for="password">Пароль:</label>
        <input type="password" name="password" required>
</div>

        <div class="wrapper-input">
        <input type="submit" value="Авторизоваться" class="add__button search-submit__button">

        </div>
    </form>
    <div class="error">
    <?php
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>
    </div>
    </main>
</body>
</html>
