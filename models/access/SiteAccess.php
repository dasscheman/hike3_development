<?php

namespace app\models\access;

use app\models\DeelnemersEvent;
use app\models\EventNames;
use yii\web\NotFoundHttpException;
use app\models\Groups;

class SiteAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

        function SiteOverviewOrganisation() {
            if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                return TRUE;
            }
            return FALSE;
        }

        function SiteOverviewPlayers() {
            if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer) {
                return TRUE;
            }

                            return TRUE;
            if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie &&
                NULL !== $model = Groups::findOne($this->userModel->ids['group_ID'])) {

                if ($model->event_ID !== $this->userModel->event_id) {
                    return FALSE;
                }

                if ($model->event_ID !== Yii::$app->user->identity->selected) {
                    return FALSE;
                }

                return TRUE;
            }
            return FALSE;
            }

            function GroupsUpdate() {
                $model = $this->findModel($this->userModel->ids['group_ID']);



            return FALSE;
        }

}
