<?php

use Wearesho\Deployer;

/** @var string[] $previous */
/** @var string $key */
/** @var Deployer\Models\Project $project */

?>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                Успешно удалено
                <b><?= $key ?></b> -
                <?= htmlspecialchars($project->name) ?>
            </h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Сервер</th>
                    <th>Предыдущее значение</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($previous as $server => $value): ?>
                    <tr data-key="<?= $server ?>">
                        <td><?= $server ?></td>
                        <td><?= $value ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <hr>
            <a href="/environment?project=<?= htmlspecialchars($project->name) ?>" class="btn btn-primary">
                Назад
            </a>
        </div>
    </div>
</section>
