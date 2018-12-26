<?php

namespace Wearesho\ScoringDemo\Controllers;

use yii\web;
use yii\di;
use Wearesho\ScoringDemo;

/**
 * Class Statistics
 * @package Wearesho\ScoringDemo\Controllers
 */
class Statistics extends web\Controller
{
    public $viewPath = '@root/Web/Views';

    /** @var string|array|web\Request */
    public $request = 'request';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $this->request = di\Instance::ensure($this->request, web\Request::class);
    }

    public function actionIndex()
    {
        $params = $this->request->getQueryParams();
        $model = new ScoringDemo\Models\Statistics();
        $model->load($params);

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
