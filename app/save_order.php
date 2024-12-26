<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $customer = Cleaner::str2db($_POST["customer"], $db);
  $email = Cleaner::str2db($_POST["email"], $db);
  $phone = Cleaner::str2db($_POST["phone"], $db);
  $address = Cleaner::str2db($_POST["address"], $db);

  $books = Eshop::getItemsFromBasket($basket, $db);

  $order = new Order(
    Cleaner::str2db($basket["order-id"], $db),
    $customer,
    $email,
    $phone,
    $address,
    $books
  );

  Eshop::saveOrder($order, $db);

  header("Location: /catalog");
  exit();
}