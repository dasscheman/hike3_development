<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\Bonuspunten;
use yii\web\NotFoundHttpException;

class BonuspuntenAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }
    function BonuspuntenCreate() {
        if ($this->userModel->hikeStatus >= EventNames::STATUS_introductie and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    public function BonuspuntenIndex() {
        if ($this->userModel->rolPlayer !== DeelnemersEvent::ROL_organisatie) {
            return FALSE;
        }
        return TRUE;
    }

    function BonuspuntenUpdate() {
        $model = $this->findModel($this->userModel->ids['bonuspunten_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }

        if ($this->userModel->rolPlayer !== DeelnemersEvent::ROL_organisatie) {
            return FALSE;
        }

        if ($this->userModel->hikeStatus !== EventNames::STATUS_introductie AND
            $this->userModel->hikeStatus !== EventNames::STATUS_gestart) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Finds the Bonuspunten model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bonuspunten the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bonuspunten::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
