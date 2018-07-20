<?php

namespace tests\models;

use app\tests\fixtures;
use app\models\Users;

class UserTest extends \Codeception\Test\Unit
{    
    public function _fixtures()
    {
        return [
           'user' => [
               'class' => fixtures\UsersFixture::className(),
               'dataFile' => 'tests/fixtures/data/Users.php',
            ],
        ];
    }

    public function testFindUserById()
    {
        expect_that($user = Users::findIdentity(1));
        expect($user->username)->equals('organisatie');

        expect_not(Users::findIdentity(999));
    }

    public function testFindUserByAccessToken()
    {
        expect_that($user = Users::findIdentityByAccessToken('100-token'));
        expect($user->username)->equals('organisatie');

        expect_not(Users::findIdentityByAccessToken('non-existing'));        
    }

    public function testFindUserByUsername()
    {
        expect_that($user = Users::findByUsername('organisatie'));
        expect_not(Users::findByUsername('not-admin'));
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser($user)
    {
        $user = Users::findByUsername('organisatie');
        expect_that($user->validateAuthKey('test100key'));
        expect_not($user->validateAuthKey('test102key'));
    }

}
