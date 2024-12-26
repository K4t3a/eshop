<?php
class User
{
  public string $login;
  public string $password;
  public string $email;
  public string $password_hash;

  function __construct(string $login, string $email, string $password, string $password_hash)
  {
    $this->login = $login;
    $this->email = $email;
    $this->password = $password;
    $this->password_hash = $password_hash;
  }
}