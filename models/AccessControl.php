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

        if(NULL !== Yii::$app->request->get('event_id') AND
            Yii::$app->request->get('event_id') !== Yii::$app->user->identity->selected_event_ID) {
            // When a qr code is scanned the, event_ID is passed in the GET.
            // Because all checks are based on the selected_event_ID we must
            // if the GET and the selected have the same ID, if not check the GET
            // event_ID. When okey, set this id.
            // This is done here in case it is usedby other models.
            $exists = DeelnemersEvent::find()
                ->where('user_ID=:user_id AND event_ID=:event_id')
                ->addParams([
                    ':user_id' => Yii::$app->user->identity->id,
                    ':event_id' => Yii::$app->request->get('event_id')
                ])
                ->exists();

            if($exists){
                Yii::$app->user->identity->selected_event_ID = (int) Yii::$app->request->get('event_id');
                Yii::$app->user->identity->save();
            }
        }

        if(!isset(Yii::$app->user->identity->selected_event_ID)) {
            // Select the event_ID which is most recently modified.
            $selected = DeelnemersEvent::find()
                ->where('user_ID=:user_id')
                ->addParams([':user_id' => Yii::$app->user->identity->id])
                ->orderBy(['update_time'=>SORT_DESC]);

            if(!$selected->exists()) {
                return FALSE;
            }
            Yii::$app->user->identity->selected_event_ID = (int) $selected->one()->event_ID;
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
