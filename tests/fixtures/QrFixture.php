<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class QrFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Qr';
    public $depends = [
      'app\tests\fixtures\EventNamesFixture',
      'app\tests\fixtures\RouteFixture',
      'app\tests\fixtures\DeelnemersEventFixture',
      'app\tests\fixtures\GroupsFixture',
      'app\tests\fixtures\UsersFixture'
    ];

    public function beforeLoad() {
        parent::beforeLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
    }

    public function afterLoad() {
        parent::afterLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
