<h1>Добавить товар в каталог</h1>
<p><a href='/admin'>Назад в админку</a></p>    
<form action="save_item_to_catalog" method="post">
    <div>
        <label>Название:</label> 
        <input type="text" name="title" size="50">
    </div>
    <div>
        <label>Автор:</label>
        <input type="text" name="author" size="50">
    </div>
    <div>
        <label>Год издания:</label> 
        <input type="text" name="pubyear" size="50" maxlength="4">
    </div>
    <div>
        <label>Цена (руб.):</label> 
        <input type="text" name="price" size="50" maxlength="6">
    </div>
    <div>
        <input type="submit" value="Добавить">
    </div>
</form>
