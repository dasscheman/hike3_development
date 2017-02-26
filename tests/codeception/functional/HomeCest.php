<?php
class HomeCest
{
    public function ensureThatHomePageWorks(\FunctionalTester $I)
    {
        $I->amOnPage('/site/index');
        $I->see('My Company');

        $I->seeLink('Contact');
        $I->click('Contact');
        $I->see('Contact');
    }
}
