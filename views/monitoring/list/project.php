<?php

use Wearesho\Deployer\Repositories\Container\Entity;

/**
 * @var string $project
 * @var array $servers
 */
?>
<h5>Проект <?= $project ?></h5>
<hr>
<div class="row">
    <?php foreach ($servers as ['hostName' => $hostName, 'containers' => $containers]): ?>
        <div class="col-md-6">
            <div class="card<?= count($containers) > 0 ? "" : " border-warning"?>">
                <div class="card-body">
                    <h6 class="card-title">
                        <?= $hostName ?>
                    </h6>
                    <div class="card-text">
                        <?php if (count($containers) > 0): ?>
                            <table class="table table-striped table-responsive">
                                <thead class="thead-light">
                                <tr>
                                    <th>Название</th>
                                    <th>Статус</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var Entity $container ?> */ ?>
                                <?php foreach ($containers as $container): ?>
                                    <tr data-key="<?= $container->getName() ?>"
                                        class="<?= $container->isUp() ? "table-success" : "table-danger" ?>">
                                        <td><?= $container->getName() ?></td>
                                        <td><?= $container->getStatus() ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                Контекнеры не найдены или не запущены
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
