<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class TimeTrailCheckFixture extends ActiveFixture
{
    public $modelClass = 'app\models\TimeTrailCheck';

    public function beforeLoad() {
        parent::beforeLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
    }

    public function afterLoad() {
        parent::afterLoad();
        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
