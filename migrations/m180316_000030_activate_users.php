<?php

//use Yii;
use yii\db\Migration;
use app\components\GeneralFunctions;
use app\models\Users;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180316_000030_activate_users extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $users = Users::find()
            ->where('id != 2')
            ->all();

        foreach ($users as $user) {
            $user->password_hash = password_hash(GeneralFunctions::randomString(8), PASSWORD_DEFAULT);
            $user->confirmed_at = $user->created_at;
            $user->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->truncateTable('auth_assignment');
    }

}
