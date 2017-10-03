<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\PostPassage;
use app\models\TimeTrailCheck;
use yii\web\NotFoundHttpException;

class TimeTrailCheckAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function TimeTrailCheckCreate() {
        if ($this->userModel->hikeStatus == EventNames::STATUS_introductie and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer) {
            return TRUE;
        }

        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer and
            PostPassage::isTimeLeftToday($this->userModel->event_id, $this->userModel->groupOfPlayer)) {
            return TRUE;
        }

        return FALSE;
    }

    function TimeTrailCheckDelete() {
//        $model = $this->findModel($this->userModel->ids['time_trail_check_ID']);
//        if ($model->getTimteTrailChecks()->one() != NULL) {
//             return FALSE;
//        }
//        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
//            return FALSE;
//        }
//        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
//            return TRUE;
//        }
        return FALSE;
    }

    function TimeTrailCheckUpdate() {
        $model = $this->findModel($this->userModel->ids['time_trail_check_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }

        if (($this->userModel->hikeStatus == EventNames::STATUS_introductie or
            $this->userModel->hikeStatus == EventNames::STATUS_gestart) and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Finds the Qr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Qr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TimeTrailCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
