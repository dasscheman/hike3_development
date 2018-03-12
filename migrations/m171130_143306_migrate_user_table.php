<?php

//use Yii;
use yii\db\Migration;
use app\models\Users;


/**
 * Class m171104_143306_update_rbac_data
 */
class m171130_143306_migrate_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $users = Yii::$app->db->createCommand('SELECT * FROM tbl_users')->queryAll();

        foreach ($users as $user) {
            if($user['create_time'] === '0000-00-00 00:00:00' ||
               $user['create_time'] == NULL ) {
                $create_time = time();
            } else {
                $create_time = strtotime($user['create_time']);
            }

            if($user['update_time'] === '0000-00-00 00:00:00' ||
               $user['update_time'] == NULL ) {
                $update_time = time();
            } else {
                $update_time = strtotime($user['update_time']);
            }

            $this->insert('user', [
                'id' => $user['user_ID'],
                'username' => $user['username'],
                'email' => $user['email'],
                'auth_key' => \Yii::$app->security->generateRandomString(),
                'password_hash' => $user['password'],
                'voornaam' => $user['voornaam'],
                'achternaam' => $user['achternaam'],
                'organisatie' => $user['organisatie'],
                'birthdate' => $user['birthdate'],
                'created_at' => $create_time,
                'create_time' => $user['create_time'],
                'create_user_ID' =>$user['create_user_ID'],
                'update_time' => $user['update_time'],
                'updated_at' => $update_time,
                'last_login_at' => strtotime($user['last_login_time']),
                'selected_event_ID' => $user['selected_event_ID'],
                'update_user_ID' => $user['update_user_ID']
            ]);
        }
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
