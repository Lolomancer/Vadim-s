<?php

use yii\db\Migration;

class m160503_001457_access extends Migration
{
    public function safeUp()
    {
        $this->createTable('clndr_access', [
            'id' => $this->primaryKey(),
            'user_owner' => $this->integer(11)->notNull(),
            'user_guest' => $this->integer(11)->notNull(),
            'date' => $this->date()->notNull(),
        ]);
        $this->addForeignKey('FK_user_owner', 'clndr_access', 'user_owner', 'clndr_user', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('FK_user_guest', 'clndr_access', 'user_guest', 'clndr_user', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        $this->dropTable('clndr_access');
    }
}
