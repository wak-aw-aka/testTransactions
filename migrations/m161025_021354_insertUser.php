<?php

use yii\db\Migration;

class m161025_021354_insertUser extends Migration
{
    public function up()
    {
         $this->insert('user',array(
                 'name'    => 'John Doo',
                 'balance' => 100,
          ));

          $id = Yii::$app->db->getLastInsertID();

         $this->insert('operationHistory',array(
                 'userId'        => 0,
                 'toUserId'      => $id,
                 'sum'           => 100,
                 'operationType' => 'refill'
          ));

         $this->insert('user',array(
                 'name'    => 'Alex Murfy',
                 'balance' => 0,
          ));

    }

    public function down()
    {
        echo "m161025_021353_insertUser cannot be reverted.\n";

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
