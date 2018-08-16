<?php
use app\tests\fixtures;

class PostenCest
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
              'postPassage' => [
                  'class' => fixtures\PostPassageFixture::className(),
                  //'dataFile' => 'tests/fixtures/data/posten.php',
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
              'qr' => [
                  'class' => fixtures\QrFixture::className(),
                  'dataFile' => 'tests/fixtures/data/qr.php',
              ],
              'timeTrail' => [
                  'class' => fixtures\TimeTrailFixture::className(),
                  'dataFile' => 'tests/fixtures/data/timetrail.php',
              ],
              'timeTrailItem' => [
                  'class' => fixtures\TimeTrailItemFixture::className(),
                  'dataFile' => 'tests/fixtures/data/timetrailitem.php',
              ],
              'vragen' => [
                  'class' => fixtures\VragenFixture::className(),
                  'dataFile' => 'tests/fixtures/data/vragen.php',
              ],
              'noodEnvelop' => [
                  'class' => fixtures\NoodEnvelopFixture::className(),
                  'dataFile' => 'tests/fixtures/data/noodenvelop.php',
              ],
         ]);
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function _failed(\FunctionalTester $I)
    {
        exec("mysqldump -u root -psecret hike-app-test> tests/_data/test_dump.sql");
    }

    public function testCheckTimeGestartStartPosSpeler(\FunctionalTester $I)
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

        $I->see('Start hike groep A gestart');
        $I->amOnPage(['post-passage/check-station', 'post_ID' => 1,'group_ID' => 5, 'action' => 'start']);
        $I->see('In/Uit checken van post');

        $I->fillField('PostPassage[vertrek]',date("Y-m-d H:i", time() - 3600));
    	$I->click('Create');
        $I->canSeeRecord('app\models\PostPassage', array(
          'group_id' => '5',
          'post_ID' => '1'
        ));
        $I->amOnPage(['user/security/logout']);

        $I->amGoingTo('scan Qr of hike gestart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['site/index']);
        $I->see('Looptijd: 01:00');
        $I->see('Te gaan: 22:');


        $I->amOnPage(['user/security/logout']);
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        Yii::$app->cache->flush();
        $I->amOnPage(['posten/index']);
        $I->canSeeRecord('app\models\Posten', array(
          'post_ID' => 2,
          'event_ID' => 3
        ));

        $I->see('Checkin groep A gestart');
        $I->dontSeeElement('#check-post-2-5[disabled]');
        $I->see('Checkin groep B gestart');
        $I->seeElement('#check-post-2-6[disabled]');

        $I->amOnPage(['post-passage/check-station', 'post_ID' => 2,'group_ID' => 5, 'action' => 'checkin']);
        $I->see('In/Uit checken van post');

        $I->fillField('PostPassage[binnenkomst]',date("Y-m-d H:i", time() - 1800));
  		$I->click('Create');
        $I->canSeeRecord('app\models\PostPassage', array(
          'group_id' => '5',
          'post_ID' => '2'
        ));
        $I->amOnPage(['user/security/logout']);

        $I->amGoingTo('scan Qr of hike gestart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['site/index']);
        $I->see('Looptijd: 00:30');
        $I->see('Te gaan: 23:30');

        $I->amOnPage(['qr-check/create', 'qr_code' => 'haasxasxaergxffghhebddSEF', 'event_id' => 3]);
        $I->see('QR code gecontroleerd!');

        $I->amOnPage(['posten/index']);
        $I->dontSee('Hints:');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 13]);
        $I->see('dag 2 gestart tweede hint');
        $I->see('Weet je zeker dat je deze hint wilt openen?');
        $I->click('open-hint');

        $I->amOnPage(['site/overview-players', 'group_ID' => '5']);
        $I->see('Hints: 5');

        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '9']);
        $I->see('Geef je antwoord voor: dag 2 gestart');
        $I->see('dag 2  gestart??');
        $I->see('Answer Players');
        $I->fillField('OpenVragenAntwoorden[antwoord_spelers]', 'LOLOLOLOL IS DIT GOED');
        $I->click('Opslaan');
        $I->see('Vraag is beantwoord.');

        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdGFFQFQWFwefeadcsadcasdc']);
        $I->see('eerste start');
        $I->see('ga naar twee');
    }

    public function testCheckItemsNotstartedGestartStartPosSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('Check Qr, hints, vragen, time trial when not checkout from start');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemerb'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['site/index']);
        $I->see('Looptijd: 00:00:00');

        $I->amOnPage(['qr-check/create', 'qr_code' => 'haasxasxaergxffghhebddSEF', 'event_id' => 3]);
        $I->see('Forbidden (#403)');

        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 9]);
        $I->see('Forbidden (#403)');

        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '9']);
        $I->see('Forbidden (#403)');

        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdGFFQFQWFwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');

    }

    public function testCheckItemsTimeIsUpGestartStartPosSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('Check Qr, hints, vragen, time trial when Time is upfrom start');
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

        $I->fillField('PostPassage[vertrek]',date("Y-m-d H:i", time() - 3600*25));
    	$I->click('Create');
        $I->canSeeRecord('app\models\PostPassage', array(
          'group_id' => '6',
          'post_ID' => '1'
        ));
        $I->amOnPage(['user/security/logout']);

        $I->amGoingTo('Check time is up for group B');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemerb'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['site/index']);
        $I->see('Looptijd: 01:00');
        $I->see('Te gaan: 00:00:00');

        $I->amOnPage(['qr-check/create', 'qr_code' => 'haasxasxaergxffghhebddSEF', 'event_id' => 3]);
        $I->see('Forbidden (#403)');

        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 9]);
        $I->see('Forbidden (#403)');

        $I->amOnPage(['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => '9']);
        $I->see('Forbidden (#403)');

        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdGFFQFQWFwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
    }
}
