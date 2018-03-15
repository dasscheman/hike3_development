<?php

//use Yii;
use yii\db\Migration;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180315_000030_assign_role_to_users extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $users = Yii::$app->db->createCommand('SELECT * FROM user')->queryAll();

        foreach ($users as $user) {
            $this->insert('auth_assignment', [
                'item_name' => 'gebruiker',
                'user_id' => $user['id']
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->truncateTable('auth_assignment');
    }

}
