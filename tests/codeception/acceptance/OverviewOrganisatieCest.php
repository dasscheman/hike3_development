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
 class OverviewOrganisatieCest
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


          $I->amGoingTo('change max time');
          $I->see('(not set)');
          $I->see('Change max time hike');
          $disabled = $I->grabAttributeFrom('#modalEditMaxTimeButton', 'disabled');
          $I->assertEquals($disabled, 'true');

          $I->amGoingTo('change hike settings');
          $I->see('organisatie: de Bison');
          $I->see('Wijzig hike settings');
          $I->click('Wijzig hike settings');
          $I->wait(3); // only for selenium

          $I->fillField(['name' => 'EventNames[organisatie]'], 'TESTnaamwijzigen');
          $I->see('Opslaan');
          $I->click('#settings-button');

          $I->wait(3); // only for selenium
          $I->see('organisatie: TESTnaamwijzigen');

        $I->amGoingTo('Assign bonuspoints');
        $I->see('Assign bonuspoints');
        $disabled = $I->grabAttributeFrom('#modalAssingBonuspointsButton', 'disabled');
        $I->assertEquals($disabled, 'true');

        $I->amGoingTo('Add organisation');
        $I->see('Add organisation to hike');
        $I->click('Add organisation to hike');
        $I->wait(3); // only for selenium
        $I->click('#select2-deelnemersevent-user_id-container');
        $I->wait(3); // only for selenium
        $I->executeJS('jQuery("#deelnemersevent-user_id").show()');
        $I->selectOption('#deelnemersevent-user_id', 'deelnemere');

        $I->executeJS('jQuery("#deelnemers-event-rol-field-create").show()');
        $I->selectOption('#deelnemers-event-rol-dropdown-create', 'Organisatie');
        $I->wait(3); // only for selenium
        $I->click('#deelnemers-event-form-create');
        $I->wait(3); // only for selenium
        $I->see('deelnemere');

        $I->click('deelnemere');
        $I->wait(3); // only for selenium
        $I->executeJS('jQuery("#deelnemers-event-rol-field-update-6").show()');
        $I->selectOption('#deelnemers-event-rol-dropdown-update-6', 'Post');
        $I->wait(3); // only for selenium
        $I->click('#deelnemers-event-form-update-6');
        $I->wait(3); // only for selenium
        $I->see('Post');

        $I->amGoingTo('Add group');
        $I->see('Add group to hike');
        $I->click('Add group to hike');
        $I->wait(3); // only for selenium
        $I->fillField('#groups-group_name', 'NIEUWE TEST GROEP');

        $I->executeJS('jQuery("#groups-users_temp").show()');
        $I->selectOption('#groups-users_temp', 'deelnemerd');
        $I->wait(3); // only for selenium

        $I->click('#groups-form-create');
        $I->wait(3); // only for selenium
        $I->see('NIEUWE TEST GROEP');

        $I->click('#1-is_active_status-targ');
        $I->wait(3); // only for selenium
        $I->selectOption('#1-is_active_status', 'Gestart');
        $I->click('#1-is_active_status-submit');
        $I->wait(3); // only for selenium
        $I->see('Gestart');

        $I->click('#1-is_active_day-targ');
        $I->wait(3); // only for selenium
        // $I->executeJS('jQuery("#1-is_active_day").show()');
        $I->selectOption('#1-is_active_day', '2015-02-27');
        $I->click('#1-is_active_day-submit');
        $I->wait(3); // only for selenium
        $I->see('2015-02-27');

     }

     public function testMainMenuOrganisatieIntroductie(\AcceptanceTester $I)
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
         $selectHikePage->selectHike(2);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->amGoingTo('ensure that Home button works');
         $I->see('Geselecteerde hike: introductie');
         $I->click('Geselecteerde hike: introductie');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('introductie', 'h3');


       $I->amGoingTo('change max time');
       $I->see('(not set)');
       $I->see('Change max time hike');
       $disabled = $I->grabAttributeFrom('#modalEditMaxTimeButton', 'disabled');
       $I->assertEquals($disabled, 'true');

       $I->amGoingTo('change hike settings');
       $I->see('organisatie: de Bison');
       $I->see('Wijzig hike settings');
       $disabled = $I->grabAttributeFrom('#modalEditSettingsButton', 'disabled');
       $I->assertEquals($disabled, 'true');

       $I->amGoingTo('Assign bonuspoints');
       $I->see('Assign bonuspoints');
       $I->click('Assign bonuspoints');
       $I->wait(3); // only for selenium
       $I->executeJS('jQuery("#bonuspunten-group-field-create").show()');
       $I->selectOption('#bonuspunten-group-dropdown-create', 'groep A introductie');
       $I->wait(3);

       $I->fillField('input[name="Bonuspunten[omschrijving]"]', 'EXTRAPUNTEN in de introductietest');
       $I->fillField('input[name="Bonuspunten[score]"]', '777');
       $I->wait(3);
       $I->click('#save-create-bonuspunten');
       $I->wait(3);
       $I->see('EXTRAPUNTEN in de introductietest');

      $I->amGoingTo('check questoins');
       $I->see('Hoofdletter h', 'h3');
        $I->see('Hoofdletter i', 'h3');

        $I->click('#correct-awnser-4');
        $I->wait(3);

        $I->click('#wrong-awnser-5');
        $I->wait(3);

        $I->click('#wrong-awnser-6');
        $I->wait(3);

         $I->dontSee('Hoofdletter h', 'h3');
          $I->dontSee('Hoofdletter i', 'h3');

          $I->amGoingTo('Add organisation');
          $I->see('Add organisation to hike');
          $I->click('Add organisation to hike');
          $I->wait(3); // only for selenium
          $I->click('#select2-deelnemersevent-user_id-container');
          $I->wait(3); // only for selenium
          $I->executeJS('jQuery("#deelnemersevent-user_id").show()');
          $I->selectOption('#deelnemersevent-user_id', 'deelnemere');

          $I->executeJS('jQuery("#deelnemers-event-rol-field-create").show()');
          $I->selectOption('#deelnemers-event-rol-dropdown-create', 'Organisatie');
          $I->wait(3); // only for selenium
          $I->click('#deelnemers-event-form-create');
          $I->wait(3); // only for selenium
          $I->see('deelnemere');

          $I->click('deelnemere');
          $I->wait(3); // only for selenium
          $I->executeJS('jQuery("#deelnemers-event-rol-field-update-6").show()');
          $I->selectOption('#deelnemers-event-rol-dropdown-update-6', 'Post');
          $I->wait(3); // only for selenium
          $I->click('#deelnemers-event-form-update-6');
          $I->wait(3); // only for selenium
          $I->see('Post');

          $I->amGoingTo('Add group');
          $I->see('Add group to hike');
          $I->click('Add group to hike');
          $I->wait(3); // only for selenium
          $I->fillField('#groups-group_name', 'NIEUWE TEST GROEP');

          $I->executeJS('jQuery("#groups-users_temp").show()');
          $I->selectOption('#groups-users_temp', 'deelnemerd');
          $I->wait(3); // only for selenium

          $I->click('#groups-form-create');
          $I->wait(3); // only for selenium
          $I->see('NIEUWE TEST GROEP');

          $I->click('#2-is_active_status-targ');
          $I->wait(3); // only for selenium
          $I->selectOption('#2-is_active_status', 'Gestart');
          $I->click('#2-is_active_status-submit');
          $I->wait(3); // only for selenium
          $I->see('Gestart');

          $I->click('#2-is_active_day-targ');
          $I->wait(3); // only for selenium
          // $I->executeJS('jQuery("#1-is_active_day").show()');
          $I->selectOption('#2-is_active_day', '2015-02-27');
          $I->click('#2-is_active_day-submit');
          $I->wait(3); // only for selenium
          $I->see('2015-02-27');
     }

     public function testMainMenuOrganisatieGestart(\AcceptanceTester $I)
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
         $selectHikePage->selectHike(3);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->amGoingTo('ensure that Home button works');
         $I->see('Geselecteerde hike: gestart');
         $I->click('Geselecteerde hike: gestart');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('gestart', 'h3');


       $I->amGoingTo('change max time');
       $I->see('24:00:00');
       $I->see('Change max time hike');
       $I->click('#modalEditMaxTimeButton');
       $I->wait(3); // only for selenium

       $I->fillField('input[name="EventNames[max_time]"]', '12:13');
       $I->wait(3); // only for selenium
       $I->click('#set-max-time-button');
       $I->wait(3); // only for selenium
       $I->see('12:13:00');

       $I->amGoingTo('change hike settings');
       $I->see('organisatie: de Bison');
       $I->see('Wijzig hike settings');
       $disabled = $I->grabAttributeFrom('#modalEditSettingsButton', 'disabled');
       $I->assertEquals($disabled, 'true');

       $I->amGoingTo('Assign bonuspoints');
       $I->see('Assign bonuspoints');
       $I->click('Assign bonuspoints');
       $I->wait(3); // only for selenium
       $I->executeJS('jQuery("#bonuspunten-group-field-create").show()');
       $I->selectOption('#bonuspunten-group-dropdown-create', 'groep A gestart');
       $I->wait(3);

       $I->fillField('input[name="Bonuspunten[score]"]', '777');
       $I->fillField('input[name="Bonuspunten[omschrijving]"]', 'EXTRAPUNTEN in de starttest');
       $I->wait(3);
       $I->click('#save-create-bonuspunten');
       $I->wait(3);
       $I->see('EXTRAPUNTEN in de starttest');

      $I->amGoingTo('check questoins');
       $I->see('Hoofdletter a', 'h3');
        $I->see('Hoofdletter b', 'h3');

        $I->click('#correct-awnser-1');
        $I->wait(3);

        $I->click('#wrong-awnser-2');
        $I->wait(3);

        $I->click('#wrong-awnser-3');
        $I->wait(3);

         $I->dontSee('Hoofdletter a', 'h3');
          $I->dontSee('Hoofdletter b', 'h3');

          $I->amGoingTo('Add organisation');
          $I->see('Add organisation to hike');
          $I->click('Add organisation to hike');
          $I->wait(3); // only for selenium
          $I->click('#select2-deelnemersevent-user_id-container');
          $I->wait(3); // only for selenium
          $I->executeJS('jQuery("#deelnemersevent-user_id").show()');
          $I->selectOption('#deelnemersevent-user_id', 'deelnemere');

          $I->executeJS('jQuery("#deelnemers-event-rol-field-create").show()');
          $I->selectOption('#deelnemers-event-rol-dropdown-create', 'Organisatie');
          $I->wait(3); // only for selenium
          $I->click('#deelnemers-event-form-create');
          $I->wait(3); // only for selenium
          $I->see('deelnemere');

          $I->click('deelnemere');
          $I->wait(3); // only for selenium
          $I->executeJS('jQuery("#deelnemers-event-rol-field-update-6").show()');
          $I->selectOption('#deelnemers-event-rol-dropdown-update-6', 'Post');
          $I->wait(3); // only for selenium
          $I->click('#deelnemers-event-form-update-6');
          $I->wait(3); // only for selenium
          $I->see('Post');

          $I->amGoingTo('Add group');
          $I->see('Add group to hike');
          $I->click('Add group to hike');
          $I->wait(3); // only for selenium
          $I->fillField('#groups-group_name', 'NIEUWE TEST GROEP');

          $I->executeJS('jQuery("#groups-users_temp").show()');
          $I->selectOption('#groups-users_temp', 'deelnemerd');
          $I->wait(3); // only for selenium

          $I->click('#groups-form-create');
          $I->wait(3); // only for selenium
          $I->see('NIEUWE TEST GROEP');

          $I->click('#3-is_active_day-targ');
          $I->wait(3); // only for selenium
          // $I->executeJS('jQuery("#1-is_active_day").show()');
          $I->selectOption('#3-is_active_day', '2015-02-27');
          $I->click('#3-is_active_day-submit');
          $I->wait(3); // only for selenium
          $I->see('2015-02-27');


        $I->click('#3-is_active_status-targ');
        $I->wait(3); // only for selenium
        $I->selectOption('#3-is_active_status', 'Introductie');
        $I->click('#3-is_active_status-submit');
        $I->wait(3); // only for selenium
        $I->see('Introductie');

     }


     public function testMainMenuOrganisatieBeeindigd(\AcceptanceTester $I)
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
         $selectHikePage->selectHike(4);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->amGoingTo('ensure that Home button works');
         $I->see('Geselecteerde hike: beindigd');
         $I->click('Geselecteerde hike: beindigd');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }

         $I->see('beindigd', 'h3');


          $I->amGoingTo('change max time');
          $I->see('(not set)');
          $I->see('Change max time hike');
          $disabled = $I->grabAttributeFrom('#modalEditMaxTimeButton', 'disabled');
          $I->assertEquals($disabled, 'true');


         $I->amGoingTo('change hike settings');

         $I->see('organisatie: de Bison');
         $I->see('Wijzig hike settings');
         $disabled = $I->grabAttributeFrom('#modalEditSettingsButton', 'disabled');
         $I->assertEquals($disabled, 'true');


        $I->amGoingTo('Assign bonuspoints');
        $I->see('Assign bonuspoints');
        $I->click('Assign bonuspoints');
        $I->wait(3); // only for selenium
        $I->executeJS('jQuery("#bonuspunten-group-field-create").show()');
        $I->selectOption('#bonuspunten-group-dropdown-create', 'groep A beindigd');
        $I->wait(3);

        $I->fillField('input[name="Bonuspunten[score]"]', '777');
        $I->fillField('input[name="Bonuspunten[omschrijving]"]', 'EXTRAPUNTEN in de beindigdtest');
        $I->wait(3);
        $I->click('#save-create-bonuspunten');
        $I->wait(3);
        $I->see('EXTRAPUNTEN in de beindigdtest');


        $I->amGoingTo('Add organisation');
        $I->see('Add organisation to hike');
        $I->click('Add organisation to hike');
        $I->wait(3); // only for selenium
        $I->click('#select2-deelnemersevent-user_id-container');
        $I->wait(3); // only for selenium
        $I->executeJS('jQuery("#deelnemersevent-user_id").show()');
        $I->selectOption('#deelnemersevent-user_id', 'deelnemere');

        $I->executeJS('jQuery("#deelnemers-event-rol-field-create").show()');
        $I->selectOption('#deelnemers-event-rol-dropdown-create', 'Organisatie');
        $I->wait(3); // only for selenium
        $I->click('#deelnemers-event-form-create');
        $I->wait(3); // only for selenium
        $I->see('deelnemere');

        $I->click('deelnemere');
        $I->wait(3); // only for selenium
        $I->executeJS('jQuery("#deelnemers-event-rol-field-update-6").show()');
        $I->selectOption('#deelnemers-event-rol-dropdown-update-6', 'Post');
        $I->wait(3); // only for selenium
        $I->click('#deelnemers-event-form-update-6');
        $I->wait(3); // only for selenium
        $I->see('Post');

        $I->amGoingTo('Add group');
        $I->see('Add group to hike');
        $I->click('Add group to hike');
        $I->wait(3); // only for selenium
        $I->fillField('#groups-group_name', 'NIEUWE TEST GROEP');

        $I->executeJS('jQuery("#groups-users_temp").show()');
        $I->selectOption('#groups-users_temp', 'deelnemerd');
        $I->wait(3); // only for selenium

        $I->click('#groups-form-create');
        $I->wait(3); // only for selenium
        $I->see('NIEUWE TEST GROEP');

        $I->click('#4-is_active_status-targ');
        $I->wait(3); // only for selenium
        $I->selectOption('#4-is_active_status', 'Gestart');
        $I->click('#4-is_active_status-submit');
        $I->wait(3); // only for selenium
        $I->see('Gestart');

        $I->click('#4-is_active_day-targ');
        $I->wait(3); // only for selenium
        // $I->executeJS('jQuery("#1-is_active_day").show()');
        $I->selectOption('#4-is_active_day', '2015-02-27');
        $I->click('#4-is_active_day-submit');
        $I->wait(3); // only for selenium
        $I->see('2015-02-27');

     }

 }
