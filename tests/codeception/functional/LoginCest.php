<?php
use app\tests\codeception\fixtures\UsersFixture;

class LoginFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->haveFixtures([
            'users' => [
                'class' => UsersFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/models/users.php',
             ],
        ]);

        $I->amOnRoute('site/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Login', 'h1');

    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginById(\FunctionalTester $I)
    {
        // $I->
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        $I->amOnPage('/');
        $I->see('Logout (organisatie)');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginByInstance(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        $I->amOnPage('/');
        $I->see('Logout (organisatie)');
    }

    public function loginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', []);
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    public function loginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'wrong',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect username or password.');
    }

    public function loginSuccessfully(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'organisatie',
            'LoginForm[password]' => 'test123',
        ]);
        $I->see('Logout (organisatie)');
        $I->dontSeeElement('form#login-form');
    }
}
