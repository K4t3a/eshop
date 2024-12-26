<?php
require_once __DIR__ . "/../core/init.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $id = (int) $_POST['id']; // Получение ID товара

  // Удаление товара из корзины через Eshop
  Eshop::removeItemFromBasket($id, $basket);

  // Перенаправление обратно в корзину
  header("Location: /basket");
  exit;
} else {
  // Если запрос некорректный, перенаправление на корзину
  header("Location: /basket");
  exit;
}
