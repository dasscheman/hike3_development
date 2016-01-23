<?php
//require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class admin_tests extends PHPUnit_Extensions_SeleniumTestCase
{
    protected function setUp()
    {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://localhost/');
        $this->shareSession(true);
    }

    public function testLoadPageZes()
    {
    	$this->open('hike_development/index-test.php');
        $this->waitForPageToLoad ( "30000" );
	$this->assertContains("HIKE-app*", $this->getBodyText());
    }
}
?>