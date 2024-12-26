<?php

class Eshop
{
    static function init(array $opt)
    {
        return new PDO(
            "mysql:host=" . $opt["HOST"] . ";dbname=" . $opt["NAME"],
            $opt["USER"],
            $opt["PASS"]
        );
    }

    static function addItemToCatalog(Book $book, PDO $db)
    {
        $sql = "call spAddItemToCatalog({$book->title}, {$book->author}, {$book->price}, {$book->pubyear});";
        try {
            return (bool) $db->exec($sql);
        } catch (PDOException $e) {
            trigger_error("Не удалось добавить книгу в каталог.", E_USER_ERROR);
        }
    }

    static function getItemsFromCatalog(PDO $db)
    {
        $sql = "call spGetCatalog();";
        $result = null;
        try {
            $result = $db->query($sql, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Не удалось прочитать каталог.", E_USER_ERROR);
        }

        $data = [];
        foreach ($result as $row) {

            $data[] = new Book(
                $row["title"],
                $row["author"],
                $row["price"],
                $row["pubyear"],
                $row["id"]
            );
        }

        return $data;
    }

    static function addItemToBasket(int $id, array &$basket)
    {
        if (empty($basket)) {
            $basket = Basket::init();
        }
        
        $basket = Basket::add($id, $basket);
        
        Basket::save($basket);
    }

    static function removeItemFromBasket(int $id, array $basket)
    {
        $basket = Basket::remove($id, $basket);
        Basket::save($basket);
    }

    static function getItemsFromBasket(array $basket, PDO $db)
    {
        if (empty($basket)) {
            throw new Exception("Не удалось прочитать корзину.");
        }

        $items = [];
        foreach ($basket as $id => $quantity) {
            
            if ($id === "order-id") {
                continue;
            }

          
            $stmt = $db->prepare("SELECT * FROM catalog WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $book = new Book(
                    $row['title'],
                    $row['author'],
                    $row['price'],
                    $row['pubyear'],
                    $row['id']
                );

                $items[] = (object) [
                    'book' => $book,
                    'quantity' => $quantity,
                ];
            }
        }

        return $items;
    }
    static function saveOrder(Order $order, PDO $db)
    {
        $sql = "call spSaveOrder({$order->order_id}, {$order->customer}, {$order->email}, {$order->phone}, {$order->address});";
        echo $sql;
        try {
            $db->exec($sql);
        } catch (PDOException $e) {
            trigger_error("Не удалось добавить заказ.", E_USER_ERROR);

        }
        foreach ($order->items as $book) {
            $sql = "call spSaveOrderedItems({$order->order_id}, {$book->id}, 1);";
            try {
                $db->exec($sql);
            } catch (PDOException $e) {
                trigger_error("Не удалось добавить книгу в заказ.", E_USER_ERROR);
            }
        }
        $newBasket = Basket::create();
        Basket::save($newBasket);
    }

    static function getOrders(PDO $db)
    {
        $sql = "call spGetOrders();";
        $orders = null;
        try {
            $orders = $db->query($sql, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Не удалось прочитать заказы.", E_USER_ERROR);
        }

        $data = [];
        $ids = "";
        foreach ($orders as $row) {
            $data[$row["order_id"]] = new Order(
                $row["order_id"],
                $row["customer"],
                $row["email"],
                $row["phone"],
                $row["address"],
                [],
                $row["id"],
                $row["created"]
            );
            $ids = $ids . "," . $row["order_id"];
        }
        $orders->closeCursor();

        $ids = $db->quote($ids);
        $sql = "call spGetOrderedItems({$ids});";
        $orderedItems = null;
        try {
            $orderedItems = $db->query($sql, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Не удалось прочитать книги из заказа.", E_USER_ERROR);
        }

        foreach ($orderedItems as $orderedItem) {
            $data[$orderedItem["order_id"]]->items[] = new Book(
                $orderedItem["title"],
                $orderedItem["author"],
                $orderedItem["price"],
                $orderedItem["pubyear"]
            );
        }

        return $data;
    }

    static function userAdd(User $user, PDO $db)
    {
        $sql = "call sp_save_admin({$user->login}, {$user->password_hash}, {$user->email});";
        try {
            return (bool) $db->exec($sql);
        } catch (PDOException $e) {
            trigger_error("Не удалось создать пользователя", E_USER_ERROR);
        }
    }

    static function userCheck(User $user, PDO $db)
    {
        $sql = "call sp_get_admin(:login)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':login', $user->login);

            echo "Логин из формы: " . htmlspecialchars($user->login) . "<br>";

            $stmt->execute();
            $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dbUser) {
                echo "Пользователь не найден.<br>";
                return false;
            }

            echo "Хэш из базы: " . $dbUser["password"] . "<br>";
            echo "Введенный пароль: " . htmlspecialchars($user->password) . "<br>";

            if (!password_verify($user->password, $dbUser["password"])) {
                echo "Пароль не совпадает.<br>";
                return false;
            }

            return true;
        } catch (PDOException $e) {
            echo "Ошибка PDO: " . $e->getMessage();
            return false;
        }
    }



    static function userGet(User $user, PDO $db)
    {
        $sql = "call sp_get_admin({$user->login})";
        try {
            $dbUser = ($db->query($sql))->fetch(PDO::FETCH_ASSOC);
            return new User(
                $dbUser["login"],
                $dbUser["email"],
                $user->password,
                $dbUser["password"]
            );
        } catch (PDOException $e) {
            throw new Exception("Не удалось получить пользователя");
        }
    }

    static function createHash(string $password)
    {
        return password_hash(
            $password,
            PASSWORD_BCRYPT,
            ["cost" => 12]
        );
    }

    static function isAdmin()
    {
        return isset($_SESSION["admin"]);
    }

    static function logIn(User $user)
    {
        $_SESSION["admin"] = $user;
    }

    static function logOut()
    {
        session_unset();
    }
}