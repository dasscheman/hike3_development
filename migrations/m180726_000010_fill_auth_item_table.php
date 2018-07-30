<?php

//use Yii;
use yii\db\Migration;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180726_000010_fill_auth_item_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute("
            INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
                ('organisatiePostCheck', 1, '', 'organisatiePostCheck', NULL, 1520807822, 1520807822); ");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('auth_item', ['name' => 'organisatiePostCheck']);
    }
}
