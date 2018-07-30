<?php
use app\tests\fixtures;

class NoodEnvelopCest
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
              'noodEnvelop' => [
                  'class' => fixtures\NoodEnvelopFixture::className(),
                  'dataFile' => 'tests/fixtures/data/noodenvelop.php',
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
         ]);
         Yii::$app->cache->flush();
    }

        public function _after(FunctionalTester $I)
        {
        }

    public function _failed(\FunctionalTester $I)
    {
        exec("mysqldump -u root -psecret hike-app-test> tests/_data/test_dump.sql");
    }

    public function testOpenHintOpstartSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('Open hint of hike opstart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 1;
        Yii::$app->user->identity->save();
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 1]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 2]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 3]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 4]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 5]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 6]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 7]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 8]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 9]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 10]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 11]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 12]);
        $I->see('Forbidden (#403)');
    }

    public function testOpenHintIntroSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('Open hints of hike intro with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 2;
        Yii::$app->user->identity->save();
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 1]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 2]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 3]);
        $I->see('Deze hint is niet voor deze hike.');

        $I->amOnPage(['site/overview-players', 'group_ID' => '3']);
        $I->dontSee('Hints:');

        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 4]);
        $I->see('Hint Name:');
        $I->see('intro intro');
        $I->see('Weet je zeker dat je deze hint wilt openen?');
        $I->click('open-hint');

        $I->amOnPage(['site/overview-players', 'group_ID' => '3']);
        $I->see('Hints: 5');

        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 4]);
        $I->see('Jou groep heeft deze Hint al geopend.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 5]);
        $I->see('Deze hint is niet voor vandaag.');

        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 6]);
        $I->see('Deze hint is niet voor vandaag.');

        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 7]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 8]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 9]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 10]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 11]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 12]);
        $I->see('Deze hint is niet voor deze hike.');
    }

    public function testOpenHintsGepstartSpeler(\FunctionalTester $I)
    {
        // bij deze test is er nog niet uitgechecked bij een start post.
        $I->amGoingTo('Open hint of hike gestart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 1]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 2]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 3]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 4]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 5]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 6]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 7]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 8]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 9]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 10]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 11]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 12]);
        $I->see('Forbidden (#403)');
    }



    public function testOpemHintsGestartStartPosSpeler(\FunctionalTester $I)
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

        $I->amGoingTo('Open hint of hike gestart with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 3;
        Yii::$app->user->identity->save();
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 1]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 2]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 3]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 4]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 5]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 6]);
        $I->see('Deze hint is niet voor deze hike.');

        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 7]);
        $I->see('Deze hint is niet voor vandaag.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 8]);
        $I->see('Deze hint is niet voor vandaag.');
        $I->amOnPage(['site/overview-players', 'group_ID' => '5']);
        $I->dontSee('Hints:');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 9]);
        $I->see('dag 2 gestart');
        $I->see('Weet je zeker dat je deze hint wilt openen?');
        $I->click('open-hint');

        $I->amOnPage(['site/overview-players', 'group_ID' => '5']);
        $I->see('Hints: 2');

        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 10]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 11]);
        $I->see('Deze hint is niet voor deze hike.');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 12]);
        $I->see('Deze hint is niet voor deze hike.');
    }

    public function testOpenHintsEindSpeler(\FunctionalTester $I)
    {
        $I->amGoingTo('Open hint of hike beeindigd with deelnemera');
        $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
        Yii::$app->user->identity->selected_event_ID = 4;
        Yii::$app->user->identity->save();
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 1]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 2]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 3]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 4]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 5]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 6]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 7]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 8]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 9]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 10]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 11]);
        $I->see('Forbidden (#403)');
        $I->amOnPage(['open-nood-envelop/open', 'nood_envelop_ID' => 12]);
        $I->see('Forbidden (#403)');
    }
}
