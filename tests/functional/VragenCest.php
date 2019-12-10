<?php
use app\tests\fixtures;
use app\models\OpenVragenAntwoorden;

class VragenCest
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
              'posten' => [
                  'class' => fixtures\PostenFixture::className(),
                  'dataFile' => 'tests/fixtures/data/posten.php',
              ],
              'vragen' => [
                  'class' => fixtures\VragenFixture::className(),
                  'dataFile' => 'tests/fixtures/data/vragen.php',
              ],
              'deelnemersEvent' => [
                  'class' => fixtures\DeelnemersEventFixture::className(),
                  'dataFile' => 'tests/fixtures/data/deelnemersevent.php',
              ],
              'groups' => [
                  'class' => fixtures\GroupsFixture::className(),
                  'dataFile' => 'tests/fixtures/data/groups.php',
              ],
              'route' => [
                  'class' => fixtures\RouteFixture::className(),
                  'dataFile' => 'tests/fixtures/data/route.php',
              ],
              'timeTrail' => [
                  'class' => fixtures\TimeTrailFixture::className(),
                  'dataFile' => 'tests/fixtures/data/timetrail.php',
              ],
              'timeTrailItem' => [
                  'class' => fixtures\TimeTrailItemFixture::className(),
                  'dataFile' => 'tests/fixtures/data/timetrailitem.php',
              ],
              'qr' => [
                  'class' => fixtures\QrFixture::className(),
                  'dataFile' => 'tests/fixtures/data/qr.php',
              ],
         ]);
    }

    public function _failed(\FunctionalTester $I)
    {
        exec("mysqldump -u test -psecret hike-app-test> tests/_data/test_dump.sql");
    }

    public function testQrScanOpstartSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('scan Qr of hike opstart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 1;
        Yii::$app->user->identity->save();
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '1']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '2']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '3']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '4']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '5']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '6']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '7']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '8']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '9']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '10']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '11']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '12']);
        $I->see('Forbidden (#403)');
    }

    public function testBeantwoordenVraagIntroSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('Awnser question hike intro with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '1']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '2']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '3']);
        $I->see('Deze vraag is niet voor deze hike.');

        $I->amOnPage(['site/overview-players', 'group_ID' => '3']);
        $I->see('intro intro??');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '4']);
        $I->see('Geef je antwoord voor: intro intro');
        $I->see('intro intro??');
        $I->see('Answer Players');
        $I->fillField('OpenVragenAntwoorden[antwoord_spelers]', 'LALALAALAL IS DIT GOED');
        $I->click('Opslaan');
        $I->see('Vraag is beantwoord.');

        $I->amOnPage(['user/security/logout']);
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        Yii::$app->cache->flush();
        $I->amOnPage(['site/index']);
        $I->see('Answer Players: LALALAALAL IS DIT GOED');
        // $I->click('#correct-awnser-1');
        $I->click('Correct awnser');
        $I->amOnPage(['user/security/logout']);

        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();

        $I->amOnPage(['site/overview-players', 'group_ID' => '3']);
        $I->see('Vragen: 2');

        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '5']);
        $I->see('Deze vraag is niet voor vandaag.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '6']);
        $I->see('Deze vraag is niet voor vandaag.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '7']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '8']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '9']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '10']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '11']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '12']);
        $I->see('Deze vraag is niet voor deze hike.');
    }

    public function testQrScanGepstartSpeler(\FunctionalTester $I)
    {
        // bij deze test is er nog niet uitgechecked bij een start post.
        $I->amGoingTo('scan Qr of hike gestart with deelnemerb');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemerb'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();

        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '1']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '2']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '3']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '4']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '5']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '6']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '7']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '8']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '9']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '10']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '11']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '12']);
        $I->see('Forbidden (#403)');
    }



    public function testQrScanGestartStartPosSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('Start post uitchecken');
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        Yii::$app->cache->flush();
        $I->amOnPage(['posten/index']);
        $I->canSeeRecord('app\models\Posten', array(
          'post_ID' => 1,
          'event_ID' => 3
        ));

        $I->see('Start hike groep B gestart');
        $I->amOnPage(['post-passage/check-station', 'post_ID' => 1,'group_ID' => 6, 'action' => 'start']);
        $I->see('In/Uit checken van post');

        $I->fillField('PostPassage[vertrek]',date("Y-m-d H:i", time() - 3600));
      		$I->click('Create');
        $I->canSeeRecord('app\models\PostPassage', array(
          'group_id' => '6',
          'post_ID' => '1'
        ));
        $I->amOnPage(['user/security/logout']);

        $I->amGoingTo('scan Qr of hike gestart with deelnemerb');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemerb'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '1']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '2']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '3']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '4']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '5']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '6']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '7']);
        $I->see('Deze vraag is niet voor vandaag.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '8']);
        $I->see('Deze vraag is niet voor vandaag.');

        $I->amOnPage(['site/overview-players', 'group_ID' => '3']);
        $I->see('dag 2  gestart??');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '9']);
        $I->see('Geef je antwoord voor: dag 2 gestart');
        $I->see('dag 2  gestart??');
        $I->see('Answer Players');
        $I->fillField('OpenVragenAntwoorden[antwoord_spelers]', 'LULULULUL IS DIT GOED');
        $I->click('Opslaan');
        $I->see('Vraag is beantwoord.');

        $antwoord = OpenVragenAntwoorden::find()
            ->where('antwoord_spelers =:antwoord')
            ->params([':antwoord' => 'LULULULUL IS DIT GOED'])
            ->one();

        $I->amOnPage(['user/security/logout']);
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();

        $I->amOnPage(['site/index']);
        $I->see('Answer Players: LULULULUL IS DIT GOED');
        $I->click('#correct-awnser-' . $antwoord->open_vragen_antwoorden_ID);

        sleep(2);
        $I->amOnPage(['site/cache-flush']);
        $I->amOnPage(['user/security/logout']);

        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemerb'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();

        $I->amOnPage(['site/overview-players', 'group_ID' => '6']);
        $I->see('Vragen: 2');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '10']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '11']);
        $I->see('Deze vraag is niet voor deze hike.');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '12']);
        $I->see('Deze vraag is niet voor deze hike.');
    }

    public function testQrScanEindSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('scan Qr of hike beeindigd with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '1']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '2']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '3']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '4']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '5']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '6']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '7']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '8']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '9']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '10']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '11']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '12']);
        $I->see('Forbidden (#403)');
    }
}
