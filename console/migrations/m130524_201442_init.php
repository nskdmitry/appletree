<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%apples}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string()->notNull(),
            'created' => $this->integer()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
            'falled' => $this->integer(),
            'size' => $this->float()->defaultValue(1.0),
        ], $tableOptions);

        $this->batchInsert("apples", [
            'color', "created", "size", "falled"
        ], [
            ["#FF3500", time(), 1.00, null],
            ["F43510", time(), 1.00, null],
            ["F00000", time(), 1.00, null],
            ["FF0000", time()-1000, 1.0, null],
            ["FF4545", time(), 1.0, time() + 1000],
            ["FF4545", time(), 0.95, time() + 1000],
            ["FF9040", time() - 1748564, 1.0, time() - 600000]
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
