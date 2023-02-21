<?php

session_start();

function pdo(): PDO
{
    static $pdo;

    if (!$pdo) {
        $config = include 'config/config.php';
        // Подключение к БД
        $dsn = 'mysql:dbname='.$config['db_name'].';host='.$config['db_host'];
        $pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $pdo;
}

function create_users_table(): void {
    $stmt = pdo()->prepare("CREATE TABLE IF NOT EXISTS users (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL
        );");
    $stmt->execute();
}

function create_currencies_table(): void {
    $stmt = pdo()->prepare("CREATE TABLE IF NOT EXISTS currency (
            NumCode INT(11),
            CharCode VARCHAR(3),
            Nominal INT(11),
            Name VARCHAR(50),
            Value FLOAT
        );");
    $stmt->execute();

}


function flash(?string $message = null)
{
    if ($message) {
        $_SESSION['flash'] = $message;
    } else {
        if (!empty($_SESSION['flash'])) { ?>
            <div class="alert alert-danger mb-3">
                <?=$_SESSION['flash']?>
            </div>
        <?php }
        unset($_SESSION['flash']);
    }
}

function check_auth(): bool
{
    return !!($_SESSION['user_id'] ?? false);
}


function init_db(): void {
    create_currencies_table();
    create_users_table();
}
