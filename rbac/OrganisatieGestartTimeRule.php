<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\PostPassage;

/**
 * Checks if authorID matches user passed via params
 */
class OrganisatieGestartTimeRule extends Rule
{
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $postPassage = new PostPAssage();
        if (Yii::$app->user->identity->getStatusForEvent() == EventNames::STATUS_gestart &&
            Yii::$app->user->identity->getRolUserForEvent() == DeelnemersEvent::ROL_organisatie &&
            isset($params['group_id']) &&
            $postPassage->isGroupStarted($params['group_id']) &&
            $postPassage->istimeLeftToday($params['group_id'])) {
            return true;
        }
        return false;
    }
}
