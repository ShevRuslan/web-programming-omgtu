<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "web";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM manufacturer";
$result = mysqli_query($conn, $sql);
// Создание массива с данными
$cars = array();
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $cars[] = array(
      'name' => $row['name'],
      'image' => 'img/' . strtolower($row['photo'])
    );
  }
}
$sql1 = "SELECT * FROM cars ";
$cars1 = mysqli_query($conn, $sql1);
$sql_manufacturers = "SELECT * FROM manufacturer";
$result_manufacturers = mysqli_query($conn, $sql_manufacturers);
// Закрытие соединения
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/main.css" />
  <title>Админка</title>
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
    <h1>Админка</h1>
    <section class="add">
      <form id="carForm" action="admin_process.php" method="post" enctype="multipart/form-data" class="form">
        <h2>Добавить машину</h2>

        <div class="wrapper-input">
          <label for="car_name">Название:</label>
          <input type="text" name="car_name" required>
        </div>
        <div class="wrapper-input">
          <label for="vin">VIN:</label>
          <input type="text" name="vin" required>
        </div>

        <div class="wrapper-input">
          <label for="horsepower">Л.с:</label>
          <input type="number" name="horsepower" required>
        </div>
        <div class="wrapper-input">
          <label for="year">Год выпуска:</label>
          <input type="number" name="year" required>
        </div>
        <div class="wrapper-input">
          <label for="color">Цвет:</label>
          <input type="text" name="color" required>
        </div>
        <div class="wrapper-input">
          <label for="photo">Фотография:</label>
          <input type="file" name="car_photo" accept="image/*" required>
        </div>
        <div class="wrapper-input">
          <label for="description">Описание:</label>
          <textarea name="description" rows="4"></textarea>
        </div>
        <div class="wrapper-input">
          <label for="manufacturer_id">Производитель:</label>
          <select name="manufacturer_id" required>
            <?php
            // Выводите опции производителей из базы данных
            while ($row_manufacturer = mysqli_fetch_assoc($result_manufacturers)) {
              echo "<option value='{$row_manufacturer['id']}'>{$row_manufacturer['name']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="wrapper-input">
          <input type="submit" name="add_car" value="Добавить машину" class="add__button search-submit__button">

        </div>
      </form>


      <!-- Форма для добавления нового производителя -->
      <form id="manufacturerForm" action="admin_process.php" method="post" enctype="multipart/form-data" class="form">
        <h2>Добавить производителя</h2>
        <div class="wrapper-input">
          <label for="manufacturer_name">Название:</label>
          <input type="text" name="manufacturer_name" required>
        </div>
        <div class="wrapper-input">
          <label for="manufacturer_photo">Фото:</label>
          <input type="file" name="manufacturer_photo" accept="image/*" required>
        </div>
        <div class="wrapper-input">
          <input type="submit" name="add_manufacturer" value="Добавить производителя" class="add__button search-submit__button">

        </div>
      </form>
    </section>
    <h1>Производители</h1>
    <section class="brands">

    </section>
    <h2>Модели</h2>

    <section class="models" style="margin-top: 100px">

    </section>
  </main>
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <form id="editForm" class="form" enctype="multipart/form-data">
        <!-- Ваш код для формы редактирования -->
      </form>
    </div>
  </div>
  <script>
    function updateModels() {
      var modelsSection = document.getElementById('modelsSection');

      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'get_manufacturers_json.php', true);

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var data = JSON.parse(xhr.responseText);
          renderModels(data);
        }
      };

      xhr.send();
    }

    function renderModels(data) {
      var modelsSection = document.querySelector('.brands');
      modelsSection.innerHTML = ''; // Очищаем содержимое секции перед добавлением новых данных

      data.forEach(function(car) {
        var modelDiv = document.createElement('div');
        modelDiv.classList.add('brands-brand');

        var link = document.createElement('a');
        link.href = `car/${car.name}`

        var image = document.createElement('img');
        image.src = car.image;
        image.alt = car.name;

        var heading = document.createElement('h2');
        heading.textContent = car.name;

        var deleteButton = document.createElement('button');
        deleteButton.classList.add('delete-button');
        deleteButton.dataset.carId = car.id;
        deleteButton.textContent = 'Удалить';

        deleteButton.addEventListener("click", () => {
          var confirmation = confirm('Вы уверены, что хотите удалить эту машину?');

          if (confirmation) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'admin_process.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
              if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);

                if (response.success) {
                  updateModels();
                  // Обработка успешного удаления (можете обновить страницу или скрыть блок с удаленной машиной)
                  console.log('Машина успешно удалена');
                } else {
                  console.error('Произошла ошибка при удалении машины');
                }
              }
            };

            xhr.send('action=delete&manufacturer_id=' + car.id);
          }
        })



        var editButton = document.createElement('button');
        editButton.classList.add('edit-button');
        editButton.dataset.carId = car.id;
        editButton.textContent = 'Редактировать';

        editButton.addEventListener("click", () => {
          openEditModal(car.id)
        })


        link.appendChild(image);
        modelDiv.appendChild(link);
        modelDiv.appendChild(heading);
        modelDiv.appendChild(deleteButton);
        modelDiv.appendChild(editButton);

        modelsSection.appendChild(modelDiv);
      });
    }

    function updateCars() {

      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'get_cars_json.php', true);

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var data = JSON.parse(xhr.responseText);
          renderCars(data);
        }
      };

      xhr.send();
    }

    function renderCars(data) {
      var modelsSection = document.querySelector('.models');
      modelsSection.innerHTML = ''; // Очищаем содержимое секции перед добавлением новых данных

      data.forEach(function(car) {
        var modelDiv = document.createElement('div');
        modelDiv.classList.add('models-model');

        var link = document.createElement('a');
        link.href = '/car.php?id=' + car.id;

        var image = document.createElement('img');
        image.src = car.image;
        image.alt = car.name;

        var heading = document.createElement('h2');
        heading.textContent = car.name;

        var deleteButton = document.createElement('button');
        deleteButton.classList.add('delete-button');
        deleteButton.dataset.carId = car.id;
        deleteButton.textContent = 'Удалить';

        deleteButton.addEventListener("click", () => {
          var confirmation = confirm('Вы уверены, что хотите удалить эту машину?');

          if (confirmation) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'admin_process.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
              if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);

                if (response.success) {
                  updateCars();
                  // Обработка успешного удаления (можете обновить страницу или скрыть блок с удаленной машиной)
                  console.log('Машина успешно удалена');
                } else {
                  console.error('Произошла ошибка при удалении машины');
                }
              }
            };

            xhr.send('action=delete&car_id=' + car.id);
          }
        })



        var editButton = document.createElement('button');
        editButton.classList.add('edit-button');
        editButton.dataset.carId = car.id;
        editButton.textContent = 'Редактировать';

        editButton.addEventListener("click", () => {
          openEditModalManufacturer(car.id)
        })


        link.appendChild(image);
        modelDiv.appendChild(link);
        modelDiv.appendChild(heading);
        modelDiv.appendChild(deleteButton);
        modelDiv.appendChild(editButton);

        modelsSection.appendChild(modelDiv);
      });
    }



    document.addEventListener('DOMContentLoaded', function() {
      updateModels()
      updateCars()
      var manufacturerForm = document.getElementById('manufacturerForm');

      manufacturerForm.addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(manufacturerForm);
        formData.append('add_manufacturer', '1'); // Добавляем параметр для идентификации формы

        var xhr = new XMLHttpRequest();
        xhr.open('POST', manufacturerForm.action, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // Обработка успешного ответа от сервера
            updateModels()
            console.log(xhr.responseText);
            // Можете добавить дополнительные действия, если необходимо
          }
        };

        xhr.send(formData);
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      var form = document.getElementById('carForm');

      form.addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(form);
        formData.append('add_car', '1');
        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            updateCars();
            // Обработка успешного ответа от сервера
            console.log(xhr.responseText);
            // Можете добавить дополнительные действия, если необходимо
          }
        };

        xhr.send(formData);
      });
    });



    var editModal = document.getElementById('editModal');
    var editForm = document.getElementById('editForm');

    function openEditModalManufacturer(carId) {
      // Здесь используется fetch для получения данных машины по ID
      fetch('get_car_data.php?car_id=' + carId)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Заполнение данных в форме
            editForm.innerHTML = `
              <!-- Замените эти значения на данные из вашей базы данных -->
              <input type="hidden" name="id" value="${carId}" required>
              <input type="text" name="name" value="${data.car.name}" required>
              <input type="text" name="vin" value="${data.car.vin}" required>
              <input type="text" name="color" value="${data.car.color}" required>
              <input type="text" name="horsepower" value="${data.car.horsepower}" required>
              <input type="text" name="year" value="${data.car.year}" required>
              <!-- ... Другие поля ... -->
              <input type="submit" name="edit_car" value="Сохранить изменения" class="edit__button search-submit__button">
            `;

            // Отображение модального окна
            editModal.style.display = 'block';
          } else {
            console.error('Ошибка при загрузке данных машины');
          }
        })
        .catch(error => console.error('Ошибка при загрузке данных', error));
    }

    // Обработка закрытия модального окна
    var closeBtn = document.querySelector('.close');
    closeBtn.addEventListener('click', function() {
      editModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
      if (event.target == editModal) {
        editModal.style.display = 'none';
      }
    });

    // Добавьте обработчик отправки формы для редактирования
    editForm.addEventListener('submit', function(event) {
      event.preventDefault();

      var formData = new FormData(editForm);
      formData.append('action', 'edit'); // Добавьте действие "edit" для обработки на сервере

      fetch('admin_process.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            updateModels()
            updateCars()
            console.log('Машина успешно отредактирована');
            // Дополнительные действия после успешного редактирования
          } else {
            console.error('Ошибка при редактировании машины');
          }
        })
        .catch(error => console.error('Ошибка при отправке данных', error));
    });








    var editModal = document.getElementById('editModal');
    var editForm = document.getElementById('editForm');

    function openEditModal(carId) {
      // Здесь используется fetch для получения данных машины по ID
      fetch('get_car_data.php?manufacturer_id=' + carId)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Заполнение данных в форме
            editForm.innerHTML = `
              <!-- Замените эти значения на данные из вашей базы данных -->
              <input type="hidden" name="manufacturer_id" value="${carId}" required>
              <input type="text" name="name" value="${data.car.name}" required>
              <input type="submit" name="edit_car" value="Сохранить изменения" class="edit__button search-submit__button">
            `;

            // Отображение модального окна
            editModal.style.display = 'block';
          } else {
            console.error('Ошибка при загрузке данных машины');
          }
        })
        .catch(error => console.error('Ошибка при загрузке данных', error));
    }

    // Обработка закрытия модального окна
    var closeBtn = document.querySelector('.close');
    closeBtn.addEventListener('click', function() {
      editModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
      if (event.target == editModal) {
        editModal.style.display = 'none';
      }
    });

    // Добавьте обработчик отправки формы для редактирования
    editForm.addEventListener('submit', function(event) {
      event.preventDefault();

      var formData = new FormData(editForm);
      console.log(formData)
      formData.append('action', 'edit'); // Добавьте действие "edit" для обработки на сервере

      fetch('admin_process.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            updateModels()
            updateCars()
            console.log('Машина успешно отредактирована');
            // Дополнительные действия после успешного редактирования
          } else {
            console.error('Ошибка при редактировании машины');
          }
        })
        .catch(error => console.error('Ошибка при отправке данных', error));
    });
  </script>
</body>

</html>