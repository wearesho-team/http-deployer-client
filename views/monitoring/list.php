<?php

use Wearesho\Deployer;

/** @var \yii\web\View $this */
/** @var array $data */

?>

<section class="container">
    <div class="card card-light bg-light">
        <div class="card-body">
            <h4 class="card-title">
                Контейнеры
            </h4>
            <div class="card-text">
                <?php foreach ($data as $project) echo $this->render('list/project', $project) ?>
            </div>
        </div>
    </div>
</section>
