<?php

namespace app\models\access;

use app\models\DeelnemersEvent;
use app\models\EventNames;
use yii\web\NotFoundHttpException;

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
            return FALSE;
        }

}
