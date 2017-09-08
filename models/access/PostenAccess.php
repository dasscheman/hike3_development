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

        if ($model->event_ID !== Yii::$app->user->identity->selected_event_ID) {
            return FALSE;
        }
        if ($this->userModel->rolPlayer == DeelnemersEvent::ROL_organisatie) {
            return TRUE;
        }
        return FALSE;
    }

    function PostenMoveUpDown() {
        $post_id = $this->userModel->ids['post_ID'];
  
        $data = $this->findModel($post_id);

        if ($this->userModel->parameters['move_action'] == 'down'){
            return Posten::higherOrderNumberExists($data->date, $data->post_volgorde);
        }

        if ($this->userModel->parameters['move_action'] == 'up'){
            return Posten::lowererOrderNumberExists($data->date, $data->post_volgorde);
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
