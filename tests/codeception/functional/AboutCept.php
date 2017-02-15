<?php

use tests\codeception\_pages\AboutPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that about works');
$I->amOnPage(Yii::$app->getUrlManager()->createUrl('').'site/about');
// AboutPage::openBy($I);
$I->see('Not Found', 'h1');
