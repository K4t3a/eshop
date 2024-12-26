<?php
require_once __DIR__ . "/../core/init.php";

$catalogData = $db->query("SELECT * FROM catalog")->fetchAll(PDO::FETCH_OBJ);

$basket = Basket::init();

function getBasketCount(array $basket): int
{
    return count(array_filter($basket, function ($key) {
        return $key !== "order-id"; 
    }, ARRAY_FILTER_USE_KEY));
}
?>
<h1>Каталог товаров</h1>
<p class="admin"><a href="/admin">Админка</a></p>
<p>Товаров в <a href="/basket">корзине</a>: <?php echo getBasketCount($basket); ?></p>
<table border="2" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Название</th>
            <th>Автор</th>
            <th>Год издания</th>
            <th>Цена, руб.</th>
            <th>В корзину</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($catalogData as $book): ?>
            <tr>
                <td><?php echo htmlspecialchars($book->title); ?></td>
                <td><?php echo htmlspecialchars($book->author); ?></td>
                <td><?php echo htmlspecialchars($book->pubyear); ?></td>
                <td><?php echo htmlspecialchars($book->price); ?></td>
                <td>
                    <form action="/add_item_to_basket" method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($book->id); ?>">
                        <button type="submit">Добавить в корзину</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>