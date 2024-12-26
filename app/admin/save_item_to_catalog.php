<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = Cleaner::str2db($_POST["title"], $db);
  $author = Cleaner::str2db($_POST["author"], $db);
  $price = Cleaner::uint($_POST["price"]);
  $pubyear = Cleaner::uint($_POST["pubyear"]);

  $book = new Book($title, $author, $price, $pubyear);
  if (Eshop::addItemToCatalog($book, $db)) {
    header("Location: /admin/add_item_to_catalog");
    exit();
  }
}