<?php

use app\tests\fixtures;
// use \FunctionalTester;

class LoginFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->haveFixtures([
           'user' => [
               'class' => fixtures\UsersFixture::className(),
               'dataFile' => 'tests/fixtures/data/users.php',
            ],
        ]);
        $I->amOnRoute('user/security/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Log in', 'h3');

    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginById(\FunctionalTester $I)
    {
        $I->amLoggedInAs(1);
        $I->amOnPage('/');
        $I->see('Uitloggen organisatie');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginByInstance(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        $I->amOnPage('/');
        $I->see('Uitloggen organisatie');
    }

    public function loginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->see('Log in');
        $I->submitForm('#login-form', []);
        $I->expectTo('see validations errors');
        $I->see('E-mailadres mag niet leeg zijn.');
        $I->see('Password mag niet leeg zijn.');
    }

    public function loginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->see('Log in');
        $I->submitForm('#login-form', [
            'login-form[login]' => 'organisatie',
            'login-form[password]' => 'wrong',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Ongeldige gebruikersnaam of wachtwoord');
    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->see('Log in');
        $I->submitForm('#login-form', [
            'login-form[login]' => 'organisatie',
            'login-form[password]' => 'test',
        ]);
        $I->see('Uitloggen organisatie');
        $I->dontSeeElement('form#login-form');
    }
}

//login-form-login
