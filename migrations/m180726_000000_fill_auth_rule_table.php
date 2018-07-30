<?php

//use Yii;
use yii\db\Migration;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180726_000000_fill_auth_rule_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute("
            INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
            ('organisatiePostCheck', 0x4f3a33333a226170705c726261635c4f7267616e697361746965506f7374436865636b52756c65223a333a7b733a343a226e616d65223b733a32303a226f7267616e697361746965506f7374436865636b223b733a393a22637265617465644174223b693a313533323836303434333b733a393a22757064617465644174223b693a313533323836303434333b7d, 1532860443, 1532860443)
        ");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('auth_rule', ['name' => 'organisatiePostCheck']);
    }
}
