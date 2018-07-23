<?php

//use Yii;
use yii\db\Migration;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180720_000020_fill_auth_item_child_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute("
            INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
                ('gebruiker', 'post'); ");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('auth_item_child', ['child' => 'post']);
    }
}
