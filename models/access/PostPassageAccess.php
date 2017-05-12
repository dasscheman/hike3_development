<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\PostPassage;

class PostPassageAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function PostPassageCreate() {
        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart and
            $this->userModel->rolPlayer <= DeelnemersEvent::ROL_post and
            $this->userModel->groupOfPlayer === $this->userModel->ids['group_ID'] and
            PostPassage::istimeLeftToday($this->userModel->event_id, $this->userModel->ids['group_ID'])) {
            return TRUE;
        }
        return FALSE;
    }

    function PostPassageUpdate() {
        $model = $this->findModel($this->userModel->ids['posten_passage_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected) {
            return FALSE;
        }
        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart and
            $this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Finds the PostPassage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PostPassage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostPassage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
