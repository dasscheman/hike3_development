<?php
class HikeBeheerOpstartCest
{
    public function _before(\FunctionalTester $I)
    {
    }

    public function _after(\FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(\FunctionalTester $I)
    {
        $I->amLoggedInAs(100);
        $I->amOnPage('/');
        $I->see('Logout (admin)');
    }
}
