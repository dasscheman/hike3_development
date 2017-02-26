<?php

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('My Company');
$I->seeLink('Contact');
$I->click('Contact');
$I->see('Contact');
