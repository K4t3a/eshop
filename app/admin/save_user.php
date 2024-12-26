<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $login = Cleaner::str2db($_POST["login"], $db);
  $password = Cleaner::str2db($_POST["password"], $db);
  $email = Cleaner::str2db($_POST["email"], $db);
  $passwordHash = Cleaner::str2db(Eshop::createHash($password), $db);

  $user = new User(
    $login,
    $email,
    $password,
    $passwordHash
  );

  if (Eshop::userAdd($user, $db)) {
    header("Location: /admin/create_user");
    exit();
  }
}