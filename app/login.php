<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = Cleaner::str2db($_POST["login"], $db);
    $password = Cleaner::str2db($_POST["password"], $db);

    $user = new User(
        $login,
        "",
        $password,
        password_hash: ""
    );

    echo Eshop::createHash($password);

    if (Eshop::userCheck($user, $db)) {
        $user = Eshop::userGet($user, $db);
        Eshop::logIn($user);
        header("Location: /admin");
        exit();
    } else {
        echo "<p>Неверный логин или пароль</p>";
        echo "<p>Вернуться в <a href='/catalog'>каталог</a></p>";
        echo "<a href='/enter'>Повторить</a>";
    }
}