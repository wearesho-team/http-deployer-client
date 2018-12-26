<?php

namespace Wearesho\Deployer\Models\Project;

use Carbon\Carbon;
use Wearesho\Deployer;
use yii\behaviors\AttributesBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db;

/**
 * Class History
 * @package Wearesho\Deployer\Models\Project
 *
 * @property int $id [integer]
 * @property int $user_id [integer]
 * @property int $project_id [integer]
 * @property string $key [varchar(255)]
 * @property string $value
 * @property string $created_at [timestamp(0)]
 *
 * @property Deployer\Models\Project $project
 * @property Deployer\Models\User $user
 */
class History extends db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'project_history';
    }

    public function behaviors(): array
    {
        return [
            'ts' => [
                'class' => TimestampBehavior::class,
                'value' => function (): string {
                    return Carbon::now()->toDateTimeString();
                },
                'updatedAtAttribute' => null,
            ],
            'value' => [
                'class' => AttributesBehavior::class,
                'attributes' => [
                    'user_id' => [
                        db\ActiveRecord::EVENT_BEFORE_INSERT => function (): ?int {
                            if (!\Yii::$app->has('user')) {
                                return null;
                            }
                            return \Yii::$app->user->id;
                        },
                    ],
                ],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['project_id', 'key',], 'required',],
            [['project_id', 'user_id',], 'integer', 'min' => 1,],
            [['project_id',], 'exist', 'targetRelation' => 'project',],
            [['user_id',], 'exist', 'targetRelation' => 'user',],
            [['key',], 'string',],
            [['value',], 'string',],
        ];
    }

    public function getProject(): db\ActiveQuery
    {
        return $this->hasOne(Deployer\Models\Project::class, ['id' => 'project_id']);
    }

    public function setProject(Deployer\Models\Project $project): History
    {
        $this->project_id = $project->id;
        $this->populateRelation('project', $project);
        return $this;
    }

    public function getUser(): db\ActiveQuery
    {
        return $this->hasOne(Deployer\Models\User::class, ['id' => 'user_id']);
    }

    public function setUser(Deployer\Models\User $user): History
    {
        $this->user_id = $user->id;
        $this->populateRelation('user', $user);
        return $this;
    }
}
