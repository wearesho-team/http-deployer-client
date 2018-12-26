<?php
/** @var int $count */

$this->title = "Импорт БД";

?>

Количество записей: <?= $count ?> <br>
<h4>Загрузка базы</h4>
<form action="/import" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="dbFileInput">Выберете CSV файл базы для импорта</label>
        <input type="file" class="form-control-file" name="db" id="dbFileInput">
        <small id="dbFileInputHelp" class="form-text text-muted">
            дата;скоринговый бал;просрочка;статус
        </small>
    </div>
    <input type="submit" name="submit" class="btn btn-success"/>
</form>
