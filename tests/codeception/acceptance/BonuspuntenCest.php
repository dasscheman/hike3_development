<?php
 use app\tests\codeception\fixtures\UsersFixture;
 use app\tests\codeception\fixtures\EventNamesFixture;

 class BonuspuntenCest
 {
     public function testOrganisatieOverview(\AcceptanceTester $I)
     {
         $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
         $I->wantTo('Test bonuspunten actions for Organisation and gestart hike');
         $loginPage = LoginPage::openBy($I);
         $loginPage->login('organisatie', 'test123');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Logout (organisatie)');
         $selectHikePage = SelectHikePage::openBy($I);
         $selectHikePage->selectHike(3);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Geselecteerde hike: gestart');
         $I->click('Bonus Points overview');

         $I->see('Overview bonuspoints');
         $I->dontSee('No results found.');
         $I->see('bonus gestart organisatie');
         $I->see('bonus gestart players groep A');
         $I->see('bonus gestart players groep B');
         $I->dontSee('bonus intro organisatie');
         $I->dontSee('bonus intro players groep A');
         $I->dontSee('bonus intro players groep B');
         $I->dontSee('bonus beindigd players groep A');
         $I->dontSee('bonus beindigd players groep B');

         $I->click('#bonus-expand-2');
         $I->see('Save');
         $I->fillField('input[name="Bonuspunten[omschrijving]"]', 'Acceptatietests');
         $I->fillField('input[name="Bonuspunten[score]"]', '999');
         $I->see('Save');
         $I->click('Bonus Points overview');
         $I->see('Acceptatietests');
         $I->see('999');

    }

    public function testDeelnemerOverview(\AcceptanceTester $I)
    {
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        $I->wantTo('Test bonuspunten actions for deelnemer and gestart hike');
        $loginPage = LoginPage::openBy($I);
        $loginPage->login('deelnemera', 'test123');
        if (method_exists($I, 'wait')) {
            $I->wait(3); // only for selenium
        }
        $I->see('Logout (organisatie)');
        $selectHikePage = SelectHikePage::openBy($I);
        $selectHikePage->selectHike(3);
        if (method_exists($I, 'wait')) {
            $I->wait(3); // only for selenium
        }
        $I->see('Geselecteerde hike: gestart');
        $I->donSee('Bonus Points overview');

        $I->amOnPageCustom('bonuspunten/index');
        $I->donSee('Overview bonuspoints');
        $I->see('Forbidden (#403)');
   }
}
