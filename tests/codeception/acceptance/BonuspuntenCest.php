<?php
use tests\codeception\_pages\LoginPage;
use tests\codeception\_pages\SelectHikePage;

 class BonuspuntenCest
 {
     public function testOrganisatieOverview(\AcceptanceTester $I)
     {
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
         $I->amGoingTo('ensure that Organisatie menu works');
         $I->click('Organisatie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Bonus Points overview');
         $I->click('Bonus Points overview');
         if (method_exists($I, 'wait')) {
           $I->wait(3); // only for selenium
         }

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

         $I->see('Geselecteerde hike: gestart');
         $I->click('Geselecteerde hike: gestart');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->click('Assign bonuspoints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Opslaan');
         $I->fillField('input[name="Bonuspunten[omschrijving]"]', 'Acceptatietests');
         $I->fillField('input[name="Bonuspunten[score]"]', '999');

         $I->click('Opslaan');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Received bonus points');
         $I->see('999');

    }

    public function testDeelnemerOverview(\AcceptanceTester $I)
    {
        $I->wantTo('Test bonuspunten actions for deelnemer and gestart hike');
        $loginPage = LoginPage::openBy($I);
        $loginPage->login('deelnemera', 'test123');
        if (method_exists($I, 'wait')) {
            $I->wait(3); // only for selenium
        }
        $I->see('Logout (deelnemera)');
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
