<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $id = (int) $_POST['id'];

  $basket = Basket::init();
  $basket = Basket::add($id, $basket);
  Basket::save($basket);

  $_SESSION['basket_message'] = 'Товар добавлен в корзину!';

  $referer = $_SERVER['HTTP_REFERER'] ?? '/';
  header("Location: $referer");
  exit;
}
