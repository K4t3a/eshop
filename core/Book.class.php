<?php

class Book
{
  public string $title;
  public string $author;
  public int $price;
  public int $pubyear;
  public int $id;

  function __construct(string $title, string $author, int $price, int $pubyear, int $id = 0)
  {
    $this->title = $title;
    $this->author = $author;
    $this->price = $price;
    $this->pubyear = $pubyear;
    $this->id = $id;
  }
}