<?php

//use Yii;
use yii\db\Migration;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180326_000000_fill_auth_rule_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute("
            INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
            ('deelnemerEnded', 0x4f3a32373a226170705c726261635c4465656c6e656d6572456e64656452756c65223a333a7b733a343a226e616d65223b733a31343a226465656c6e656d6572456e646564223b733a393a22637265617465644174223b693a313532323039383233393b733a393a22757064617465644174223b693a313532323039383233393b7d, 1522098239, 1522098239)
        ");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('auth_rule', ['name' => 'deelnemerEndend']);
    }
}
