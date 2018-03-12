<?php

use yii\db\Migration;
use app\models\Users;


/**
 * Class m171104_143306_update_rbac_data
 */
class m171104_143306_update_rbac_data extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
//        $this->batchInsert('auth_item', ['name', 'type', 'description', 'rule_name', 'data'], [
//            ['beheerder', 1, 'Behherder van de site.', NULL, NULL],
//            ['gebruiker', 1, 'Standaard gebruiker.', NULL, NULL]
//        ]);
//
//        $this->batchInsert('auth_item_child', ['parent', 'child'], [
//            ['beheerder', 'gebruiker']
//        ]);
//
//        $users = Users::find()->where('user_ID > 1')->all();
//        foreach ($users as $user) {
//            $this->insert('auth_assignment', ['item_name' => 'gebruiker', 'user_id' => $user->user_ID]);
//        }

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171104_143306_update_rbac_data cannot be reverted.\n";

        return false;
    }
    */
}
