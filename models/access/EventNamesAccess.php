<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use yii\web\NotFoundHttpException;

class EventNamesAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function EventNamesSetMaxTime() {
        $model = $this->findModel($this->userModel->ids['event_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected) {
            return FALSE;
        }

        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie &&
            $this->userModel->hikeStatus == EventNames::STATUS_gestart) {
            return TRUE;
        }
        return FALSE;
    }

    function EventNamesUpdate() {
        $model = $this->findModel($this->userModel->ids['event_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected) {
            return FALSE;
        }

        if ($this->userModel->ids['action'] == 'set_max_time' &&
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie &&
            $this->userModel->hikeStatus == EventNames::STATUS_gestart) {
            return TRUE;
        }

        if ($this->userModel->ids['action'] == 'change_settings' &&
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie &&
            $this->userModel->hikeStatus == EventNames::STATUS_opstart) {
            return TRUE;
        }
        return FALSE;
    }

    function EventNamesUpload() {
        $model = $this->findModel($this->userModel->ids['event_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected) {
            return FALSE;
        }

        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Finds the EventNames model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EventNames the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EventNames::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
