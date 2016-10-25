<?php

use yii\db\Migration;

class m161025_015502_addBaseTables extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'user' => $this->string(),
            'balance' => $this->integer(),
        ]);

        $this->createTable('operationHistory', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'toUserId' => $this->integer(),
            'sum' => $this->integer()->notNull(),
            'operationType' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('user');

        $this->dropTable('operationHistory');

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
