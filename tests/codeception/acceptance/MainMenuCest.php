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
     public function testMainMenuOrganisatieOpstart(\AcceptanceTester $I)
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
         $selectHikePage->selectHike(1);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->wantTo('ensure that Home button works');
         $I->see('Geselecteerde hike: opstart');
         $I->click('Geselecteerde hike: opstart');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('opstart', 'h2');

         $I->see('Overview');
         $I->click('Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('organisatie@kiwi.run');

         $I->see('Search New Friends');
         $I->click('Search New Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Friends');
         $I->click('Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('deelnemera');
         $I->see('deelnemerb');

         $I->see('Friends Requests');
         $I->click('Friends Requests');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Select Hike');
         $I->click('Select Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Select Hike', 'h1');
         $I->see('opstart');
         $I->see('introductie');
         $I->see('gestart');
         $I->see('beindigd');

         $I->dontSee('Game overview');
         $I->amOnPageCustom('site/overview-players');
         $I->see('Forbidden (#403)');

         $I->see('Overview groups scores');
         $I->click('Overview groups scores');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview groups scores', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');

         $I->see('Passed Stations & bonuspoints');
         $I->click('Passed Stations & bonuspoints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview passed stations and bonuspoints', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');
         $I->see('No day selected');

         $I->see('Start New Hike');
         $I->click('Start New Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Create new hike', 'h1');

         $I->dontSee('Hike overview');

         $I->see('Route Overview');
         $I->click('Route Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Routes', 'h1');
         $I->see('Introduction');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('No results found.');

         $I->see('Stations');
         $I->click('Stations');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Posten', 'h1');
         $I->see('Introduction');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('No results found.');

         $I->see('Overview opened hints');
         $I->click('Overview opened hints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview opened hints', 'h1');
         $I->see('No results found.');

         $I->see('Overview checked silent stations');
         $I->see('Overview checked silent stations');
         $I->click('Overview checked silent stations', 'h1');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Answered overview');
         $I->click('Answered overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview answered questions', 'h1');
         $I->see('No results found.');

          $I->see('Bonus Points overview');
          $I->click('Bonus Points overview');
          if (method_exists($I, 'wait')) {
              $I->wait(3); // only for selenium
          }
          $I->see('Overview bonuspoints', 'h1');
          $I->see('No results found.');
     }

     public function testMainMenuDeelnemerOpstart(\AcceptanceTester $I)
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
         $selectHikePage->selectHike(1);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->wantTo('ensure that Home button works');
         $I->see('Geselecteerde hike: opstart');
         $I->click('Geselecteerde hike: opstart');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('groep A opstart', 'h2');

         $I->see('Overview');
         $I->click('Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('deelnemera@kiwi.run');

         $I->see('Search New Friends');
         $I->click('Search New Friends');
         $I->see('No results found.');

         $I->see('Friends');
         $I->click('Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('organisatie');
         $I->see('deelnemerb');

         $I->see('Friends Requests');
         $I->click('Friends Requests');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Select Hike');
         $I->click('Select Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Select Hike', 'h1');
         $I->see('opstart');
         $I->see('introductie');
         $I->see('gestart');
         $I->see('beindigd');

         $I->dontSee('Game overview');
         $I->amOnPageCustom('site/overview-players');
         $I->click('Game overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('groep A opstart');

         $I->see('Overview groups scores');
         $I->click('Overview groups scores');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview groups scores', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');

         $I->see('Passed Stations & bonuspoints');
         $I->click('Passed Stations & bonuspoints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview passed stations and bonuspoints', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');
         $I->see('No day selected');

         $I->see('Start New Hike');
         $I->click('Start New Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Create new hike', 'h1');

         $I->dontSee('Hike overview');
         $I->amOnPageCustom('site/overview-organisation');
         $I->see('Forbidden (#403)');

         $I->dontSee('Route Overview');
         $I->amOnPageCustom('route/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Stations');
         $I->amOnPageCustom('posten/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Overview opened hints');
         $I->amOnPageCustom('open-nood-envelop/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Overview checked silent stations');
         $I->amOnPageCustom('qr-check/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Answered overview');
         $I->amOnPageCustom('open-vragen-antwoorden/index');
         $I->see('Forbidden (#403)');

          $I->dontSee('Bonus Points overview');
          $I->amOnPageCustom('bonuspunten/index');
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

         $I->see('opstart', 'h2');

         $I->see('Overview');
         $I->click('Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('organisatie@kiwi.run');

         $I->see('Search New Friends');
         $I->click('Search New Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Friends');
         $I->click('Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('deelnemera');
         $I->see('deelnemerb');

         $I->see('Friends Requests');
         $I->click('Friends Requests');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Select Hike');
         $I->click('Select Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Select Hike', 'h1');
         $I->see('opstart');
         $I->see('introductie');
         $I->see('gestart');
         $I->see('beindigd');

         $I->dontSee('Game overview');
         $I->amOnPageCustom('site/overview-players');
         $I->see('Forbidden (#403)');

         $I->see('Overview groups scores');
         $I->click('Overview groups scores');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview groups scores', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');

         $I->see('Passed Stations & bonuspoints');
         $I->click('Passed Stations & bonuspoints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview passed stations and bonuspoints', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');
         $I->see('No day selected');

         $I->see('Start New Hike');
         $I->click('Start New Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Create new hike', 'h1');

         $I->dontSee('Hike overview');

         $I->see('Route Overview');
         $I->click('Route Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Routes', 'h1');
         $I->see('Introduction');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('No results found.');

         $I->see('Stations');
         $I->click('Stations');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Posten', 'h1');
         $I->see('Introduction');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('No results found.');

         $I->see('Overview opened hints');
         $I->click('Overview opened hints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview opened hints', 'h1');
         $I->see('No results found.');

         $I->see('Overview checked silent stations');
         $I->see('Overview checked silent stations');
         $I->click('Overview checked silent stations', 'h1');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Answered overview');
         $I->click('Answered overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview answered questions', 'h1');
         $I->see('No results found.');

          $I->see('Bonus Points overview');
          $I->click('Bonus Points overview');
          if (method_exists($I, 'wait')) {
              $I->wait(3); // only for selenium
          }
          $I->see('Overview bonuspoints', 'h1');
          $I->see('No results found.');
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

         $I->see('groep A opstart', 'h2');

         $I->see('Overview');
         $I->click('Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('deelnemera@kiwi.run');

         $I->see('Search New Friends');
         $I->click('Search New Friends');
         $I->see('No results found.');

         $I->see('Friends');
         $I->click('Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('organisatie');
         $I->see('deelnemerb');

         $I->see('Friends Requests');
         $I->click('Friends Requests');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Select Hike');
         $I->click('Select Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Select Hike', 'h1');
         $I->see('opstart');
         $I->see('introductie');
         $I->see('gestart');
         $I->see('beindigd');

         $I->dontSee('Game overview');
         $I->amOnPageCustom('site/overview-players');
         $I->click('Game overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('groep A opstart');

         $I->see('Overview groups scores');
         $I->click('Overview groups scores');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview groups scores', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');

         $I->see('Passed Stations & bonuspoints');
         $I->click('Passed Stations & bonuspoints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview passed stations and bonuspoints', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');
         $I->see('No day selected');

         $I->see('Start New Hike');
         $I->click('Start New Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Create new hike', 'h1');

         $I->dontSee('Hike overview');
         $I->amOnPageCustom('site/overview-organisation');
         $I->see('Forbidden (#403)');

         $I->dontSee('Route Overview');
         $I->amOnPageCustom('route/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Stations');
         $I->amOnPageCustom('posten/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Overview opened hints');
         $I->amOnPageCustom('open-nood-envelop/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Overview checked silent stations');
         $I->amOnPageCustom('qr-check/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Answered overview');
         $I->amOnPageCustom('open-vragen-antwoorden/index');
         $I->see('Forbidden (#403)');

          $I->dontSee('Bonus Points overview');
          $I->amOnPageCustom('bonuspunten/index');
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

         $I->see('gestart', 'h2');

         $I->see('Overview');
         $I->click('Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('organisatie@kiwi.run');

         $I->see('Search New Friends');
         $I->click('Search New Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Friends');
         $I->click('Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('deelnemera');
         $I->see('deelnemerb');

         $I->see('Friends Requests');
         $I->click('Friends Requests');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Select Hike');
         $I->click('Select Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Select Hike', 'h1');
         $I->see('opstart');
         $I->see('introductie');
         $I->see('gestart');
         $I->see('beindigd');

         $I->dontSee('Game overview');
         $I->amOnPageCustom('site/overview-players');
         $I->see('Forbidden (#403)');

         $I->see('Overview groups scores');
         $I->click('Overview groups scores');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview groups scores', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');

         $I->see('Passed Stations & bonuspoints');
         $I->click('Passed Stations & bonuspoints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview passed stations and bonuspoints', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');
         $I->see('No day selected');

         $I->see('Start New Hike');
         $I->click('Start New Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Create new hike', 'h1');

         $I->dontSee('Hike overview');

         $I->see('Route Overview');
         $I->click('Route Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Routes', 'h1');
         $I->see('Introduction');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('No results found.');

         $I->see('Stations');
         $I->click('Stations');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Posten', 'h1');
         $I->see('Introduction');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('No results found.');

         $I->see('Overview opened hints');
         $I->click('Overview opened hints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview opened hints', 'h1');
         $I->see('No results found.');

         $I->see('Overview checked silent stations');
         $I->see('Overview checked silent stations');
         $I->click('Overview checked silent stations', 'h1');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Answered overview');
         $I->click('Answered overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview answered questions', 'h1');
         $I->see('No results found.');

          $I->see('Bonus Points overview');
          $I->click('Bonus Points overview');
          if (method_exists($I, 'wait')) {
              $I->wait(3); // only for selenium
          }
          $I->see('Overview bonuspoints', 'h1');
          $I->see('No results found.');
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

         $I->see('groep A opstart', 'h2');

         $I->see('Overview');
         $I->click('Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('deelnemera@kiwi.run');

         $I->see('Search New Friends');
         $I->click('Search New Friends');
         $I->see('No results found.');

         $I->see('Friends');
         $I->click('Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('organisatie');
         $I->see('deelnemerb');

         $I->see('Friends Requests');
         $I->click('Friends Requests');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Select Hike');
         $I->click('Select Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Select Hike', 'h1');
         $I->see('opstart');
         $I->see('introductie');
         $I->see('gestart');
         $I->see('beindigd');

         $I->dontSee('Game overview');
         $I->amOnPageCustom('site/overview-players');
         $I->click('Game overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('groep A opstart');

         $I->see('Overview groups scores');
         $I->click('Overview groups scores');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview groups scores', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');

         $I->see('Passed Stations & bonuspoints');
         $I->click('Passed Stations & bonuspoints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview passed stations and bonuspoints', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');
         $I->see('No day selected');

         $I->see('Start New Hike');
         $I->click('Start New Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Create new hike', 'h1');

         $I->dontSee('Hike overview');
         $I->amOnPageCustom('site/overview-organisation');
         $I->see('Forbidden (#403)');

         $I->dontSee('Route Overview');
         $I->amOnPageCustom('route/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Stations');
         $I->amOnPageCustom('posten/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Overview opened hints');
         $I->amOnPageCustom('open-nood-envelop/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Overview checked silent stations');
         $I->amOnPageCustom('qr-check/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Answered overview');
         $I->amOnPageCustom('open-vragen-antwoorden/index');
         $I->see('Forbidden (#403)');

          $I->dontSee('Bonus Points overview');
          $I->amOnPageCustom('bonuspunten/index');
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

         $I->see('opstart', 'h2');

         $I->see('Overview');
         $I->click('Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('organisatie@kiwi.run');

         $I->see('Search New Friends');
         $I->click('Search New Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Friends');
         $I->click('Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('deelnemera');
         $I->see('deelnemerb');

         $I->see('Friends Requests');
         $I->click('Friends Requests');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Select Hike');
         $I->click('Select Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Select Hike', 'h1');
         $I->see('opstart');
         $I->see('introductie');
         $I->see('gestart');
         $I->see('beindigd');

         $I->dontSee('Game overview');
         $I->amOnPageCustom('site/overview-players');
         $I->see('Forbidden (#403)');

         $I->see('Overview groups scores');
         $I->click('Overview groups scores');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview groups scores', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');

         $I->see('Passed Stations & bonuspoints');
         $I->click('Passed Stations & bonuspoints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview passed stations and bonuspoints', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');
         $I->see('No day selected');

         $I->see('Start New Hike');
         $I->click('Start New Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Create new hike', 'h1');

         $I->dontSee('Hike overview');

         $I->see('Route Overview');
         $I->click('Route Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Routes', 'h1');
         $I->see('Introduction');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('No results found.');

         $I->see('Stations');
         $I->click('Stations');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Posten', 'h1');
         $I->see('Introduction');
         $I->see('2015-02-25');
         $I->see('2015-03-01');
         $I->see('No results found.');

         $I->see('Overview opened hints');
         $I->click('Overview opened hints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview opened hints', 'h1');
         $I->see('No results found.');

         $I->see('Overview checked silent stations');
         $I->see('Overview checked silent stations');
         $I->click('Overview checked silent stations', 'h1');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Answered overview');
         $I->click('Answered overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview answered questions', 'h1');
         $I->see('No results found.');

          $I->see('Bonus Points overview');
          $I->click('Bonus Points overview');
          if (method_exists($I, 'wait')) {
              $I->wait(3); // only for selenium
          }
          $I->see('Overview bonuspoints', 'h1');
          $I->see('No results found.');
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

         $I->see('groep A opstart', 'h2');

         $I->see('Overview');
         $I->click('Overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('deelnemera@kiwi.run');

         $I->see('Search New Friends');
         $I->click('Search New Friends');
         $I->see('No results found.');

         $I->see('Friends');
         $I->click('Friends');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('organisatie');
         $I->see('deelnemerb');

         $I->see('Friends Requests');
         $I->click('Friends Requests');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('No results found.');

         $I->see('Select Hike');
         $I->click('Select Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Select Hike', 'h1');
         $I->see('opstart');
         $I->see('introductie');
         $I->see('gestart');
         $I->see('beindigd');

         $I->dontSee('Game overview');
         $I->amOnPageCustom('site/overview-players');
         $I->click('Game overview');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('groep A opstart');

         $I->see('Overview groups scores');
         $I->click('Overview groups scores');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview groups scores', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');

         $I->see('Passed Stations & bonuspoints');
         $I->click('Passed Stations & bonuspoints');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Overview passed stations and bonuspoints', 'h1');
         $I->see('groep A opstart');
         $I->see('groep B opstart');
         $I->see('No day selected');

         $I->see('Start New Hike');
         $I->click('Start New Hike');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Create new hike', 'h1');

         $I->dontSee('Hike overview');
         $I->amOnPageCustom('site/overview-organisation');
         $I->see('Forbidden (#403)');

         $I->dontSee('Route Overview');
         $I->amOnPageCustom('route/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Stations');
         $I->amOnPageCustom('posten/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Overview opened hints');
         $I->amOnPageCustom('open-nood-envelop/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Overview checked silent stations');
         $I->amOnPageCustom('qr-check/index');
         $I->see('Forbidden (#403)');

         $I->dontSee('Answered overview');
         $I->amOnPageCustom('open-vragen-antwoorden/index');
         $I->see('Forbidden (#403)');

          $I->dontSee('Bonus Points overview');
          $I->amOnPageCustom('bonuspunten/index');
          $I->see('Forbidden (#403)');
     }

 }
