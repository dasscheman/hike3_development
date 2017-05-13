<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\QrCheck;
use yii\web\NotFoundHttpException;

class QrCheckAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function QrCheckCreate() {
        if ($this->userModel->hikeStatus == EventNames::STATUS_introductie and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer) {
            return TRUE;
        }

        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer and ( PostPassage::model()->isTimeLeftToday($event_id, $this->groupOfPlayer))) {
            return TRUE;
        }
        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer and
            $this->userModel->groupOfPlayer === $this->userModel->ids['group_ID'] and
            PostPassage::model()->istimeLeftToday($this->userModel->event_id, $this->userModel->ids['group_ID'])) {
            return TRUE;
        }
        return FALSE;
    }

    public function QrCheckIndex() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function QrCheckUpdate() {
        $model = $this->findModel($this->userModel->ids['qr_check_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected) {
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
     * Finds the QrCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QrCheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QrCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
