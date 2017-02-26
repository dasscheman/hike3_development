<?php

use tests\codeception\_pages\LoginPage;
use tests\codeception\_pages\SelectHikePage;

/* @var $scenario Codeception\Scenario */
/*
 * DATA:
 * event_id = 4;
 * route_id = 7, 8;
 * group_id = 7, 8;
 * bonuspunten_id = 7, 8;
 * openVragen_id = 10, 11;
 * noodenvelop_id = 6;
 * qr_id = 7, 8;
 * post_id = 10, 11;
 */
 class ActionsBeindigdPlayersCest
 {
     public function _before(\AcceptanceTester $I)
     {
         $I->wantTo('ensure that login works');

         $loginPage = LoginPage::openBy($I);

         $I->see('Login', 'h1');

         $I->amGoingTo('try to login with empty credentials');
         $loginPage->login('deelnemera', 'test123');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->expectTo('see user info');
         $I->see('Logout (deelnemera)');

         $I->wantTo('ensure that Hike selection works');
         $selectHikePage = SelectHikePage::openBy($I);
         $selectHikePage->selectHike(4);
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('Geselecteerde hike: beindigd');

         $I->wantTo('ensure that Home button works');
         $I->click('Geselecteerde hike: beindigd');
         if (method_exists($I, 'wait')) {
             $I->wait(3); // only for selenium
         }
         $I->see('groep A beindigd');
         $I->see('No day selected');
     }

//      public function AntwoordenControleren(\AcceptanceTester $I)
//      {
//          $I->amGoingTo('ensure that player cannot check questions.');
//          $I->amOnPageCustom('/openVragenAntwoorden/viewControle');
//
//
//          $this->open("hike_development/index-test.php?r=game/gameOverview&event_id=4");
//          $this->waitForPageToLoad ( "30000" );
//          $this->assertContains("hike_development/index-test.php?r=game/gameOverview&event_id=4", $this->getLocation());
//          $this->assertFalse($this->isElementPresent("link=Vragen Controleren"));
//          $this->open("hike_development/index-test.php?r=open-vragen-antwoorden/index");
//          $this->assertContains("Dat mag dus niet...", $this->getBodyText());
//
//          #data aanmaken:
//          $this->open("hike_development/index-test.php?r=openVragenAntwoorden/antwoordGoedOfFout&id=>1&goedfout=>0&event_id=>3");
//          $this->assertContains("Dat mag dus niet...", $this->getBodyText());
//          $this->open("hike_development/index-test.php?r=openVragenAntwoorden/antwoordGoedOfFout&id=>2&goedfout=>1&event_id=>3");
//          $this->assertContains("Dat mag dus niet...", $this->getBodyText());
//
//
//
// // $I = new AcceptanceTester($scenario);
//
//
// $I->amOnPageCustom('/site/index');
//     }
}


//
//     ##Game Overview:
//     public function testVragenControleren()
//     {
// 		$this->login();
//

