<?php

// namespace app\tests\fixtures;
namespace app\tests\codeception\fixtures;

use yii\test\ActiveFixture;

class UsersFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Users';
    public $depends =
    [
        'app\tests\codeception\fixtures\EventNamesFixture',
        'app\tests\codeception\fixtures\GroupsFixture'
    ];

    public function beforeLoad() {
        parent::beforeLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
    }

    public function afterLoad() {
        parent::afterLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
    //
    // public function unload(){
    //     parent::unload();
    //     $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
    //     $this->resetTable();
    //     $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 1')->execute();
    // }
}
