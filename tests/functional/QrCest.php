<?php
use app\tests\fixtures;

class QrCest
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
              'postpassage' => [
                  'class' => fixtures\PostPassageFixture::className(),
                  'dataFile' => 'tests/fixtures/data/postpassage.php',
              ],
              'qr' => [
                  'class' => fixtures\QrFixture::className(),
                  'dataFile' => 'tests/fixtures/data/qr.php',
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
              'vragen' => [
                  'class' => fixtures\VragenFixture::className(),
                  'dataFile' => 'tests/fixtures/data/vragen.php',
              ],
         ]);
         Yii::$app->cache->flush();
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
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'qerqwerqwerqwerqwerqw']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'asdsadasdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'haergxffghhebddSEF']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'adsedvdava']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'asdsadasDCSDcSDdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'haergxffghhsdvaCQSAXXXebddSEF']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'qerqwercewcwqwerqwerqwerqw']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'asCDcwecwedsadasdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'haasxasxaergxffghhebddSEF']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'qerqwerqwccwaswerqwerqwerqw']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'asdsadacqccsdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'haergxffghhebtyrvecddSEF']);
        $I->see('Forbidden (#403)');
    }

    public function testQrScanIntroSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('scan Qr of hike intro with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'qerqwerqwerqwerqwerqw']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'asdsadasdaadq']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'haergxffghhebddSEF']);
        $I->see('Deze QR is niet voor deze hike.');

        $I->amOnPage(['site/overview-players', 'group_ID' => '3']);
        $I->dontSee('Stille posten: ');

        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'adsedvdava']);
        $I->see('Stille posten: 1');

        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'asdsadasDCSDcSDdaadq']);
        $I->see('Deze QR is vandaag niet geldig.');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'haergxffghhsdvaCQSAXXXebddSEF']);
        $I->see('Deze QR is vandaag niet geldig.');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'qerqwercewcwqwerqwerqwerqw']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'asCDcwecwedsadasdaadq']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'haasxasxaergxffghhebddSEF']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'qerqwerqwccwaswerqwerqwerqw']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'asdsadacqccsdaadq']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'haergxffghhebtyrvecddSEF']);
        $I->see('Deze QR is niet voor deze hike.');
    }

    public function testQrScanGepstartSpeler(\FunctionalTester $I)
    {
        // bij deze test is er nog niet uitgechecked bij een start post.
        $I->amGoingTo('scan Qr of hike gestart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'qerqwerqwerqwerqwerqw']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'asdsadasdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'haergxffghhebddSEF']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'adsedvdava']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'asdsadasDCSDcSDdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'haergxffghhsdvaCQSAXXXebddSEF']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'qerqwercewcwqwerqwerqwerqw']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'asCDcwecwedsadasdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'haasxasxaergxffghhebddSEF']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'qerqwerqwccwaswerqwerqwerqw']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'asdsadacqccsdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'haergxffghhebtyrvecddSEF']);
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
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'qerqwerqwerqwerqwerqw']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'asdsadasdaadq']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'haergxffghhebddSEF']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'haergxffghhebddSEF']);
        $I->see('Dit is geen geldige QR code.');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'adsedvdava']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'asdsadasDCSDcSDdaadq']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'haergxffghhsdvaCQSAXXXebddSEF']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'qerqwercewcwqwerqwerqwerqw']);
        $I->see('Deze QR is vandaag niet geldig.');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'asCDcwecwedsadasdaadq']);
        $I->see('Deze QR is vandaag niet geldig.');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'haasxasdfasd2344ergxffghhebddSEF']);
        $I->see('QR code gecontroleerd!');
        $I->amOnPage(['site/overview-players']);
        $I->see('Dag 2 gestart');
        $I->see('Dag 2 gestart tweede QR');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'qerqwerqwccwaswerqwerqwerqw']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'asdsadacqccsdaadq']);
        $I->see('Deze QR is niet voor deze hike.');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'haergxffghhebtyrvecddSEF']);
        $I->see('Deze QR is niet voor deze hike.');
    }

    public function testQrScanEindSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('Start post uitchecken');
        $I->amGoingTo('scan Qr of hike beeindigd with deelnemerb');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemerb'));
        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'qerqwerqwerqwerqwerqw']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'asdsadasdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 1, 'qr_code' => 'haergxffghhebddSEF']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'adsedvdava']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'asdsadasDCSDcSDdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 2, 'qr_code' => 'haergxffghhsdvaCQSAXXXebddSEF']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'qerqwercewcwqwerqwerqwerqw']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'asCDcwecwedsadasdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 3, 'qr_code' => 'haasxasxaergxffghhebddSEF']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'qerqwerqwccwaswerqwerqwerqw']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'asdsadacqccsdaadq']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['qr-check/create', 'event_id' => 4, 'qr_code' => 'haergxffghhebtyrvecddSEF']);
        $I->see('Forbidden (#403)');
    }
}