//     }
//
//
//     public function testBonuspuntenGeven()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/gameOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/gameOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Bonuspunten Geven"));
// 		$this->open("hike_development/index-test.php?r=bonuspunten/create&event_id=4");
// 		$this->waitForPageToLoad("30000");
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
//     }
//
//     public function testBeantwoordeVragenBekijken()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/gameOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/gameOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Beantwoorde Vragen"));
// 		$this->open("hike_development/index-test.php?r=openVragenAntwoorden/index&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testGeopendeHintsBekijken()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/gameOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/gameOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Geopende Hints"));
// 		$this->open("hike_development/index-test.php?r=openNoodEnvelop/index&event_id=4");
// 		$this->waitForPageToLoad ( "30000" );
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testBonuspuntenBekijken()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/gameOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/gameOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Bonuspunten Overzicht"));
// 		$this->open("hike_development/index-test.php?r=bonuspunten/index&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testGepasserdePostenBekijken()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/gameOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/gameOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Gepasserde Posten"));
// 		$this->open("hike_development/index-test.php?r=postPassage/index&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testGecheckteStillePostenBekijken()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/gameOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/gameOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Stille Posten"));
// 		$this->open("hike_development/index-test.php?r=QrCheck/index&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     ## Group Overview
//     public function testLoadGroupOverview()
// 	{
// 		$this->login();
//
// 		# bekijken van gegevens van andere groep.
// 		$this->open("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=8");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("Posten Overzicht", $this->getBodyText());
// 		$this->assertContains("Te Controleren Vragen", $this->getBodyText());
// 		$this->assertContains("Geopende Hints", $this->getBodyText());
//
// 		# bekijken van gegevens van eigen groep.
//     	$this->open("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertContains("Posten Overzicht", $this->getBodyText());
// 		$this->assertContains("Te Controleren Vragen", $this->getBodyText());
// 		$this->assertContains("Geopende Hints", $this->getBodyText());
// 	}
//
//     public function testPostBinnenkomst()
// 	{
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Binnenkomst Post"));
// 		$this->open("hike_development/index-test.php?r=postPassage/create&event_id=4&group_id=7");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
// 	public function testGroupsVragenBekijken()
// 	{
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertTrue($this->isElementPresent("link=Vragen"));
// 		$this->click("link=Vragen");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=openVragen/viewPlayers&event_id=4&group_id=7", $this->getLocation());
//
// 		$this->open("hike_development/index-test.php?r=openVragenAntwoorden/update&event_id=2&group_id=8&vraag_id=1");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("Dat mag dus niet...", $this->getBodyText());
//
// 		$this->open("hike_development/index-test.php?r=openVragenAntwoorden/update&event_id=4&group_id=7&vraag_id=1");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("Dat mag dus niet...", $this->getBodyText());
//
// 		$this->open("hike_development/index-test.php?r=openVragenAntwoorden/create&event_id=4&group_id=7&vraag_id=3");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
// 	public function testGroupsBeantwoordenVragenBekijken()
// 	{
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertTrue($this->isElementPresent("link=Beantwoorde Vragen"));
// 		$this->click("link=Beantwoorde Vragen");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=openVragenAntwoorden/viewPlayers&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertContains("Er zijn geen vragen beantwoord", $this->getBodyText());
//
//
// 	}
//
// 	public function testGroupsHintsBekijken()
// 	{
// 		$scoreHintBegin = NoodEnvelop::model()->getNoodEnvelopScore(3, 5);
// 		$scoreTotalBegin = Groups::model()->getTotalScoreGroup(3, 5);
//
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertTrue($this->isElementPresent("link=Hints"));
// 		$this->click("link=Hints");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=noodEnvelop/viewPlayers&event_id=4&group_id=7", $this->getLocation());
//
// 		$this->assertNotContains("Hint beindigd", $this->getBodyText());
// 		$this->assertContains("Er zijn geen hints", $this->getBodyText());
//
// 		$this->open("hike_development/index-test.php?r=qrCheck/create&event_id=4&qr_code=1wDlYLbS8Ws9EutrUMjNv6");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=qrCheck/create&event_id=4&qr_code=1wDlYLbS8Ws9EutrUMjNv6", $this->getLocation());
// 		$this->assertContains("Dat mag dus niet...", $this->getBodyText());
//
// 		$scoreHintEnd = NoodEnvelop::model()->getNoodEnvelopScore(3, 5);
// 		$scoreTotalEnd = Groups::model()->getTotalScoreGroup(3, 5);
// 		$this->assertEquals(7, $scoreHintBegin);
// 		$this->assertEquals(7, $scoreHintEnd);
// 		$this->assertEquals(0, $scoreHintEnd-$scoreHintBegin);
// 		$this->assertEquals(0, $scoreTotalEnd-$scoreTotalBegin);
// 	}
//
// 	public function testGroupsBonuspuntenBekijken()
// 	{
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertTrue($this->isElementPresent("link=Bonuspunten"));
// 		$this->click("link=Bonuspunten");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=bonuspunten/viewPlayers&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertContains("bonus beindigd players groep A", $this->getBodyText());
// 		$this->assertNotContains("bonus beindigd players groep B", $this->getBodyText());
// 	}
//
// 	public function testGroupsStillePostenBekijken()
// 	{
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=game/groupOverview&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertTrue($this->isElementPresent("link=Stille Posten"));
// 		$this->click("link=Stille Posten");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=qrCheck/viewPlayers&event_id=4&group_id=7", $this->getLocation());
// 		$this->assertContains("Je hebt nog geen enkele stille post gecheckt", $this->getBodyText());
// 	}
//     ## Startup Overview
//     public function testLoginAndStartupOverview()
//     {
// 		$this->login();
//
// 		$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
// 		$this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
//     }
//
//     public function testIntroductieBekijken()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Introductie"));
// 		$this->open("hike_development/index-test.php?r=route/viewIntroductie&event_id=4");
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testRouteBeheren()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Route Beheren"));
// 		$this->open("hike_development/index-test.php?r=route/index&event_id=4");
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testPostenBeheren()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Posten Beheren"));
// 		$this->open("hike_development/index-test.php?r=posten/index&event_id=4");
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testVragenOverzicht()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Vragen Overzicht"));
// 		$this->open("hike_development/index-test.php?r=openVragen/index&event_id=4");
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testHintsOverzicht()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Hints Overzicht"));
// 		$this->open("hike_development/index-test.php?r=noodEnvelop/index&event_id=4");
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testStillePostenOverzicht()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Stille Posten Overzicht"));
// 		$this->open("hike_development/index-test.php?r=qr/index&event_id=4");
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testDeelnemersToevoegen()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Deelnemers Toevoegen"));
// 		$this->open("hike_development/index-test.php?r=deelnemersEvent/create&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testGroepAanmaken()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Groep Aanmaken"));
// 		$this->open("hike_development/index-test.php?r=groups/create&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testDagVeranderen()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Dag Veranderen"));
// 		$this->open("hike_development/index-test.php?r=eventNames/changeDay&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
//
//     public function testStatusVeranderen()
//     {
// 		$this->login();
//
//     	$this->open("hike_development/index-test.php?r=startup/startupOverview&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
// 		$this->assertContains("hike_development/index-test.php?r=startup/startupOverview&event_id=4", $this->getLocation());
// 		$this->assertFalse($this->isElementPresent("link=Status Veranderen"));
// 		$this->open("hike_development/index-test.php?r=eventNames/changeStatus&event_id=4");
//         $this->waitForPageToLoad ( "30000" );
//         $this->assertContains("Dat mag dus niet...", $this->getBodyText());
// 	}
// }
