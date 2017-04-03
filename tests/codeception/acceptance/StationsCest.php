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
 class MainMenuCest
 {
     public function testMainMenuOrganisatieOpstart(\AcceptanceTester $I)
     {
         $I->wantTo('ensure that Organisation Opstart works');
         $loginPage = LoginPage::openBy($I);

         $I->see('Login', 'h1');

         $loginPage->login('organisatie', 'test123');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->expectTo('see user info');
         $I->see('Logout (organisatie)');

         $I->amGoingTo('ensure that Hike selection works');
         $selectHikePage = SelectHikePage::openBy($I);
         $selectHikePage->selectHike(1);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->amGoingTo('ensure that Home button works');
         $I->see('Geselecteerde hike: opstart');
         $I->click('Geselecteerde hike: opstart');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('opstart', 'h3');

         $I->click('Organisatie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Stations');
         $I->click('Stations');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Posten', 'h1');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('TODO');

     }

     public function testMainMenuDeelnemerOpstart(\AcceptanceTester $I)
     {
          $I->wantTo('ensure that Deelnemers Opstart works');
          $loginPage = LoginPage::openBy($I);

          $I->see('Login', 'h1');

          $loginPage->login('deelnemera', 'test123');
          if (method_exists($I, 'wait')) {
              $I->wait(3); // only for selenium
          }
          $I->expectTo('see user info');
          $I->see('Logout (deelnemera)');

          $I->amGoingTo('ensure that Hike selection works');
          $selectHikePage = SelectHikePage::openBy($I);
          $selectHikePage->selectHike(1);
          if (method_exists($I, 'wait')) {
              $I->wait(3); // only for selenium
          }

          $I->amGoingTo('ensure that Home button works');
          $I->see('Geselecteerde hike: opstart');
          $I->click('Geselecteerde hike: opstart');
          if (method_exists($I, 'wait')) {
              $I->wait(3); // only for selenium
          }
         $I->see('groep A opstart', 'h3');

         $I->click('Organisatie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->dontSee('Stations');
         $I->amOnPageCustom('posten/index');
         $I->see('Forbidden (#403)');
     }

     public function testMainMenuOrganisatieIntroductie(\AcceptanceTester $I)
     {
         $I->wantTo('ensure that login works');
         $loginPage = LoginPage::openBy($I);

         $I->see('Login', 'h1');

         $loginPage->login('organisatie', 'test123');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->expectTo('see user info');
         $I->see('Logout (organisatie)');

         $I->wantTo('ensure that Hike selection works');
         $selectHikePage = SelectHikePage::openBy($I);
         $selectHikePage->selectHike(2);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->wantTo('ensure that Home button works');
         $I->see('Geselecteerde hike: introductie');
         $I->click('Geselecteerde hike: introductie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('introductie', 'h3');

         $I->click('Organisatie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Stations');
         $I->click('Stations');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Posten', 'h1');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('TODO');

     }

     public function testMainMenuDeelnemerIntroductie(\AcceptanceTester $I)
     {
         $I->wantTo('ensure that login works');
         $loginPage = LoginPage::openBy($I);

         $I->see('Login', 'h1');

         $loginPage->login('deelnemera', 'test123');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->expectTo('see user info');
         $I->see('Logout (deelnemera)');

         $I->wantTo('ensure that Hike selection works');
         $selectHikePage = SelectHikePage::openBy($I);
         $selectHikePage->selectHike(2);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->wantTo('ensure that Home button works');
         $I->see('Geselecteerde hike: introductie');
         $I->click('Geselecteerde hike: introductie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('groep A introductie', 'h3');

         $I->click('Organisatie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->dontSee('Stations');
         $I->amOnPageCustom('posten/index');
         $I->see('Forbidden (#403)');
     }

     public function testMainMenuOrganisatieGestart(\AcceptanceTester $I)
     {
         $I->wantTo('ensure that login works');
         $loginPage = LoginPage::openBy($I);

         $I->see('Login', 'h1');

         $loginPage->login('organisatie', 'test123');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->expectTo('see user info');
         $I->see('Logout (organisatie)');

         $I->wantTo('ensure that Hike selection works');
         $selectHikePage = SelectHikePage::openBy($I);
         $selectHikePage->selectHike(3);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->wantTo('ensure that Home button works');
         $I->see('Geselecteerde hike: gestart');
         $I->click('Geselecteerde hike: gestart');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('gestart', 'h3');

         $I->click('Organisatie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Stations');
         $I->click('Stations');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Posten', 'h1');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('TODO');
     }

     public function testMainMenuDeelnemerGestart(\AcceptanceTester $I)
     {
         $I->wantTo('ensure that login works');
         $loginPage = LoginPage::openBy($I);

         $I->see('Login', 'h1');

         $loginPage->login('deelnemera', 'test123');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->expectTo('see user info');
         $I->see('Logout (deelnemera)');

         $I->wantTo('ensure that Hike selection works');
         $selectHikePage = SelectHikePage::openBy($I);
         $selectHikePage->selectHike(3);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->wantTo('ensure that Home button works');
         $I->see('Geselecteerde hike: gestart');
         $I->click('Geselecteerde hike: gestart');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('groep A gestart', 'h3');

           $I->click('Organisatie');
           if (method_exists($I, 'wait')) {
               $I->wait(3); // only for selenium
           }
         $I->dontSee('Stations');
         $I->amOnPageCustom('posten/index');
         $I->see('Forbidden (#403)');
     }

     public function testMainMenuOrganisatieBeindigd(\AcceptanceTester $I)
     {
         $I->wantTo('ensure that login works');
         $loginPage = LoginPage::openBy($I);

         $I->see('Login', 'h1');

         $loginPage->login('organisatie', 'test123');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->expectTo('see user info');
         $I->see('Logout (organisatie)');

         $I->wantTo('ensure that Hike selection works');
         $selectHikePage = SelectHikePage::openBy($I);
         $selectHikePage->selectHike(4);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->wantTo('ensure that Home button works');
         $I->see('Geselecteerde hike: beindigd');
         $I->click('Geselecteerde hike: beindigd');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('beindigd', 'h3');

         $I->click('Organisatie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Stations');
         $I->click('Stations');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Posten', 'h1');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('TODONo results found.');
     }

     public function testMainMenuDeelnemerBeindigd(\AcceptanceTester $I)
     {
         $I->wantTo('ensure that login works');
         $loginPage = LoginPage::openBy($I);

         $I->see('Login', 'h1');

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

         $I->wantTo('ensure that Home button works');
         $I->see('Geselecteerde hike: beindigd');
         $I->click('Geselecteerde hike: beindigd');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('groep A beindigd', 'h3');

          $I->click('Organisatie');
          if (method_exists($I, 'wait')) {
              $I->wait(3); // only for selenium
          }
         $I->dontSee('Stations');
         $I->amOnPageCustom('posten/index');
         $I->see('Forbidden (#403)');
     }

 }
