<?php

// namespace app\tests\fixtures;
namespace app\tests\codeception\fixtures;

use yii\test\ActiveFixture;

class DeelnemersEventFixture extends ActiveFixture
{
    public $modelClass = 'app\models\DeelnemersEvent';
    public $depends = ['app\tests\codeception\fixtures\RouteFixture'];

    // public function unload(){
    //     parent::unload();
    //     $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
    //     // $this->resetTable();
    //     $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 1')->execute();
    // }
}
