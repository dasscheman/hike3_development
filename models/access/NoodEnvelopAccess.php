<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\NoodEnvelop;
use yii\web\NotFoundHttpException;

class NoodenvelopAccess {

    public $userModel;

    function __construct() {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function NoodEnvelopCreate() {
        if($this->userModel->rolPlayer !== DeelnemersEvent::ROL_organisatie) {
            return FALSE;
        }

        if ($this->userModel->hikeStatus !== EventNames::STATUS_opstart AND
            $this->userModel->hikeStatus !== EventNames::STATUS_introductie) {
            return FALSE;
        }
        return TRUE;
    }

    function NoodEnvelopUpdate() {
        $model = $this->findModel($this->userModel->ids['nood_envelop_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Finds the TblNoodEnvelop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblNoodEnvelop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NoodEnvelop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
