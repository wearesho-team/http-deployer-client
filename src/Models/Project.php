<?php

namespace Wearesho\Deployer\Models;

use yii\db;

/**
 * Class Project
 * @package Wearesho\Deployer\Models
 *
 * @property string $id [integer]
 * @property string $name [varchar(255)]
 *
 * @property-read Project\Server[] $servers
 */
class Project extends db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'project';
    }

    public function rules(): array
    {
        return [
            [['name',], 'required',],
            [['name',], 'string',],
            [['name',], 'unique',],
        ];
    }

    public function getServers(): db\ActiveQuery
    {
        return $this->hasOne(Project\Server::class, ['project_id' => 'id']);
    }
}
