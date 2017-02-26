<?php

use tests\codeception\_pages\LoginPage;
use tests\codeception\_pages\SelectHikePage;

/* @var $scenario Codeception\Scenario */
/*
 * DATA:
 * event_id = 4;
 * route_id = 7, 8;
 * group_id = 7, 8;
 * bonuspunten_id = 7, 8;
 * openVragen_id = 10, 11;
 * noodenvelop_id = 6;
 * qr_id = 7, 8;
 * post_id = 10, 11;
 */
 class ActionsBeindigdPlayersCest
 {
     public function _before(\AcceptanceTester $I)
     {
         $I->wantTo('ensure that login works');

         $loginPage = LoginPage::openBy($I);

         $I->see('Login', 'h1');

         $I->amGoingTo('try to login with empty credentials');
         $loginPage->login('deelnemera', 'test123');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->expectTo('see user info');
         $I->see('Logout (deelnemera)');

         $I->wantTo('ensure that Hike selection works');
         $selectHikePage = SelectHikePage::openBy($I);
         $selectHikePage->selectHike(4);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Geselecteerde hike: beindigd');

         $I->wantTo('ensure that Home button works');
         $I->click('Geselecteerde hike: beindigd');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('groep A beindigd');
         $I->see('No day selected');
     }


// $I->amOnPageCustom('/site/index');
//     }
}
