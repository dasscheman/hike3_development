<?php
 use app\tests\codeception\fixtures\UsersFixture;
 use app\tests\codeception\fixtures\EventNamesFixture;
 use app\tests\codeception\fixtures\DeelnemersEventFixture;
 use app\tests\codeception\fixtures\BonuspuntenFixture;
 use app\tests\codeception\fixtures\GroupsFixture;

 class BonuspuntenCest
 {
     public function _before(\FunctionalTester $I)
     {
         // parent::beforeLoad();
         // $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
         $I->haveFixtures([
             'users' => [
                 'class' => UsersFixture::className(),
                 'dataFile' => '@tests/codeception/fixtures/data/models/users.php',
              ],
              'eventNames' => [
                  'class' => EventNamesFixture::className(),
                  'dataFile' => '@tests/codeception/fixtures/data/models/eventnames.php',
              ],
              'deelnemersEvent' => [
                  'class' => DeelnemersEventFixture::className(),
                  'dataFile' => '@tests/codeception/fixtures/data/models/deelnemersevent.php',
              ],
              'bonuspunten' => [
                  'class' => BonusPuntenFixture::className(),
                  'dataFile' => '@tests/codeception/fixtures/data/models/bonuspunten.php',
              ],
              'groups' => [
                  'class' => GroupsFixture::className(),
                  'dataFile' => '@tests/codeception/fixtures/data/models/groups.php',
              ],
         ]);
         $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
     }

     public function testBonuspuntenIndexOpstart(\FunctionalTester $I)
     {
         $I->wantTo('Test bonuspunten index action');
         $I->amGoingTo('Access bonuspunten index of hike opstart with organisation');
         $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
         Yii::$app->user->identity->setSelected(1);
         $I->amOnPage(['bonuspunten/index']);
         $I->see('Overview bonuspoints');
         $I->see('No results found.');
         $I->dontSee('bonus gestart organisatie');
         $I->dontSee('bonus gestart players groep A');
         $I->dontSee('bonus gestart players groep B');
         $I->dontSee('bonus intro organisatie');
         $I->dontSee('bonus intro players groep A');
         $I->dontSee('bonus intro players groep B');
         $I->dontSee('bonus beindigd players groep A');
         $I->dontSee('bonus beindigd players groep B');

          $I->amGoingTo('Access bonuspunten index of hike opstart with deelnemera');
          $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
          Yii::$app->user->identity->setSelected(1);
          $I->amOnPage(['bonuspunten/index']);
          $I->dontsee('Overview bonuspoints');
          $I->see('Forbidden (#403)');
     }

    public function testBonuspuntenIndexIntroduction(\FunctionalTester $I)
    {
         $I->amGoingTo('Access bonuspunten index of hike introductie with organisation');
         $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
         Yii::$app->user->identity->setSelected(2);
         $I->amOnPage(['bonuspunten/index']);
         $I->see('Overview bonuspoints');
         $I->dontSee('No results found.');
         $I->dontSee('bonus gestart organisatie');
         $I->dontSee('bonus gestart players groep A');
         $I->dontSee('bonus gestart players groep B');
         $I->see('bonus intro organisatie');
         $I->see('bonus intro players groep A');
         $I->see('bonus intro players groep B');
         $I->dontSee('bonus beindigd players groep A');
         $I->dontSee('bonus beindigd players groep B');

      $I->amGoingTo('Access bonuspunten index of hike introductie with deelnemera');
      $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
      Yii::$app->user->identity->setSelected(2);
      $I->amOnPage(['bonuspunten/index']);
      $I->dontsee('Overview bonuspoints');
      $I->see('Forbidden (#403)');
     }

     public function testBonuspuntenIndexGestart(\FunctionalTester $I)
     {
         $I->amGoingTo('Access bonuspunten index of hike gestart with organisation');
         $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
         Yii::$app->user->identity->setSelected(3);
         $I->amOnPage(['bonuspunten/index']);
         $I->see('Overview bonuspoints');
         $I->dontSee('No results found.');
         $I->see('bonus gestart organisatie');
         $I->see('bonus gestart players groep A');
         $I->see('bonus gestart players groep B');
         $I->dontSee('bonus intro organisatie');
         $I->dontSee('bonus intro players groep A');
         $I->dontSee('bonus intro players groep B');
         $I->dontSee('bonus beindigd players groep A');
         $I->dontSee('bonus beindigd players groep B');


          $I->amGoingTo('Access bonuspunten index of hike gestart with deelnemera');
          $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
          Yii::$app->user->identity->setSelected(3);
          $I->amOnPage(['bonuspunten/index']);
          $I->dontsee('Overview bonuspoints');
          $I->see('Forbidden (#403)');

     }
     public function testBonuspuntenIndexBeindigd(\FunctionalTester $I)
     {
         $I->amGoingTo('Access bonuspunten index of hike beindigd with organisation');
         $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
         Yii::$app->user->identity->setSelected(4);
         $I->amOnPage(['bonuspunten/index']);
         $I->see('Overview bonuspoints');
         $I->dontSee('No results found.');
         $I->dontSee('bonus gestart organisatie');
         $I->dontSee('bonus gestart players groep A');
         $I->dontSee('bonus gestart players groep B');
         $I->dontSee('bonus intro organisatie');
         $I->dontSee('bonus intro players groep A');
         $I->dontSee('bonus intro players groep B');
         $I->see('bonus beindigd players groep A');
         $I->see('bonus beindigd players groep B');

         $I->amGoingTo('Access bonuspunten index of hike beindigd with deelnemera');
         $I->amLoggedInAs(\app\models\Users::findByUsername('deelnemera'));
         Yii::$app->user->identity->setSelected(4);
         $I->amOnPage(['bonuspunten/index']);
         $I->dontsee('Overview bonuspoints');
         $I->see('Forbidden (#403)');
     }
}
