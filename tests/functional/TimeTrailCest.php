<?php
use app\tests\fixtures;

class TimeTrailCest
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
              'timeTrail' => [
                  'class' => fixtures\TimeTrailFixture::className(),
                  'dataFile' => 'tests/fixtures/data/timetrail.php',
              ],
              'timeTrailItem' => [
                  'class' => fixtures\TimeTrailItemFixture::className(),
                  'dataFile' => 'tests/fixtures/data/timetrailitem.php',
              ],
              'timeTrailCheck' => [
                  'class' => fixtures\TimeTrailCheckFixture::className(),
                  'dataFile' => 'tests/fixtures/data/timetrailcheck.php',
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
              'vragen' => [
                  'class' => fixtures\VragenFixture::className(),
                  'dataFile' => 'tests/fixtures/data/vragen.php',
              ],
         ]);
         Yii::$app->cache->flush();
    }
    public function _failed(\FunctionalTester $I)
    {
        exec("mysqldump -u root -psecret hike-app-test> tests/_data/test_dump.sql");
    }

    public function testScanTimeTrailOpstartSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('scan timetrail of hike opstart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 1;
        Yii::$app->user->identity->save();
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsaasdfsaadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'asdfasdsffwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcassafasdfaasdf324dc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadsadcasdcasddwec']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwFRefeadcsad65hy3432dc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdGFFQFQWFwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwererh5h4h5334543c']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefewere5casdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefVRWVV45dcasdc']);
        $I->see('Forbidden (#403)');
    }

    public function testScanTimeTrailIntroSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('scan Qr of hike intro with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsaasdfsaadcsadcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'asdfasdsffwefeadcsadcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcassafasdfaasdf324dc']);
        $I->see('eerste intro');
        $I->see('ga naar twee');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadsadcasdcasddwec']);
        $I->see('Je hebt het gehaald');
        $I->see('tweede intro');
        $I->see('ga naar drie');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwFRefeadcsad65hy3432dc']);
        $I->see('Je hebt het gehaald');
        $I->see('derde intro');
        $I->see('eind');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdGFFQFQWFwefeadcsadcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwererh5h4h5334543c']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefewere5casdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefVRWVV45dcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
    }

    public function testScanTimeTrailGepstartSpeler(\FunctionalTester $I)
    {
        // bij deze test is er nog niet uitgechecked bij een start post.
        $I->amGoingTo('scan Qr of hike gestart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsaasdfsaadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'asdfasdsffwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcassafasdfaasdf324dc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadsadcasdcasddwec']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwFRefeadcsad65hy3432dc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdGFFQFQWFwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwererh5h4h5334543c']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefewere5casdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefVRWVV45dcasdc']);
        $I->see('Forbidden (#403)');
    }



    public function testScanTimeTrailGestartStartPosSpeler(\FunctionalTester $I)
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
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsaasdfsaadcsadcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'asdfasdsffwefeadcsadcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcassafasdfaasdf324dc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadsadcasdcasddwec']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwFRefeadcsad65hy3432dc']);
        $I->see('Deze tijdrit is niet voor deze hike.');

        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Het lijkt erop dat je een tijdritpunt gemist.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Het lijkt erop dat je een tijdritpunt gemist.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdGFFQFQWFwefeadcsadcasdc']);
        $I->see('eerste start');
        $I->see('ga naar twee');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Je hebt het gehaald');
        $I->see('tweede start');
        $I->see('ga naar drie');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Je hebt het gehaald');
        $I->see('derde start');
        $I->see('eind');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwererh5h4h5334543c']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefewere5casdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefVRWVV45dcasdc']);
        $I->see('Deze tijdrit is niet voor deze hike.');
    }

    public function testScanTimeTrailTimeIsUpGestartStartPosSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('Time trail with time is up uitchecken');

        $I->amGoingTo('scan Qr of hike gestart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdGFFQFQWFwefeadcsadasdc32223casdc']);
        $I->see('Jou groep heeft deze QR al gescand.');

        $I->see('Je bent te laat. Maar je moet nog steeds de QR scannen voor intructies naar het volgende item.');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXasdsa23324141Easdc']);
        $I->see('Je bent te laat');



    }

    public function testScanTimeTrailEindSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('scan Qr of hike beeindigd with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsaasdfsaadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'asdfasdsffwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadcsadcassafasdfaasdf324dc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefeadsadcasdcasddwec']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwFRefeadcsad65hy3432dc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdGFFQFQWFwefeadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadf32efdEXEasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdf78tyujyRGReadcsadcasdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwererh5h4h5334543c']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefewere5casdc']);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['time-trail-check/create', 'code' => 'sasdfsadfqwefVRWVV45dcasdc']);
        $I->see('Forbidden (#403)');
    }
}
