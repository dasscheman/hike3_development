<?php

namespace app\models;

use Yii;

class AccessControl extends HikeActiveRecord {

    public $controller_id;
    public $action_id;
    public $ids;
    public $parameters;
    public $event_id;
    public $hikeStatus;
    public $rolPlayer;
    public $groupOfPlayer;
    
    function isActionAllowed($controller_id = NULL, $action_id = NULL, array $ids = array(), array $parameters = array()) {
               
        AccessControl::setControllerId($controller_id);
        AccessControl::setActionId($action_id);
        AccessControl::setIds($ids);
        AccessControl::setParameters($parameters);
        AccessControl::setEventId();
        AccessControl::setHikeStatus();
        AccessControl::setRolPlayer();
        AccessControl::setGroupOfPlayer();
        
        switch ($this->action_id) {
            case 'index':
                return AccessControl::indexAllowed();
                break;
            case 'view':
                return AccessControl::viewAllowed();
                break;
            case 'create':
                return AccessControl::createAllowed();
                break;
            case 'createIntroductie':
                return AccessControl::createIntroductieAllowed();
                break;
            case 'update':
            case 'updateImage':
                return AccessControl::updateAllowed();
                break;
            case 'delete':
                return AccessControl::deleteAllowed();
                break;
            case 'viewPlayers':
                return AccessControl::viewPlayersAllowed();
                break;
            case 'moveUpDown':
                return AccessControl::moveUpDownAllowed();
            case 'ViewIntroductie':
                return AccessControl::viewIntroductieAllowed();
            default:
                return AccessControl::defaultAllowed();
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
        $this->rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);
    }
    
    function setGroupOfPlayer(){
        if ($this->rolPlayer === DeelnemersEvent::ROL_deelnemer &&
            isset($this->event_id)) {
            $this->groupOfPlayer = DeelnemersEvent::getGroupOfPlayer($this->event_id, Yii::app()->user->id);
        }
    }
    
