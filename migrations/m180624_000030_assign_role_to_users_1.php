<?php

//use Yii;
use yii\db\Migration;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180624_000030_assign_role_to_users extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $users = Yii::$app->db->createCommand('SELECT * FROM user')->queryAll();

        foreach ($users as $user) {
            $assignment = Yii::$app->db->createCommand('SELECT * FROM auth_assignment WHERE item_name="gebruiker" AND user_id=' . $user['id'])->queryAll();
            if(!$assignment) {
                $this->insert('auth_assignment', [
                    'item_name' => 'gebruiker',
                    'user_id' => $user['id']
                ]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->truncateTable('auth_assignment');
    }
}
