<?php

namespace app\models\access;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use yii\web\NotFoundHttpException;

class UsersAccess {

    public $model;

    function __construct()
    {
        $arguments = func_get_args();
        $this->model = $arguments[0];
    }

    function UsersContact() {
        return TRUE;

    }

    function UsersCreate() {
        return TRUE;
    }

    public function UsersIndex() {
        if (Yii::$app->user->identity->id == Yii::$app->request->get['id']) {
            return TRUE;
        }
        return FALSE;
    }

    function UsersSearchFriends() {
        if (!Yii::$app->user->isGuest) {
            return TRUE;
        }
        return FALSE;
    }

    function UsersSearchFriendRequests() {
        if (!Yii::$app->user->isGuest) {
            return TRUE;
        }
        return FALSE;
    }

    function UsersSearchNewFriends() {
        if (!Yii::$app->user->isGuest) {
            return TRUE;
        }
        return FALSE;
    }

    function UsersSelectHike() {
        if (!Yii::$app->user->isGuest) {
            return TRUE;
        }
        return FALSE;
    }

    function UsersUpdate() {
        if (!Yii::$app->user->isGuest) {
            return TRUE;
        }
        return FALSE;
    }

    function UsersView() {
        if (!Yii::$app->user->isGuest) {
            return TRUE;
        }
        return FALSE;
    }
}
