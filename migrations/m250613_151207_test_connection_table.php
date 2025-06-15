<?php

use yii\db\Migration;

class m250613_151207_test_connection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
public function safeUp()
{
    $this->createTable('test_connection_table', [
        'id' => $this->primaryKey(),
        'nombre' => $this->string()->notNull(),
        'fecha_creacion' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
    ]);
}



    /**
     * {@inheritdoc}
     */
    public function safeDown()
{
    $this->dropTable('test_connection_table');
}

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250613_151207_test_connection_table cannot be reverted.\n";

        return false;
    }
    */
}
