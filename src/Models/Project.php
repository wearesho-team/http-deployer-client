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
 * @property-read Project\History[] $history
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

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
        ];
    }

    public function getServers(): db\ActiveQuery
    {
        return $this->hasMany(Project\Server::class, ['project_id' => 'id']);
    }

    public function getHistory(): db\ActiveQuery
    {
        return $this->hasMany(Project\History::class, ['project_id' => 'id']);
    }
}
