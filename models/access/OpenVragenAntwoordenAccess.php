<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\PostPassage;
use app\models\OpenVragenAntwoorden;
use yii\web\NotFoundHttpException;

class OpenVragenAntwoordenAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function OpenVragenAntwoordenAntwoordGoed() {
        $model = $this->findModel($this->userModel->ids['open_vragen_antwoorden_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }

        if (($this->userModel->hikeStatus == EventNames::STATUS_introductie OR
            $this->userModel->hikeStatus == EventNames::STATUS_gestart) AND
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie AND ! OpenVragenAntwoorden::isAntwoordGecontroleerd($this->userModel->ids['open_vragen_antwoorden_ID'])) {
            return TRUE;
        }
        return FALSE;
    }

    function OpenVragenAntwoordenAntwoordFout() {
        $model = $this->findModel($this->userModel->ids['open_vragen_antwoorden_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }

        if (($this->userModel->hikeStatus == EventNames::STATUS_introductie OR
            $this->userModel->hikeStatus == EventNames::STATUS_gestart) AND
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie AND ! OpenVragenAntwoorden::isAntwoordGecontroleerd($this->userModel->ids['open_vragen_antwoorden_ID'])) {
            return TRUE;
        }
        return FALSE;
    }

    function OpenVragenAntwoordenBeantwoorden() {
        if ($this->userModel->hikeStatus === EventNames::STATUS_introductie and
            $this->userModel->rolPlayer === DeelnemersEvent::ROL_deelnemer) {
            return TRUE;
        }

        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer and
            PostPassage::istimeLeftToday($this->userModel->event_id, $this->userModel->groupOfPlayer)) {
            return TRUE;
        }
        return FALSE;
    }

    public function OpenVragenAntwoordenIndex() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function OpenVragenAntwoordenUpdate() {
        $model = $this->findModel($this->userModel->ids['open_vragen_antwoorden_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }

        if ($this->userModel->hikeStatus == EventNames::STATUS_introductie and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer and
            $this->userModel->groupOfPlayer == $group_id) {
            return TRUE;
        }
        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer and
            $this->userModel->groupOfPlayer == $group_id and
            PostPassage::model()->isTimeLeftToday($event_id, $group_id)) {
            return TRUE;
        }
        if (($this->userModel->hikeStatus == EventNames::STATUS_introductie or
            $this->userModel->hikeStatus == EventNames::STATUS_gestart) and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Finds the OpenVragenAntwoorden model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OpenVragenAntwoorden the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OpenVragenAntwoorden::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
