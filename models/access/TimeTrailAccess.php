<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\TimeTrail;
use yii\web\NotFoundHttpException;

class TimeTrailAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function TimeTrailCreate() {
        if (($this->userModel->hikeStatus == EventNames::STATUS_opstart or
            $this->userModel->hikeStatus == EventNames::STATUS_introductie) and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    public function TimeTrailIndex() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    public function TimeTrailUpdate() {
        $model = $this->findModel($this->userModel->ids['time_trail_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    public function TimeTrailDelete() {
        $model = $this->findModel($this->userModel->ids['time_trail_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function TimeTrailstatus() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer) {
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
        if (($model = TimeTrail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
