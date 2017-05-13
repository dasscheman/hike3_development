<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\Posten;
use yii\web\NotFoundHttpException;

class PostenAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function PostenCreate() {
        if (($this->userModel->hikeStatus == EventNames::STATUS_opstart or
            $this->userModel->hikeStatus == EventNames::STATUS_introductie) and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    public function PostenIndex() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function PostenUpdate() {
        $model = $this->findModel($this->userModel->ids['post_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function PostenMoveUpDown() {
        if ($this->userModel->parameters['move_action'] == 'down'){
            return Posten::higherOrderNumberExists($this->userModel->ids['post_ID']);
        }

        if ($this->userModel->parameters['move_action'] == 'up'){
            return Posten::lowererOrderNumberExists($this->userModel->ids['post_ID']);
        }
        return FALSE;
    }

    /**
     * Finds the Posten model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Posten the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Posten::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
