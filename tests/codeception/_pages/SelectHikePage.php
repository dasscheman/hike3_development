<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents login page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class SelectHikePage extends BasePage
{
    public $route = 'event-names/select-hike';

    /**
     * @param string $username
     * @param string $password
     */
    public function selectHike($event_id)
    {
        $this->actor->click('#select-hike-'. $event_id);
    }
}
