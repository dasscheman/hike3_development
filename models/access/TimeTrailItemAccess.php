<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\TimeTrailItem;
use yii\web\NotFoundHttpException;

class TimeTrailItemAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function TimeTrailItemCreate() {
        if (($this->userModel->hikeStatus == EventNames::STATUS_opstart or
            $this->userModel->hikeStatus == EventNames::STATUS_introductie) and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function TimeTrailItemMoveUpDown() {
        if ($this->userModel->parameters['move_action'] == 'down'){
            return TimeTrailItem::higherOrderNumberExists($this->userModel->ids['time_trail_item_ID']);
        }

        if ($this->userModel->parameters['move_action'] == 'up'){
            return TimeTrailItem::lowererOrderNumberExists($this->userModel->ids['time_trail_item_ID']);
        }
        return FALSE;
    }

    function TimeTrailItemReport() {
        $model = $this->findModel($this->userModel->ids['time_trail_item_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function TimeTrailItemQrcode() {
        $model = TimeTrailItem::find()
            ->where('code =:code')
            ->params([':code' => $this->userModel->ids['code']])
            ->one();

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function TimeTrailItemDelete() {
        $model = $this->findModel($this->userModel->ids['time_trail_item_ID']);
        if ($model->getTimeTrailChecks()->one() != NULL) {
             return FALSE;
        }
        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function TimeTrailItemUpdate() {
        $model = $this->findModel($this->userModel->ids['time_trail_item_ID']);
        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
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
        if (($model = TimeTrailItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
