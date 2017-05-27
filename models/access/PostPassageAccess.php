<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\PostPassage;
use app\models\Posten;
use yii\web\NotFoundHttpException;

class PostPassageAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function PostPassageStart() {
        $active_day = EventNames::getActiveDayOfHike();
        $start_post_id = Posten::getStartPost($active_day);
        if($this->userModel->ids['post_ID'] !== $start_post_id){
            // the selected post is NOT a start post of current day.
            return FALSE;
        }
        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart AND
            $this->userModel->rolPlayer <= DeelnemersEvent::ROL_post AND
            Posten::isStartPost($this->userModel->ids['post_ID']) AND
            !PostPassage::isGroupStarted($this->userModel->ids['group_ID'], $active_day)) {
            return TRUE;
        }
        return FALSE;
    }

    function PostPassageCheckin() {
        $active_day = EventNames::getActiveDayOfHike();
        $start_post_id = Posten::getStartPost($active_day);
        if($this->userModel->ids['post_ID'] === $start_post_id){
            // the selected post is a start post of current day.
            return FALSE;
        }
        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart AND
            $this->userModel->rolPlayer <= DeelnemersEvent::ROL_post AND
            PostPassage::isTimeLeftToday($this->userModel->event_id, $this->userModel->ids['group_ID']) AND
            !PostPassage::isPostPassedByGroup($this->userModel->ids['group_ID'], $this->userModel->ids['post_ID'])) {
            return TRUE;
        }
        return FALSE;
    }

    function PostPassageCheckout() {
        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart AND
            $this->userModel->rolPlayer <= DeelnemersEvent::ROL_post AND
            PostPassage::isTimeLeftToday($this->userModel->event_id, $this->userModel->ids['group_ID']) AND
            PostPassage::isPostPassedByGroup($this->userModel->ids['group_ID'], $this->userModel->ids['post_ID'])) {
            return TRUE;
        }
        return FALSE;
    }

    function PostPassageUpdate() {
        $model = $this->findModel($this->userModel->ids['posten_passage_ID']);

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
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
