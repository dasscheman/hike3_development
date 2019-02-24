<?php
 use app\tests\fixtures;

class BonuspuntenCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->haveFixtures([
             'users' => [
                 'class' => fixtures\UsersFixture::className(),
                 'dataFile' => 'tests/fixtures/data/users.php',
              ],
              'eventNames' => [
                  'class' => fixtures\EventNamesFixture::className(),
                  'dataFile' => 'tests/fixtures/data/eventnames.php',
              ],
              'deelnemersEvent' => [
                  'class' => fixtures\DeelnemersEventFixture::className(),
                  'dataFile' => 'tests/fixtures/data/deelnemersevent.php',
              ],
              'bonuspunten' => [
                  'class' => fixtures\BonuspuntenFixture::className(),
                  'dataFile' => 'tests/fixtures/data/bonuspunten.php',
              ],
              'groups' => [
                  'class' => fixtures\GroupsFixture::className(),
                  'dataFile' => 'tests/fixtures/data/groups.php',
              ],
         ]);
        // $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
    }

    public function _failed(\FunctionalTester $I)
    {
        exec("mysqldump -u test -psecret hike-app-test> tests/_data/test_dump.sql");
    }

    public function testBonuspuntenViewOpstartOrganisatie(\FunctionalTester $I)
    {
        $I->wantTo('Test bonuspunten index action');
        $I->amGoingTo('Access bonuspunten index of hike opstart with organisation');
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->selected_event_ID = 1;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->see('Overzicht bonuspunten');
        $I->see('Geen resultaten gevonden');
        $I->dontSee('bonus gestart organisatie');
        $I->dontSee('bonus gestart players groep A');
        $I->dontSee('bonus gestart players groep B');
        $I->dontSee('bonus intro organisatie');
        $I->dontSee('bonus intro players groep A');
        $I->dontSee('bonus intro players groep B');
        $I->dontSee('bonus beindigd players groep A');
        $I->dontSee('bonus beindigd players groep B');
    }

    public function testBonuspuntenViewOpstartPost(\FunctionalTester $I)
    {
        $I->wantTo('Test bonuspunten index Post rol');
        $I->amGoingTo('Access bonuspunten index of hike opstart with post rol');
        $I->amLoggedInAs(\app\models\Users::findByUsername('post'));
        Yii::$app->user->identity->selected_event_ID = 1;
        Yii::$app->user->identity->save();

        $I->amOnPage(['bonuspunten/index']);
        $I->see('Overzicht bonuspunten');
        $I->see('Geen resultaten gevonden');
        $I->dontSee('bonus gestart organisatie');
        $I->dontSee('bonus gestart players groep A');
        $I->dontSee('bonus gestart players groep B');
        $I->dontSee('bonus intro organisatie');
        $I->dontSee('bonus intro players groep A');
        $I->dontSee('bonus intro players groep B');
        $I->dontSee('bonus beindigd players groep A');
        $I->dontSee('bonus beindigd players groep B');
    }

    public function testBonuspuntenViewOpstartDeelnemer(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike opstart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 1;
        Yii::$app->user->identity->save();
        // $I->amOnPage(['bonuspunten/index']);
        // $I->dontsee('Overview bonuspoints');
        // $I->see('Forbidden (#403)');
    }

    public function testBonuspuntenIndexIntroductionOrganisation(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike introductie with organisation');
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->see('Overzicht bonuspunten');
        $I->dontSee('Geen resultaten gevonden');
        $I->dontSee('bonus gestart organisatie');
        $I->dontSee('bonus gestart players groep A');
        $I->dontSee('bonus gestart players groep B');
        $I->see('bonus intro organisatie');
        $I->see('bonus intro players groep A');
        $I->see('bonus intro players groep B');
        $I->dontSee('bonus beindigd players groep A');
        $I->dontSee('bonus beindigd players groep B');
    }

    public function testBonuspuntenIndexIntroductionPost(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike introductie with post');
        $I->amLoggedInAs(\app\models\Users::findByUsername('post'));
        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->see('Overzicht bonuspunten');
        $I->dontSee('Geen resultaten gevonden');
        $I->dontSee('bonus gestart organisatie');
        $I->dontSee('bonus gestart players groep A');
        $I->dontSee('bonus gestart players groep B');
        $I->see('bonus intro organisatie');
        $I->see('bonus intro players groep A');
        $I->see('bonus intro players groep B');
        $I->dontSee('bonus beindigd players groep A');
        $I->dontSee('bonus beindigd players groep B');
    }

    public function testBonuspuntenIndexIntroductionDeelnemer(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike introductie with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->dontsee('Overzicht bonuspunten');
        $I->see('Forbidden (#403)');
    }

    public function testBonuspuntenIndexGestartOrganisation(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike gestart with organisation');
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->see('Overzicht bonuspunten');
        $I->dontSee('Geen resultaten gevonden');
        $I->see('bonus gestart organisatie');
        $I->see('bonus gestart players groep A');
        $I->see('bonus gestart players groep B');
        $I->dontSee('bonus intro organisatie');
        $I->dontSee('bonus intro players groep A');
        $I->dontSee('bonus intro players groep B');
        $I->dontSee('bonus beindigd players groep A');
        $I->dontSee('bonus beindigd players groep B');
    }

    public function testBonuspuntenIndexGestartPost(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike gestart with post');
        $I->amLoggedInAs(\app\models\Users::findByUsername('post'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->see('Overzicht bonuspunten');
        $I->dontSee('Geen resultaten gevonden');
        $I->see('bonus gestart organisatie');
        $I->see('bonus gestart players groep A');
        $I->see('bonus gestart players groep B');
        $I->dontSee('bonus intro organisatie');
        $I->dontSee('bonus intro players groep A');
        $I->dontSee('bonus intro players groep B');
        $I->dontSee('bonus beindigd players groep A');
        $I->dontSee('bonus beindigd players groep B');
    }

    public function testBonuspuntenIndexGestartDeelnemer(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike gestart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->dontsee('Overzicht bonuspunten');
        $I->see('Forbidden (#403)');
    }

    public function testBonuspuntenIndexBeindigdOrganisation(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike beindigd with organisation');
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->see('Overzicht bonuspunten');
        $I->dontSee('Geen resultaten gevonden');
        $I->dontSee('bonus gestart organisatie');
        $I->dontSee('bonus gestart players groep A');
        $I->dontSee('bonus gestart players groep B');
        $I->dontSee('bonus intro organisatie');
        $I->dontSee('bonus intro players groep A');
        $I->dontSee('bonus intro players groep B');
        $I->see('bonus beindigd players groep A');
        $I->see('bonus beindigd players groep B');
    }

    public function testBonuspuntenIndexBeindigdPost(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike beindigd with post');
        $I->amLoggedInAs(\app\models\Users::findByUsername('post'));
        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->see('Overzicht bonuspunten');
        $I->dontSee('Geen resultaten gevonden');
        $I->dontSee('bonus gestart organisatie');
        $I->dontSee('bonus gestart players groep A');
        $I->dontSee('bonus gestart players groep B');
        $I->dontSee('bonus intro organisatie');
        $I->dontSee('bonus intro players groep A');
        $I->dontSee('bonus intro players groep B');
        $I->see('bonus beindigd players groep A');
        $I->see('bonus beindigd players groep B');
    }

    public function testBonuspuntenIndexBeindigdDeelnemer(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike beindigd with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/index']);
        $I->dontsee('Overzicht bonuspunten');
        $I->see('Forbidden (#403)');
    }

    public function testBonuspuntenCreateOrganisation(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten create with organisation');
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->selected_event_ID = 1;
        Yii::$app->user->identity->save();
        $I->amOnPage(['/site/overview-players', 'group_ID' => '1']);
        $I->dontSee('Bonus:');
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Bonuspunten toevoegen');
        $I->selectOption('form select[id=bonuspunten-group-dropdown-create]', 'groep A opstart');
        $I->submitForm('#bonuspunten-form', [
            'Bonuspunten[omschrijving]' => 'Bonuspunten vfrvfr event 1',
            'Bonuspunten[score]' => 10,
        ]);

        $I->canSeeRecord('app\models\Bonuspunten', array(
            'group_id' => '1',
            'omschrijving' => 'Bonuspunten vfrvfr event 1',
            'score' => '10'
        ));
        $I->amOnPage(['bonuspunten/index']);
        $I->see('Overzicht bonuspunten');
        $I->see('groep A opstart');
        $I->see('Bonuspunten vfrvfr event 1');
        $I->see('10');

        $I->amOnPage(['site/overview-players', 'group_ID' => '1']);
        $I->see('Bonus: 10');

        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['site/overview-players', 'group_ID' => '3']);
        $I->see('Bonus: 6');
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Bonuspunten toevoegen');
        $I->selectOption('form select[id=bonuspunten-group-dropdown-create]', 'groep A introductie');
        $I->submitForm('#bonuspunten-form', [
            'Bonuspunten[omschrijving]' => 'Bonuspunten asdfasdf event 2',
            'Bonuspunten[score]' => 9,
        ]);

        $I->canSeeRecord('app\models\Bonuspunten', array(
            'group_id' => '3',
            'omschrijving' => 'Bonuspunten asdfasdf event 2',
            'score' => '9'
        ));

        $I->amOnPage(['bonuspunten/index']);
        $I->see('groep A introductie');
        $I->see('Bonuspunten asdfasdf event 2');
        $I->dontSee('groep A gestart');
        $I->dontSee('Bonuspunten lkj event 3');
        $I->dontSee('groep A beindigd');
        $I->dontSee('Bonuspunten poi event 4');
        $I->dontSee('7');
        $I->amOnPage(['site/overview-players', 'group_ID' => '3']);
        $I->see('Bonus: 15');

        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['site/overview-players', 'group_ID' => '5']);
        $I->see('Bonus: 6');
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Bonuspunten toevoegen');

        $I->selectOption('form select[id=bonuspunten-group-dropdown-create]', 'groep A gestart');
        $I->submitForm('#bonuspunten-form', [
            'Bonuspunten[omschrijving]' => 'Bonuspunten lkj event 3',
            'Bonuspunten[score]' => 8,
        ]);

        $I->canSeeRecord('app\models\Bonuspunten', array(
          'group_id' => '5',
          'omschrijving' => 'Bonuspunten lkj event 3',
          'score' => '8'
        ));

        $I->amOnPage(['bonuspunten/index']);
        $I->dontSee('groep A introductie');
        $I->dontSee('Bonuspunten asdfasdf event 2');
        $I->see('groep A gestart');
        $I->see('Bonuspunten lkj event 3');
        $I->see('8');
        $I->dontSee('groep A introductie');
        $I->dontSee('Bonuspunten poi event 4');
        $I->dontSee('7');

        $I->amOnPage(['site/overview-players', 'group_ID' => '5']);
        $I->see('Bonus: 14');

        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();

        $I->amOnPage(['site/overview-players', 'group_ID' => '7']);
        $I->see('Bonus: 3');
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Bonuspunten toevoegen');

        $I->selectOption('form select[id=bonuspunten-group-dropdown-create]', 'groep A beindigd');
        $I->submitForm('#bonuspunten-form', [
            'Bonuspunten[omschrijving]' => 'Bonuspunten poi event 4',
            'Bonuspunten[score]' => 7,
        ]);

        $I->canSeeRecord('app\models\Bonuspunten', array(
          'group_id' => '7',
          'omschrijving' => 'Bonuspunten poi event 4',
          'score' => '7'
        ));

        $I->amOnPage(['bonuspunten/index']);
        $I->dontSee('groep A introductie');
        $I->dontSee('Bonuspunten asdfasdf event 2');
        $I->dontSee('groep A gestart');
        $I->dontSee('Bonuspunten lkj event 3');
        $I->see('groep A beindigd');
        $I->see('Bonuspunten poi event 4');
        $I->see('7');
        $I->amOnPage(['site/overview-players', 'group_ID' => '7']);
        $I->see('Bonus: 10');
    }

    public function testBonuspuntenCreatePost(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike beindigd with post');
        $I->amLoggedInAs(\app\models\Users::findByUsername('post'));
        Yii::$app->user->identity->selected_event_ID = 1;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Forbidden (#403)');

        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Forbidden (#403)');

        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Forbidden (#403)');

        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Forbidden (#403)');
    }

    public function testBonuspuntenCreateDeelnemer(\FunctionalTester $I)
    {
        $I->amGoingTo('Access bonuspunten index of hike beindigd with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 1;
        Yii::$app->user->identity->save();
        $I->amOnPage('/');
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Forbidden (#403)');

        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Forbidden (#403)');

        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Forbidden (#403)');

        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();
        $I->amOnPage(['bonuspunten/create']);
        $I->see('Forbidden (#403)');
    }
}
