<?php

//use Yii;
use yii\db\Migration;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180720_000000_fill_auth_rule_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute("
            INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
            ('post', 0x4f3a31373a226170705c726261635c506f737452756c65223a333a7b733a343a226e616d65223b733a343a22706f7374223b733a393a22637265617465644174223b693a313533323131343230373b733a393a22757064617465644174223b693a313533323131343230373b7d, 1522098239, 1522098239)
        ");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('auth_rule', ['name' => 'post']);
    }
}
