<?php

class Order
{
  public string $order_id;
  public string $customer;
  public string $email;
  public string $phone;
  public string $address;
  public array $items;
  public int $id;
  public string $created;

  function __construct(string $order_id, string $customer, string $email, string $phone, string $address, array $items, int $id = 0, int $timestamp = null)
  {
    $this->order_id = $order_id;
    $this->customer = $customer;
    $this->email = $email;
    $this->phone = $phone;
    $this->address = $address;
    $this->items = $items;
    $this->id = $id;
    $this->created = date("d-m-Y H:i:s", $timestamp);
  }
}