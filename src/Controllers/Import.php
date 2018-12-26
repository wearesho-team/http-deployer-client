<?php

namespace Wearesho\ScoringDemo\Controllers;

use Carbon\Carbon;
use yii\web;
use yii\di;
use yii\db;

/**
 * Class Import
 * @package Wearesho\ScoringDemo\Web\Controller
 */
class Import extends web\Controller
{
    public $viewPath = '@root/Web/Views';

    /** @var string|array|db\Connection */
    public $db = 'db';

    /** @var string|array|web\Request */
    public $request = 'request';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        $this->request = di\Instance::ensure($this->request, web\Request::class);
        $this->db = di\Instance::ensure($this->db, db\Connection::class);
    }

    /**
     * @throws web\HttpException
     */
    public function actionIndex()
    {
        switch ($this->request->method) {
            case "POST":
                return $this->upload();
            case "GET":
                return $this->display();
            default:
                throw new web\HttpException(405);
        }
    }

    /**
     * @return web\Response
     * @throws web\BadRequestHttpException
     */
    protected function upload(): web\Response
    {
        $file = web\UploadedFile::getInstanceByName('db');
        if (!$file instanceof web\UploadedFile) {
            throw new web\BadRequestHttpException("Missing DB");
        }

        $rows = [];

        $l = 1;
        $handle = fopen($file->tempName, 'r');
        while (($line = fgets($handle)) !== false) {
            $data = str_getcsv($line);
            if (count($data) !== 4) {
                throw new web\BadRequestHttpException("Invalid line {$l} elements count");
            }

            $date = $data[0];
            if (!Carbon::hasFormat($date, 'Y-m-d')) {
                throw new web\BadRequestHttpException("Invalid line {$l} date format");
            }
            $score = $data[1];
            if (!is_numeric($score)) {
                throw new web\BadRequestHttpException("Invalid line {$l} score format");
            }
            $score = (integer)$score;
            $overdue = $data[2];
            if (empty($overdue)) {
                $overdue = 0;
            } elseif (!preg_match('/^\d+$/', $overdue)) {
                throw new web\BadRequestHttpException("Invalid line {$l} overdue format");
            }
            $type = $data[3];
            if (!in_array($type, ['repaid', 'paid', 'denied',])) {
                throw new web\BadRequestHttpException("Invalid line {$l} type format");
            }

            $rows[] = compact('date', 'score', 'type', 'overdue');

            $l++;
        }

        fclose($handle);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->db->transaction(function () use ($rows) {
            $this->db->createCommand("TRUNCATE TABLE item RESTART IDENTITY;")->execute();
            $this->db->createCommand()
                ->batchInsert('item', ['date', 'score', 'type', 'overdue',], $rows)
                ->execute();
        });

        return $this->redirect('/import');
    }

    protected function display()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $count = $this->db->createCommand('SELECT count(*) FROM item;')->queryScalar();

        return $this->render('view', compact('count'));
    }
}
