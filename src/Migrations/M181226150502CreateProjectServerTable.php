<?php

namespace Wearesho\Deployer\Migrations;

use yii\db\Migration;

/**
 * Class M181226150502CreateProjectServerTable
 */
class M181226150502CreateProjectServerTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable(
            'project_server',
            [
                'id' => $this->primaryKey(),
                'project_id' => $this->integer()->notNull(),
                'host' => $this->text()->notNull(),
                'secret' => $this->text()->notNull(),
            ]
        );

        $this->addForeignKey(
            'fk_project_server_project',
            'project_server',
            'project_id',
            'project',
            'id',
            'cascade',
            'cascade'
        );

        $this->createIndex(
            'unique_project_server_host',
            'project_server',
            ['project_id', 'host',],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('project_server');
    }
}
