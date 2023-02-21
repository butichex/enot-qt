<?php
require_once 'boot.php';

$user = null;
init_db();

if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}


?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Enot.io QT</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href='https://css.gg/arrows-exchange-alt.css' rel='stylesheet'>

</head>
<body>

<div class="container">
  <div class="row py-5">
    <div class="col-lg-6">

        <?php if ($user) { ?>

          <h1>Добро пожаловать, <?=htmlspecialchars($user['username'])?>!</h1>

            <div class="wrapper">
                <header>Конвертер валют</header>
                <form action="#">
                    <div class="amount">
                        <p>Ввведите сумму</p>
                        <input type="text" value="1">
                    </div>
                    <div class="drop-list">
                        <div class="from">
                            <p>Из</p>
                            <div class="select-box">
                                <img src="https://flagcdn.com/48x36/us.png" alt="flag">
                                <select> <!-- Options tag are inserted from JavaScript --> </select>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="gg-arrows-exchange-alt"></i>
                        </div>
                        <div class="to">
                            <p>В</p>
                            <div class="select-box">
                                <img src="https://flagcdn.com/48x36/ru.png" alt="flag">
                                <select> <!-- Options tag are inserted from JavaScript --> </select>
                            </div>
                        </div>
                    </div>
                    <div class="exchange-rate">Загружаем курс валют...</div>
                    <button>Получить курс</button>
                </form>
            </div>



          <form class="mt-5" method="post" action="controllers/do_logout.php">
            <button type="submit" class="btn btn-primary">Выйти</button>
          </form>

        <?php } else { ?>

          <h1 class="mb-5">Регистрация</h1>

            <?php flash(); ?>

          <form method="post" action="controllers/do_register.php">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
              <a class="btn btn-outline-primary" href="views/login.php">Уже зарегистрированы? Войти</a>
            </div>
          </form>

        <?php } ?>

    </div>
  </div>
</div>
<script src="public/js/script.js"></script>
</body>
</html>
