<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\Groups;

class GroupsAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function GroupsCreate() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    public function GroupsIndex() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer ||
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    public function GroupsIndexPosten() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_deelnemer ||
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function GroupsUpdate() {
        $model = $this->findModel($this->userModel->ids['groups_ID']);

        if ($model->event_ID !== $model->userModel->event_ID) {
            return FALSE;
        }

        if ($model->event_ID !== Yii::$app->user->identity->selected) {
            return FALSE;
        }

        if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Groups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
