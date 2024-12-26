<?php

class Basket
{
  static function init()
  {
    if (isset($_COOKIE["eshop"])) {
      $basket = json_decode($_COOKIE["eshop"], true);
      if (!is_array($basket)) {
        $basket = ["order-id" => uniqid(rand(5, 30))];
      }
    } else {
      $basket = ["order-id" => uniqid(rand(5, 30))];
    }
    return $basket;
  }
  static function add(int $id, array $basket)
  {
    if (isset($basket[$id])) {
      $basket[$id]++;
    } else {
      $basket[$id] = 1;
    }
    return $basket;
  }

  static function remove(int $id, array $basket)
  {
    unset($basket[$id]);
    return $basket;
  }

  static function create()
  {
    return [
      "order-id" => uniqid(rand(5, 30))
    ];
  }

  static function save(array $basket)
  {
    setcookie("eshop", json_encode($basket), time() + 3600 * 24, "/");
  }

  static function read(): array
  {
    $data = json_decode($_COOKIE["eshop"] ?? "[]", true);
    if (!is_array($data)) {
      error_log("Ошибка чтения куков: данные не являются массивом.");
      return [];
    }
    return $data;
  }
}
