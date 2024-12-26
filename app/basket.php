<?php
require_once __DIR__ . "/../core/init.php";

try {
    $basket = Basket::init();
    $basketItems = Eshop::getItemsFromBasket($basket, $db);
} catch (Exception $e) {
    $basketItems = [];
    error_log(date("Y-m-d H:i:s") . " - " . $e->getMessage(), 3, ERROR_LOG);
}

function build_basket(int $counter, Book $book, int $quantity)
{
    return "
    <tr>
        <td>{$counter}</td>
        <td>{$book->title}</td>
        <td>{$book->author}</td>
        <td>{$book->price}</td>
        <td>{$book->pubyear}</td>
        <td>{$quantity}</td>
        <td>
            <form method='post' action='/remove_item_from_basket'>
                <input type='hidden' name='id' value='{$book->id}'>
                <button type='submit'>Удалить</button>
            </form>
        </td>
    </tr>";
}
?>

<h1>Корзина</h1>
<?php if (empty($basketItems)): ?>
    <p>Корзина пуста.</p>
<?php else: ?>
    <table>
        <tr>
            <th>#</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Цена</th>
            <th>Год публикации</th>
            <th>Количество</th>
            <th>Действия</th>
        </tr>
        <?php
        $counter = 1;
        foreach ($basketItems as $item) {
            echo build_basket($counter++, $item->book, $item->quantity);
        }
        ?>
    </table>
<?php endif; ?>
<p><a href="/catalog">Назад к каталогу</a></p>
<p><a href="/create_order">Оформить заказ</a></p>
