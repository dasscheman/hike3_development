<?php

// namespace app\tests\fixtures;
namespace app\tests\codeception\fixtures;

use yii\test\ActiveFixture;

class RouteFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Route';
    // public $depends = ['app\tests\codeception\fixtures\DeelnemersEventFixture'];
    // public $depends = ['app\tests\codeception\fixtures\EventNamesFixture'];

    // public function unload(){
    //     parent::unload();
    //     $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
    //     // $this->resetTable();
    //     $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 1')->execute();
    // }
}
