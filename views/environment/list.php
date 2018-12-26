<?php

use Wearesho\Deployer;

/** @var Deployer\Repositories\Environment\Entity[][] $environment */
/** @var Deployer\Models\Project $project */
/** @var string[] $servers */

?>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                Окружение проекта
                <?= htmlspecialchars($project->name) ?>
                <a
                        href="/environment/put?<?= http_build_query(['project' => $project->name]) ?>"
                        class="btn btn-success btn-sm pull-right"
                >
                    Добавить
                </a>
            </h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Ключ</th>
                    <?php foreach ($servers as $server): ?>
                        <th><?= $server ?></th>
                    <?php endforeach; ?>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($environment as $key => $entities): ?>
                    <tr data-key="<?= $key ?>">
                        <td><?= $key ?></td>
                        <?php foreach ($servers as $server): ?>
                            <td><?= $entities[$server] ?? '' ?></td>
                        <?php endforeach; ?>
                        <td>
                            <form
                                    action="/environment/delete?<?= http_build_query([
                                        'project' => $project->name,
                                        'key' => $key,
                                    ]) ?>"
                                    method="post"
                                    style="display:inline;"
                            >
                                <input
                                        id="form-token"
                                        type="hidden"
                                        name="<?= Yii::$app->request->csrfParam ?>"
                                        value="<?= Yii::$app->request->csrfToken ?>"
                                />
                                <button type="submit" class="btn btn-sm btn-danger pull-right">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <a href="/environment/put?<?= http_build_query([
                                'project' => $project->name,
                                'key' => $key,
                            ]) ?>" class="btn btn-sm btn-primary pull-right">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
