<?php

namespace Wearesho\Deployer\Migrations;

use yii\db\Migration;

/**
 * Class M181226133555CreateUserTable
 */
class M181226133555CreateUserTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'login' => $this->string()->unique()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'authenticator_secret' => $this->text()->notNull(),
            'auth_key' => $this->text()->unique()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('user');
    }
}
