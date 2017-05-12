<?php

namespace app\models;

use Yii;
use yii\web\Cookie;
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
    private $_selected;

    public function init() {
        if (isset(Yii::$app->user->identity) && Yii::$app->user->identity !== NULL) {
            return;
        }
        $this->setSelected($this->getSelectedCookie());
    }

    /**
     *
     */
    public function setSelectedCookie($value) {
        $cookies = Yii::$app->getResponse()->getCookies();
        $cookies->remove('selected_event_ID');
        $cookie = new Cookie([
            'name' => 'selected_event_ID',
            'value' => $value,
            'expire' => time() + 86400 * 365,
        ]);
        $cookies->add($cookie);
    }

    function getSelectedCookie() {
        $cookies = Yii::$app->getRequest()->getCookies();
        return (int) $cookies->getValue('selected_event_ID');
    }

    function isActionAllowed($controller_id = NULL, $action_id = NULL, array $ids = NULL, array $parameters = NULL) {
        // return TRUE;
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
        return call_user_func(array($model, $method));
    }

    public function setSelected($value) {
        $id = (int) $value;
        $this->_selected = $id;
    }

    public function getSelected() {
        return $this->_selected;
    }

    function setControllerId($controller_id) {
        if ($controller_id == NULL) {
            $this->controller_id = Yii::$app->controller->id;
        } else {
            $this->controller_id =$controller_id;
        }
    }

    function setActionId($action_id) {
        if ($this->action_id == NULL) {
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
        if (!isset(Yii::$app->user->identity->selected)) {
            $this->event_id = FALSE;
            return;
        }
        $this->event_id = Yii::$app->user->identity->selected;
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

    // Depricated voor naslag nog even bewaren:
    // function indexAllowed() {
    //     if (!isset($this->event_id)) {
    //         return FALSE;
    //     }
    //     switch ($this->controller_id) {
    //         case 'nood-envelop':
    //         case 'open-vragen':
    //         case 'deelnemers-event':
    //         case 'event-names':
    //         case 'post-passage':
    //             if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                 return TRUE;
    //             }
    //         default:
    //             return FALSE;
    //     }
    // }
    //
    // function deleteAllowed() {
    //     if (!isset($this->event_id)) {
    //         return FALSE;
    //     }
    //
    //     switch ($this->controller_id) {
    //         case 'bonuspunten':
    //             if (($this->hikeStatus == EventNames::STATUS_introductie or
    //                 $this->hikeStatus == EventNames::STATUS_gestart) and
    //                 $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                 return TRUE;
    //             }
    //         case 'nood-envelop':
    //         case 'open-vragen':
    //         case 'posten':
    //         case 'qr':
    //         case 'chart':
    //         case 'groups':
    //         case 'deelnemers-event':
    //         case 'event-names':
    //         case 'groups':
    //         case 'open-nood-envelop':
    //         case 'open-vragen-antwoorden':
    //         case 'post-passage':
    //         case 'qr-check':
    //         case 'route':
    //             if ($this->hikeStatus == EventNames::STATUS_opstart and
    //                 $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                 return TRUE;
    //             }
    //             break;
    //         default:
    //             return FALSE;
    //     }
    // }
    //
    // function viewAllowed() {
    //     switch ($this->controller_id) {
    //         case 'nood-envelop':
    //         case 'open-vragen':
    //         case 'posten':
    //         case 'qr':
    //         case 'route':
    //         case 'chart':
    //         case 'groups':
    //         case 'deelnemers-event':
    //         case 'event-names':
    //         case 'groups':
    //         case 'open-nood-envelop':
    //         case 'open-vragen-antwoorden':
    //         case 'post-passage':
    //         case 'qr-check':
    //             if (!isset($this->event_id)) {
    //                 return FALSE;
    //             }
    //             if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                 return TRUE;
    //             }
    //             if (($this->hikeStatus == EventNames::STATUS_introductie or
    //                 $this->hikeStatus == EventNames::STATUS_gestart) and
    //                 $this->rolPlayer == DeelnemersEvent::ROL_post) {
    //                 return TRUE;
    //             }
    //             if ($this->hikeStatus == EventNames::STATUS_beindigd) {
    //                 return TRUE;
    //             }
    //         default:
    //             return FALSE;
    //     }
    // }
    //
    // function viewPlayersAllowed() {
    //     if (!isset($this->event_id)) {
    //         return FALSE;
    //     }
    //
    //     switch ($this->controller_id) {
    //         case 'bonuspunten':
    //         case 'qr-check':
    //         case 'open-vragen':
    //         case 'open-vragen-antwoorden':
    //             if (($this->hikeStatus == EventNames::STATUS_introductie or
    //                 $this->hikeStatus == EventNames::STATUS_gestart) and
    //                 $this->rolPlayer == DeelnemersEvent::ROL_deelnemer and
    //                 $this->groupOfPlayer == $group_id) {
    //                 return TRUE;
    //             }
    //             if (($this->hikeStatus == EventNames::STATUS_introductie or
    //                 $this->hikeStatus == EventNames::STATUS_gestart) and
    //                 $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                 return TRUE;
    //             }
    //
    //             if (($this->hikeStatus == EventNames::STATUS_introductie or
    //                 $this->hikeStatus == EventNames::STATUS_gestart) and
    //                 $this->rolPlayer == DeelnemersEvent::ROL_post) {
    //                 return TRUE;
    //             }
    //             if ($this->hikeStatus == EventNames::STATUS_beindigd) {
    //                 return TRUE;
    //             }
    //             break;
    //         case 'nood-envelop':
    //         case 'open-nood-envelop':
    //         case 'post-passage':
    //             if ($this->hikeStatus == EventNames::STATUS_gestart AND
    //                 $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                 return TRUE;
    //             }
    //             if ($this->hikeStatus == EventNames::STATUS_gestart and
    //                 $this->rolPlayer == DeelnemersEvent::ROL_post) {
    //                 return TRUE;
    //             }
    //             if ($this->hikeStatus == EventNames::STATUS_beindigd) {
    //                 return TRUE;
    //             }
    //
    //             if ($this->hikeStatus == EventNames::STATUS_gestart and
    //                 $this->rolPlayer == DeelnemersEvent::ROL_deelnemer and
    //                 $this->groupOfPlayer == $group_id) {
    //                 return TRUE;
    //             }
    //             break;
    //         default:
    //             return FALSE;
    //     }
    // }
    //
    // function createIntroductieAllowed() {
    //     if (!isset($this->event_id)) {
    //         return FALSE;
    //     }
    //
    //     switch ($this->controller_id) {
    //         case 'open-vragen':
    //         case 'qr':
    //             if ($this->hikeStatus == EventNames::STATUS_opstart and
    //                 $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                 return TRUE;
    //             }
    //             break;
    //         default:
    //             return FALSE;
    //     }
    // }
    //
    // function moveUpDownAllowed(){
    //
    //     if (!isset($this->parameters['date']) || !isset($this->parameters['move_action'])){
    //         return FALSE;
    //     }
    //     if ($this->hikeStatus != EventNames::STATUS_opstart or
    //         $this->rolPlayer != DeelnemersEvent::ROL_organisatie) {
    //             return FALSE;
    //     }
    //
    //     switch ($this->controller_id) {
    //         case 'qr':
    //             if ($this->parameters['move_action'] == 'up'){
    //                 return Qr::higherOrderNumberExists($this->ids[0]);
    //             }
    //             if ($this->parameters['move_action'] == 'down'){
    //                 return Qr::lowererOrderNumberExists($this->ids[0]);
    //             }
    //         case 'open-vragen':
    //              if ($this->parameters['move_action'] == 'up') {
    //                 return OpenVragen::higherOrderNumberExists($this->ids[0]);
    //             }
    //             if ($this->parameters['move_action'] == 'down') {
    //                 return OpenVragen::lowerOrderNumberExists($this->ids[0]);
    //             }
    //         case 'posten':
    //             if ($this->parameters['move_action'] == 'up') {
    //                 return Posten::higherOrderNumberExists($this->ids[0]);
    //             }
    //             if ($this->parameters['move_action'] == 'down') {
    //                 return Posten::lowererOrderNumberExists($this->ids[0]);
    //             }
    //         case 'nood-envelop':
    //             if ($this->parameters['move_action'] == 'up'){
    //                 return NoodEnvelop::higherOrderNumberExists($this->ids[0]);
    //             }
    //             if ($this->parameters['move_action'] == 'down'){
    //                 return NoodEnvelop::lowererOrderNumberExists($this->ids[0]);
    //             }
    //         default:
    //             return FALSE;
    //     }
    // }
    //
    // function viewIntroductieAllowed() {
    //     switch ($this->controller_id) {
    //         case 'route':
    //             if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie ){
    //                 return TRUE;
    //             }
    //         default:
    //             return FALSE;
    //     }
    // }
    //
    // function defaultAllowed() {
    //     if (!isset($this->event_id)) {
    //         return FALSE;
    //     }
    //
    //     switch ($this->controller_id) {
    //         case 'event-names':
    //             if ($this->action_id == 'changeStatus'){
    //                 if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                     return TRUE;
    //                 }
    //             }
    //         case 'organisatie':
    //             if ($this->rolPlayer <= DeelnemersEvent::ROL_deelnemer &&
    //                 $this->action_id == 'gameOverview') {
    //                 return TRUE;
    //             }
    //             if ($this->action_id == 'groupOverview') {
    //                 if ($this->rolPlayer <= DeelnemersEvent::ROL_post) {
    //                     return TRUE;
    //                 }
    //                 if ($this->rolPlayer == DeelnemersEvent::ROL_deelnemer &&
    //                     $group_id == $group_id_of_player &&
    //                     ($this->hikeStatus == EventNames::STATUS_gestart ||
    //                     $this->hikeStatus == EventNames::STATUS_introductie)) {
    //                     return TRUE;
    //                 }
    //                 if ($this->rolPlayer == DeelnemersEvent::ROL_deelnemer &&
    //                     $this->hikeStatus == EventNames::STATUS_beindigd) {
    //                     return TRUE;
    //                 }
    //             }
    //             if (isset($this->rolPlayer) && $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                 if ($this->action_id == 'overview') {
    //                     return TRUE;
    //                 }
    //                 if ($this->controller_id === 'event-names' && $this->action_id == 'changeStatus') {
    //                     if (($this->hikeStatus == EventNames::STATUS_opstart or
    //                         $this->hikeStatus == EventNames::STATUS_introductie or
    //                         $this->hikeStatus == EventNames::STATUS_gestart) and
    //                         $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                         return TRUE;
    //                     }
    //                 }
    //                 if ($this->controller_id === 'event-names' && $this->action_id == 'changeDay') {
    //                     if ($this->hikeStatus == EventNames::STATUS_gestart and
    //                         $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                         return TRUE;
    //                     }
    //                 }
    //             }
    //         default:
    //     }
    //     if ($this->controller_id === 'open-vragen-antwoorden') {
    //         switch ($this->action_id) {
    //
    //             case 'viewControle':
    //                 if (($this->hikeStatus == EventNames::STATUS_introductie OR
    //                     $this->hikeStatus == EventNames::STATUS_gestart) AND
    //                     $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                     return TRUE;
    //                 }
    //                 break;
    //             case 'updateOrganisatie':
    //                 if (($this->hikeStatus == EventNames::STATUS_introductie OR
    //                     $this->hikeStatus == EventNames::STATUS_gestart) AND
    //                     $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
    //                     return TRUE;
    //                 }
    //                 break;
    //         }
    //     }
    //
    //     if ($this->controller_id === 'post-passage') {
    //
    //         if ($this->action_id == 'create' and
    //             $this->hikeStatus == EventNames::STATUS_gestart and
    //             $this->rolPlayer <= DeelnemersEvent::ROL_post and
    //             PostPassage::isTimeLeftToday($event_id, $group_id) and
    //             Posten::existPostForActiveDay($event_id) and ! PostPassage::isFirstPostOfDayForGroup($event_id, $group_id) and
    //             PostPassage::notAllPostsOfDayPassedByGroup($event_id, $group_id)) {
    //             return TRUE;
    //         }
    //
    //         if ($this->action_id == 'createDayStart' and
    //             $this->hikeStatus == EventNames::STATUS_gestart and
    //             $this->rolPlayer == DeelnemersEvent::ROL_organisatie and
    //             Posten::model()->existPostForActiveDay($event_id) and
    //             PostPassage::isFirstPostOfDayForGroup($event_id, $group_id)) {
    //             return TRUE;
    //         }
    //
    //         if ($this->action_id == 'updateVertrek' and
    //             $this->hikeStatus == EventNames::STATUS_gestart and
    //             $this->rolPlayer <= DeelnemersEvent::ROL_post and
    //             PostPassage::model()->isTimeLeftToday($event_id, $group_id) and
    //             Posten::model()->existPostForActiveDay($event_id) and
    //             PostPassage::model()->notAllPostsOfDayPassedByGroup($event_id, $group_id) and ! PostPassage::model()->isFirstPostOfDayForGroup($event_id, $group_id)) {
    //             return TRUE;
    //         }
    //     }
    //
    //     return FALSE;
    // }
}