    function indexAllowed() {
        if (!isset($this->event_id)) {
            return FALSE;
        }
    
        switch ($this->controller_id) {
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'route':
            case 'groups':
            case 'deelnemersEvent':
            case 'eventNames':
            case 'groups':
            case 'route':
            case 'startup':
                if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'openNoodEnvelop':
            case 'postPassage':
                if ($this->hikeStatus > EventNames::STATUS_introductie AND
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'qrCheck':
            case 'bonuspunten':
            case 'openVragenAntwoorden':
                if ($this->hikeStatus <> EventNames::STATUS_opstart AND
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
            default:
                return FALSE;
        }
    }

    function updateAllowed() {
        if ($this->controller_id === 'users' &&
            array_key_exists('user_id', $this->ids) &&
            Yii::$app->user->identity->id === $this->ids['user_id']) {
            return TRUE;
        }

        if (!isset($this->event_id)) {
            return FALSE;
        }

        switch ($this->controller_id) {
            case 'openVragenAntwoorden':
                if ($this->hikeStatus == EventNames::STATUS_introductie and
                    $this->rolPlayer == DeelnemersEvent::ROL_deelnemer and
                    $this->groupOfPlayer == $group_id) {
                    return TRUE;
                }
                if ($this->hikeStatus == EventNames::STATUS_gestart and
                    $this->rolPlayer == DeelnemersEvent::ROL_deelnemer and
                    $this->groupOfPlayer == $group_id and
                    PostPassage::model()->isTimeLeftToday($event_id, $group_id)) {
                    return TRUE;
                }
            case 'bonuspunten':
            case 'qrCheck':
                if (($this->hikeStatus == EventNames::STATUS_introductie or
                    $this->hikeStatus == EventNames::STATUS_gestart) and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'openNoodEnvelop':
            case 'postPassage':
                if ($this->hikeStatus == EventNames::STATUS_gestart and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'route':
                if ($this->hikeStatus == EventNames::STATUS_opstart and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'groups':
            case 'deelnemersEvent':
            case 'groups':
                if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'eventNames':
                if ($this->hikeStatus == EventNames::STATUS_opstart and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            default:
                return FALSE;
        }
    }

    function createAllowed() {
        if (!isset($this->event_id)) {
            return FALSE;
        }

        switch ($this->controller_id) {
            case 'bonuspunten':
                if ($this->hikeStatus >= EventNames::STATUS_introductie and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'groups':
                if (($this->hikeStatus == EventNames::STATUS_opstart or
                    $this->hikeStatus == EventNames::STATUS_introductie) and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'deelnemersEvent':
                if (($this->hikeStatus == EventNames::STATUS_opstart or
                    $this->hikeStatus == EventNames::STATUS_introductie) and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'route':
                if ($this->hikeStatus == EventNames::STATUS_opstart and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'eventNames':
            case 'users':
                return TRUE;
                break;
            case 'qrCheck':
            case 'openVragenAntwoorden':
                if ($this->hikeStatus == EventNames::STATUS_introductie and
                    $this->rolPlayer == DeelnemersEvent::ROL_deelnemer) {
                    return TRUE;
                }

                if ($this->hikeStatus == EventNames::STATUS_gestart and
                    $this->rolPlayer == DeelnemersEvent::ROL_deelnemer and ( PostPassage::model()->isTimeLeftToday($event_id, $this->groupOfPlayer))) {
                    return TRUE;
                }
            // Hier geen break. OpenNoodenvelop en postPassage moeten uitgesloten worden voor de introductie.
            case 'openNoodEnvelop':
            case 'postPassage':
                if ($this->hikeStatus == EventNames::STATUS_gestart and
                    $this->rolPlayer == DeelnemersEvent::ROL_deelnemer and
                    $this->groupOfPlayer === $this->ids['group_id'] and
                    PostPassage::model()->istimeLeftToday($this->event_id, $this->ids['group_id'])) {
                    return TRUE;
                }
                break;
            default:
                return FALSE;
        }
    }

    function deleteAllowed() {
        if (!isset($this->event_id)) {
            return FALSE;
        }

        switch ($this->controller_id) {
            case 'bonuspunten':
                if (($this->hikeStatus == EventNames::STATUS_introductie or
                    $this->hikeStatus == EventNames::STATUS_gestart) and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'chart':
            case 'groups':
            case 'deelnemersEvent':
            case 'eventNames':
            case 'groups':
            case 'openNoodEnvelop':
            case 'openVragenAntwoorden':
            case 'postPassage':
            case 'qrCheck':
            case 'route':
                if ($this->hikeStatus == EventNames::STATUS_opstart and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            default:
                return FALSE;
        }
    }

    function viewAllowed() {
        if (!isset($this->event_id)) {
            return FALSE;
        }

        switch ($this->controller_id) {
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'route':
            case 'chart':
            case 'groups':
            case 'deelnemersEvent':
            case 'eventNames':
            case 'groups':
            case 'openNoodEnvelop':
            case 'openVragenAntwoorden':
            case 'postPassage':
            case 'qrCheck':
                if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                if (($this->hikeStatus == EventNames::STATUS_introductie or
                    $this->hikeStatus == EventNames::STATUS_gestart) and
                    $this->rolPlayer == DeelnemersEvent::ROL_post) {
                    return TRUE;
                }
                if ($this->hikeStatus == EventNames::STATUS_beindigd) {
                    return TRUE;
                }
            default:
                return FALSE;
        }
    }

    function viewPlayersAllowed() {
        if (!isset($this->event_id)) {
            return FALSE;
        }

        switch ($this->controller_id) {
            case 'bonuspunten':
            case 'qrCheck':
            case 'openVragen':
            case 'openVragenAntwoorden':
                if (($this->hikeStatus == EventNames::STATUS_introductie or
                    $this->hikeStatus == EventNames::STATUS_gestart) and
                    $this->rolPlayer == DeelnemersEvent::ROL_deelnemer and
                    $this->groupOfPlayer == $group_id) {
                    return TRUE;
                }
                if (($this->hikeStatus == EventNames::STATUS_introductie or
                    $this->hikeStatus == EventNames::STATUS_gestart) and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }

                if (($this->hikeStatus == EventNames::STATUS_introductie or
                    $this->hikeStatus == EventNames::STATUS_gestart) and
                    $this->rolPlayer == DeelnemersEvent::ROL_post) {
                    return TRUE;
                }
                if ($this->hikeStatus == EventNames::STATUS_beindigd) {
                    return TRUE;
                }
                break;
            case 'noodEnvelop':
            case 'openNoodEnvelop':
            case 'postPassage':
                if ($this->hikeStatus == EventNames::STATUS_gestart AND
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                if ($this->hikeStatus == EventNames::STATUS_gestart and
                    $this->rolPlayer == DeelnemersEvent::ROL_post) {
                    return TRUE;
                }
                if ($this->hikeStatus == EventNames::STATUS_beindigd) {
                    return TRUE;
                }

                if ($this->hikeStatus == EventNames::STATUS_gestart and
                    $this->rolPlayer == DeelnemersEvent::ROL_deelnemer and
                    $this->groupOfPlayer == $group_id) {
                    return TRUE;
                }
                break;
            default:
                return FALSE;
        }
    }

    function createIntroductieAllowed() {
        if (!isset($this->event_id)) {
            return FALSE;
        }
        
        switch ($this->controller_id) {
            case 'openVragen':
            case 'qr':
                if ($this->hikeStatus == EventNames::STATUS_opstart and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            default:
                return FALSE;
        }
    }   

    function moveUpDownAllowed(){
        
        if (!isset($this->parameters['date']) || !isset($this->parameters['move'])){
            return FALSE;
        }
        if ($this->hikeStatus != EventNames::STATUS_opstart or
            $this->rolPlayer != DeelnemersEvent::ROL_organisatie) {
                return FALSE;
        }
        
        switch ($this->controller_id) {
            case 'qr':
                if ($move == 'up'){
                    $nextOrderExist = Qr::higherOrderNumberExists($event_id,
                                                                           $model_id,
                                                                           $order,
                                                                           $route_id);
                }
                if ($move == 'down'){
                    $nextOrderExist = Qr::lowererOrderNumberExists($event_id,
                                                                            $model_id,
                                                                            $order,
                                                                            $route_id);
                }
            case 'openVragen':
                 if ($move == 'up') {
                    $nextOrderExist = OpenVragen::higherOrderNumberExists($event_id,
                                                                        $model_id,
                                                                        $order,
                                                                        $route_id);
                }
                if ($move == 'down') {
                    $nextOrderExist = OpenVragen::lowerOrderNumberExists($event_id,
                                                                        $model_id,
                                                                        $order,
                                                                        $route_id);
                }
            case 'posten':
                if ($move == 'up') {
                    $nextOrderExist = Posten::higherOrderNumberExists($event_id, $date, $order);
                }
                if ($move == 'down') {
                    $nextOrderExist = Posten::lowererOrderNumberExists($event_id, $date, $order);
                }
            case 'noodEnvelop':
                if ($move == 'up'){
                    $nextOrderExist = NoodEnvelop::model()->higherOrderNumberExists($event_id,
                                                                                    $model_id,
                                                                                    $order);
                }
                if ($move == 'down'){
                    $nextOrderExist = NoodEnvelop::model()->lowererOrderNumberExists($event_id,
                                                                                     $model_id,
                                                                                     $order);
                }
        
            case 'route':
                if ($move == 'up'){
                    $nextOrderExist = Route::higherOrderNumberExists($event_id,
                                                                     $date,
                                                                     $order);
                }
                if ($move == 'down'){
                    $nextOrderExist = Route::lowererOrderNumberExists($event_id,
                                                                               $date,
                                                                               $order);
                }
                if ($nextOrderExist) {
                    return TRUE;
                }
            default:
                return FALSE;
        }
    }
    
    function viewIntroductieAllowed() {
        switch ($this->controller_id) {
            case 'route':
                if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie ){
                    return TRUE;
                }
            default:
                return FALSE;
        }
    }
    
    function defaultAllowed() {
        if (!isset($this->event_id)) {
            return FALSE;
        }
        
        switch ($this->controller_id) {
            case 'users':
            case 'friendList':      
                if (in_array($this->action_id, ['decline', 'accept'])) {
                    return TRUE;
                }
            case 'organisatie':
                if ($this->rolPlayer <= DeelnemersEvent::ROL_deelnemer &&
                    $this->action_id == 'gameOverview') {
                    return TRUE;
                }
                if ($this->action_id == 'groupOverview') {
                    if ($this->rolPlayer <= DeelnemersEvent::ROL_post) {
                        return TRUE;
                    }
                    if ($this->rolPlayer == DeelnemersEvent::ROL_deelnemer &&
                        $group_id == $group_id_of_player &&
                        ($this->hikeStatus == EventNames::STATUS_gestart ||
                        $this->hikeStatus == EventNames::STATUS_introductie)) {
                        return TRUE;
                    }
                    if ($this->rolPlayer == DeelnemersEvent::ROL_deelnemer &&
                        $this->hikeStatus == EventNames::STATUS_beindigd) {
                        return TRUE;
                    }
                }
                if (isset($this->rolPlayer) && $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    if ($this->action_id == 'overview') {
                        return TRUE;
                    }
                    if ($this->controller_id === 'eventNames' && $this->action_id == 'changeStatus') {
                        if (($this->hikeStatus == EventNames::STATUS_opstart or
                            $this->hikeStatus == EventNames::STATUS_introductie or
                            $this->hikeStatus == EventNames::STATUS_gestart) and
                            $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                            return TRUE;
                        }
                    }
                    if ($this->controller_id === 'eventNames' && $this->action_id == 'changeDay') {
                        if ($this->hikeStatus == EventNames::STATUS_gestart and
                            $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                            return TRUE;
                        }
                    }
                }
            default:
        }
        if ($this->controller_id === 'openVragenAntwoorden') {
            switch ($this->action_id) {
                case 'antwoordGoedOfFout':
                    if (($this->hikeStatus == EventNames::STATUS_introductie OR
                        $this->hikeStatus == EventNames::STATUS_gestart) AND
                        $this->rolPlayer == DeelnemersEvent::ROL_organisatie AND ! OpenVragenAntwoorden::isAntwoordGecontroleerd($model_id)) {
                        return TRUE;
                    }
                    break;
                case 'viewControle':
                    if (($this->hikeStatus == EventNames::STATUS_introductie OR
                        $this->hikeStatus == EventNames::STATUS_gestart) AND
                        $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        return TRUE;
                    }
                    break;
                case 'updateOrganisatie':
                    if (($this->hikeStatus == EventNames::STATUS_introductie OR
                        $this->hikeStatus == EventNames::STATUS_gestart) AND
                        $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        return TRUE;
                    }
                    break;
            }
        }

        if ($this->controller_id === 'postPassage') {

            if ($this->action_id == 'create' and
                $this->hikeStatus == EventNames::STATUS_gestart and
                $this->rolPlayer <= DeelnemersEvent::ROL_post and
                PostPassage::isTimeLeftToday($event_id, $group_id) and
                Posten::existPostForActiveDay($event_id) and ! PostPassage::isFirstPostOfDayForGroup($event_id, $group_id) and
                PostPassage::notAllPostsOfDayPassedByGroup($event_id, $group_id)) {
                return TRUE;
            }

            if ($this->action_id == 'createDayStart' and
                $this->hikeStatus == EventNames::STATUS_gestart and
                $this->rolPlayer == DeelnemersEvent::ROL_organisatie and
                Posten::model()->existPostForActiveDay($event_id) and
                PostPassage::isFirstPostOfDayForGroup($event_id, $group_id)) {
                return TRUE;
            }

            if ($this->action_id == 'updateVertrek' and
                $this->hikeStatus == EventNames::STATUS_gestart and
                $this->rolPlayer <= DeelnemersEvent::ROL_post and
                PostPassage::model()->isTimeLeftToday($event_id, $group_id) and
                Posten::model()->existPostForActiveDay($event_id) and
                PostPassage::model()->notAllPostsOfDayPassedByGroup($event_id, $group_id) and ! PostPassage::model()->isFirstPostOfDayForGroup($event_id, $group_id)) {
                return TRUE;
            }
        }
        
        
        
        if ($this->controller_id === 'qr' 
            && $this->action_id == 'report' 
            && $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                $actionAllowed = true;
        }
        return FALSE;
    }
}
