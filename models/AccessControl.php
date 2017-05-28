<?php

namespace app\models;

use Yii;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
// use app\models\access\BonuspuntenAccess;
// use app\models\access\RouteAccess;

class AccessControl extends HikeActiveRecord {
    public $controller_id;
    public $action_id;
    public $ids;
    public $parameters;
    public $event_id;
    public $hikeStatus;
    public $rolPlayer;
    public $groupOfPlayer;

    function isActionAllowed($controller_id = NULL, $action_id = NULL, array $ids = NULL, array $parameters = NULL) {
        AccessControl::setSelectedEventID();
        AccessControl::setControllerId($controller_id);
        AccessControl::setActionId($action_id);
        AccessControl::setIds($ids);
        AccessControl::setParameters($parameters);
        AccessControl::setEventId();
        AccessControl::setHikeStatus();
        AccessControl::setRolPlayer();
        AccessControl::setGroupOfPlayer();

        if ($this->action_id === 'error') {
            return FALSE;
        }

        // create camelcase function name and remove dashes.
        $method = implode('', array_map('ucfirst', explode('-', $this->controller_id .'-' . $this->action_id)));
        $classname = implode('', array_map('ucfirst', explode('-', $this->controller_id .'-' . 'access')));

        // Define the access class
        $class = 'app\\models\\access\\' . $classname;
        $model = new $class($this);

        if (!method_exists($model, $method) OR
            !is_callable(array($model, $method))) {
            throw new NotFoundHttpException('Method ' . $method . 'does not exist.');
        }
        return call_user_func(array($model, $method));
    }

    /**
     *
     */
    public function setSelectedEventID() {
        if(!isset(Yii::$app->user->identity->selected_event_ID)) {
            $selected = DeelnemersEvent::find()
                ->where('user_ID=:user_id')
                ->addParams([':user_id' => Yii::$app->user->identity->id])
                ->orderBy(['update_time'=>SORT_DESC])
                ->one();

            Yii::$app->user->identity->selected_event_ID = (int) $selected->event_ID;
            Yii::$app->user->identity->save();
        }
    }

    function setControllerId($controller_id) {
        if ($controller_id == NULL) {
            $this->controller_id = Yii::$app->controller->id;
        } else {
            $this->controller_id =$controller_id;
        }
    }

    function setActionId($action_id) {
        if ($action_id == NULL) {
            $this->action_id = Yii::$app->controller->action->id;
        } else {
            $this->action_id = $action_id;
        }
    }

    function setIds($ids) {
        $this->ids = $ids;
    }

    function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    function setEventId() {
        if (!isset(Yii::$app->user->identity->selected_event_ID)) {
            $this->event_id = FALSE;
            return;
        }
        $this->event_id = Yii::$app->user->identity->selected_event_ID;
    }

    function setHikeStatus() {
        if (!isset($this->event_id)) {
            $this->hikeStatus = FALSE;
            return;
        }
        $this->hikeStatus = EventNames::getStatusHike($this->event_id);
    }

    function setRolPlayer() {
        $this->rolPlayer = DeelnemersEvent::getRolOfCurrentPlayerCurrentGame();
    }

    function setGroupOfPlayer(){
        if ($this->rolPlayer === DeelnemersEvent::ROL_deelnemer &&
            isset($this->event_id)) {
            $this->groupOfPlayer = DeelnemersEvent::getGroupOfPlayer($this->event_id, Yii::$app->user->identity->id);
        }
    }
}
