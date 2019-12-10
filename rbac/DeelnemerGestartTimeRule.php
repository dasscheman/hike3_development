<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\DeelnemersEvent;
use app\models\Posten;
use app\models\PostPassage;
use app\models\EventNames;

/**
 * Checks if authorID matches user passed via params
 */
class DeelnemerGestartTimeRule extends Rule
{
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (Yii::$app->user->identity->getStatusForEvent() != EventNames::STATUS_gestart) {
            return false;
        }

        $posten = new Posten();
        $postPassage = new PostPassage();
        // De aanname is dat als er geen startpost is, dat er dan gewoon gestart kan worden.
        // De max tijd werkt dan niet.
        if(!empty(Yii::$app->user->identity->getActiveDayForEvent()) &&
            $posten->startPostExist(Yii::$app->user->identity->getActiveDayForEvent())) {
            if(!$postPassage->isGroupStarted(Yii::$app->user->identity->getGroupUserForEvent())) {
                return false;
            }

            if(!$postPassage->istimeLeftToday(Yii::$app->user->identity->getGroupUserForEvent())) {
                return false;
            }
        }

        return true;
    }
}
