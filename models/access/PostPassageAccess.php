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

    function PostPassageCheckin() {
        $active_day = EventNames::getActiveDayOfHike();
        $start_post_id = Posten::getStartPost($active_day);
        $PostPassage = PostPassage::find()
            ->where('post_ID =:post_id AND group_ID =:group_id')
            ->params([':group_id' => $this->userModel->ids['group_ID'], ':post_id' => $this->userModel->ids['post_ID']])
            ->one();

        if($start_post_id === (int) $this->userModel->ids['post_ID']){
            // the selected post is a start post of current day.
            return FALSE;
        }

        if($this->userModel->ids['action'] === 'start') {
                if(!Posten::isStartPost($this->userModel->ids['post_ID'] )) {
                    return FALSE;
                }

                if (PostPassage::isGroupStarted($this->userModel->ids['group_ID'], $active_day)) {
                    return FALSE;
                }
        }
        if($this->userModel->ids['action'] === 'checkin') {
            if($PostPassage !== NULL) {
                return FALSE;
            }

            if (!PostPassage::isTimeLeftToday($this->userModel->ids['group_ID'])) {
                return FALSE;
            }
        }

        if($this->userModel->ids['action'] === 'checkout') {
            if($PostPassage === NULL) {
                return FALSE;
            }

            if ($PostPassage->event_ID !== Yii::$app->user->identity->selected_event_ID) {
                return FALSE;
            }

            if($PostPassage->gepasseerd == FALSE) {
                return FALSE;
            }

            if (!PostPassage::isTimeLeftToday($this->userModel->ids['group_ID'])) {
                return FALSE;
            }
        }

        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart &&
            $this->userModel->rolPlayer <= DeelnemersEvent::ROL_post) {
            return TRUE;
        }
        return FALSE;
    }

    function PostPassageUpdate() {
        $PostPassage = PostPassage::findOne($this->userModel->ids['posten_passage_ID']);
        if($PostPassage === NULL) {
            return FALSE;
        }

        if ($PostPassage->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }

        if(!$PostPassage->gepasseerd) {
            return FALSE;
        }

        if ($this->userModel->hikeStatus == EventNames::STATUS_gestart &&
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
