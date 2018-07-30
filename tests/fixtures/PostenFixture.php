<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class PostenFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Posten';

    public function beforeLoad() {
        parent::beforeLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
    }

    public function afterLoad() {
        parent::afterLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
