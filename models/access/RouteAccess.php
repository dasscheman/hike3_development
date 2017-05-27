<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\Route;
use yii\web\NotFoundHttpException;

class RouteAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function RouteCreate() {
        if ($this->userModel->hikeStatus == EventNames::STATUS_opstart and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    public function RouteIndex() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function RouteMoveUpDown() {
        if ($this->userModel->parameters['move_action'] == 'down'){
            return Route::higherOrderNumberExists($this->userModel->ids['route_ID']);
        }

        if ($this->userModel->parameters['move_action'] == 'up'){
            return Route::lowererOrderNumberExists($this->userModel->ids['route_ID']);
        }
        return FALSE;
    }

    function RouteUpdate() {
        $model = $this->findModel($this->userModel->ids['route_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Finds the TblRoute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblRoute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Route::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
