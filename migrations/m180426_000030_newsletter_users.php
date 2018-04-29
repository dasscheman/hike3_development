<?php

//use Yii;
use yii\db\Migration;
use app\models\Users;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180426_000030_newsletter_users extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user', 'newsletter', $this->boolean());
   
        $users = Users::find()
            ->where('id != 2')
            ->all();

        foreach ($users as $user) {
            $user->newsletter = true;
            $user->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'newsletter');
    }
}
