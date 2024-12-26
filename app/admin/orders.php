<?php
$orders = Eshop::getOrders($db);

function show_order(Order $order, int $num)
{
    $itemsHTML = "";
    foreach ($order->items as $item) {
        $itemsHTML .= "
        <tr>
            <td>{$item->title}</td>
            <td>{$item->author}</td>
            <td>{$item->price}</td>
            <td>{$item->quantity}</td>
        </tr>";
    }
    return "
    <div class='order'>
        <h3>Заказ #{$num}</h3>
        <p>Клиент: {$order->customer}</p>
        <p>Email: {$order->email}</p>
        <p>Телефон: {$order->phone}</p>
        <p>Адрес: {$order->address}</p>
        <p>Создан: {$order->created}</p>
        <table>
            <tr>
                <th>Название</th>
                <th>Автор</th>
                <th>Цена</th>
                <th>Количество</th>
            </tr>
            {$itemsHTML}
        </table>
    </div>";
}
?>
<h1>Заказы</h1>
<div class='orders'>
    <?php
    $counter = 1;
    foreach ($orders as $order) {
        echo show_order($order, $counter++);
    }
    ?>
</div>
