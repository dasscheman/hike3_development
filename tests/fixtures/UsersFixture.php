<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class UsersFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Users';
//    public $depends =
//    [
//        'app\tests\fixtures\EventNamesFixture',
//        'app\tests\fixtures\GroupsFixture'
//    ];

    public function beforeLoad() {
        parent::beforeLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
    }

    public function afterLoad() {
        parent::afterLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}