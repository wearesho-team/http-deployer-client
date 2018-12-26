<?php

namespace Wearesho\Deployer\Migrations;

use yii\db\Migration;

/**
 * Class M181226152352CreateProjectHistoryTable
 */
class M181226152352CreateProjectHistoryTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable(
            'project_history',
            [
                'id' => $this->primaryKey(),
                'project_id' => $this->integer()->notNull(),
                'user_id' => $this->integer()->notNull(),
                'key' => $this->string()->notNull(),
                'value' => $this->text(),
                'created_at' => $this->timestamp()->notNull(),
            ]
        );

        $this->addForeignKey(
            'fk_project_history_project',
            'project_history',
            'project_id',
            'project',
            'id',
            'cascade',
            'cascade'
        );

        $this->addForeignKey(
            'fk_project_history_user',
            'project_history',
            'user_id',
            'user',
            'id',
            'restrict',
            'cascade'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('project_history');
    }
}
