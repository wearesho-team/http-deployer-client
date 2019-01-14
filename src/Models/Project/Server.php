<?php

namespace Wearesho\Deployer\Models\Project;

use Wearesho\Deployer;
use yii\db;
use yii\validators\UniqueValidator;

/**
 * Class Server
 * @package Wearesho\Deployer\Models\Project
 *
 * @property int $id [integer]
 * @property int $project_id [integer]
 * @property string $host
 * @property string $secret
 *
 * @property-read string $hostName
 *
 * @property Deployer\Models\Project $project
 */
class Server extends db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'project_server';
    }

    public function rules(): array
    {
        return [
            [['project_id', 'host', 'secret',], 'required',],
            [['project_id',], 'integer', 'min' => 1,],
            [['project_id',], 'exist', 'targetRelation' => 'project',],
            [['host',], 'string',],
            [['host',], 'url',],
            [['host',], Server\HostValidator::class, 'skipOnError' => true,],
            [['secret',], 'string',],
            [
                ['host',],
                UniqueValidator::class,
                'filter' => function (db\Query $query): void {
                    $query->andWhere(['=', 'project_id', $this->project_id,]);
                },
                'when' => function (): bool {
                    return !$this->hasErrors('project_id');
                },
            ],
        ];
    }

    public function getProject(): db\ActiveQuery
    {
        return $this->hasOne(Deployer\Models\Project::class, ['id' => 'project_id']);
    }

    public function setProject(Deployer\Models\Project $project): Server
    {
        $this->project_id = $project->id;
        $this->populateRelation('project', $project);
        return $this;
    }

    public function getHostName(): string
    {
        return parse_url($this->host, PHP_URL_HOST);
    }
}
