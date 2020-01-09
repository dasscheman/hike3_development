<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\Posten;
use app\models\PostPassage;

/**
 * Checks if authorID matches user passed via params
 */
class OrganisatiePostCheckRule extends Rule
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
        if (!Yii::$app->user->identity->getStatusForEvent() == EventNames::STATUS_gestart) {
            return false;
        }

        if(!Yii::$app->user->identity->getRolUserForEvent() == DeelnemersEvent::ROL_organisatie &&
           !Yii::$app->user->identity->getRolUserForEvent() == DeelnemersEvent::ROL_post) {
            return false;
        }
        if (!isset($params['group_id']) &&
            !isset($params['post_id']) &&
            !isset($params['action'])) {
            return false;
        }

        $group_id = $params['group_id'];
        $post_id = $params['post_id'];
        $action = $params['action'];

        $post = Posten::findOne($post_id);
        if(empty($post)) {
            return false;
        }

        if($action=='start' &&
            $post->isStartPost() &&
            !$postPassage->isPostChechedOutByGroup($group_id,$post_id)) {
                return true;
        }

        if($action=='checkout' &&
            $postPassage->isPostPassedByGroup($group_id, $post_id) &&
            !$postPassage->isPostChechedOutByGroup($group_id, $post_id) &&
            $postPassage->isGroupStarted($group_id) &&
            $postPassage->istimeLeftToday($group_id)) {
                return true;
        }

        if($action=='checkin' &&
            !$post->isStartPost() &&
            $postPassage->isGroupStarted($group_id) &&
            !$postPassage->isPostPassedByGroup($group_id, $post_id) )    {
                return true;
        }

        if($action=='update')    {
            return true;
        }
        return false;
    }
}
