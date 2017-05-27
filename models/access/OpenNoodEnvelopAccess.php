<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\OpenNoodEnvelop;
use app\models\PostPassage;
use yii\web\NotFoundHttpException;

class OpenNoodenvelopAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function OpenNoodEnvelopOpen() {
        if ($this->userModel->hikeStatus !== EventNames::STATUS_gestart) {
            return FALSE;
        }

        if ($this->userModel->rolPlayer !== DeelnemersEvent::ROL_deelnemer) {
            return FALSE;
        }

        if ($this->userModel->groupOfPlayer !== $this->userModel->ids['group_ID']) {
            return FALSE;
        }

        if (!PostPassage::istimeLeftToday($this->userModel->event_id, $this->userModel->ids['group_ID'])) {
            return FALSE;
        }
        return TRUE;
    }

    public function OpenNoodEnvelopIndex() {
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function OpenNoodEnvelopUpdate() {
        $model = $this->findModel($this->userModel->ids['open_nood_envelop_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }

        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Finds the TblOpenNoodEnvelop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblOpenNoodEnvelop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OpenNoodEnvelop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
