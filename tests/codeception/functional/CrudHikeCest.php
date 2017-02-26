<?php
use app\tests\codeception\fixtures\UsersFixture;
use app\tests\codeception\fixtures\EventNamesFixture;

class CrudHikeCest
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
        ]);
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
    }

    public function _after(\FunctionalTester $I)
    {

    }

    public function createHikeEmptyCredentials(\FunctionalTester $I)
    {
        $I->amOnPage(['event-names/create']);
        $I->see('Create new hike', 'h1');
        $I->submitForm('#event-names-form', []);
        $I->expectTo('see validations errors');
        $I->see('Create new hike', 'h1');

        $I->see('Hike Name cannot be blank.');
    }

    public function CreateSuccessfully(\FunctionalTester $I)
    {
        $I->amOnPage(['event-names/create']);
        $I->submitForm('#event-names-form', [
            'EventNames[event_name]' => 'tester',
            'EventNames[organisation]' => 'tester',
            'EventNames[start_date]' => '2018-01-01',
            'EventNames[end_date]' => '2018-01-03',
            'EventNames[website]' => 'test content',
        ]);

        $I->dontSeeElement('#event-names-form');
        $I->see('tester', 'h2');
    }

    public function playereHasNoAccess(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\Users::findByUsername('organisatie'));
        Yii::$app->user->identity->setSelected(1);
        $I->amOnPage(['site/overview-organisation']);
        $I->see('opstart');
    }
}
