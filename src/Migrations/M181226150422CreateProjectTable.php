<?php

namespace Wearesho\Deployer\Migrations;

use yii\db\Migration;

/**
 * Class M181226150422CreateProjectTable
 */
class M181226150422CreateProjectTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable(
            'project',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->unique(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('project');
    }
}
