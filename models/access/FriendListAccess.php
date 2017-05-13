<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\FriendList;
use yii\web\NotFoundHttpException;

class FriendListAccess {

    public $userModel;

    function __construct()
    {
        $arguments = func_get_args();
        $this->userModel = $arguments[0];
    }

    function FriendListAccept() {
        $model = $this->findModel($this->userModel->ids['friend_list_ID']);

        if ($model->friends_with_user_ID === Yii::$app->user->id) {
            return TRUE;
        };
        return FALSE;
    }

    function FriendListDecline() {
        $model = $this->findModel($this->userModel->ids['friend_list_ID']);

        if ($model->friends_with_user_ID === Yii::$app->user->id) {
            return TRUE;
        };
        return FALSE;
    }

    /**
     * Finds the FriendList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FriendList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FriendList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
