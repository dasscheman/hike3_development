<?php

namespace app\models;

use Yii;
use yii\web\Cookie;

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
        if (Yii::$app->user->identity !== NULL) {
            return;
        }
        $this->setSelected($this->getSelectedCookie());
    }

        /**
     *
     */
    public function setSelectedCookie($value) {

        $cookies = Yii::$app->getResponse()->getCookies();

        $cookies->remove('selected_event_ID'    );

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
        AccessControl::setControllerId($controller_id);
        AccessControl::setActionId($action_id);
        AccessControl::setIds($ids);
        AccessControl::setParameters($parameters);
        AccessControl::setEventId();
        AccessControl::setHikeStatus();
        AccessControl::setRolPlayer();
        AccessControl::setGroupOfPlayer();

        switch ($this->action_id) {
            case 'create':
                return AccessControl::createAllowed();
            case 'createIntroductie':
                return AccessControl::createIntroductieAllowed();
            case 'delete':
                return AccessControl::deleteAllowed();
            case 'index':
                return AccessControl::indexAllowed();
            case 'moveUpDown':
                return AccessControl::moveUpDownAllowed();
            case 'update':
            case 'updateImage':
                return AccessControl::updateAllowed();
            case 'overview':
                return AccessControl::overviewAllowed();
            case 'view':
                return AccessControl::viewAllowed();
            case 'ViewIntroductie':
                return AccessControl::viewIntroductieAllowed();
            case 'viewPlayers':
                return AccessControl::viewPlayersAllowed();
            default:
                return AccessControl::defaultAllowed();
        }
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
        $this->rolPlayer = DeelnemersEvent::getRolOfPlayer();
    }
    
    function setGroupOfPlayer(){
        if ($this->rolPlayer === DeelnemersEvent::ROL_deelnemer &&
            isset($this->event_id)) {
            $this->groupOfPlayer = DeelnemersEvent::getGroupOfPlayer($this->event_id, Yii::app()->user->id);
        }
    }
    
    function indexAllowed() {
        if ($this->controller_id === 'users' &&
            Yii::$app->user->identity->id == Yii::$app->request->get('id')) {
            return TRUE;
        }
        
        if (!isset($this->event_id)) {
            return FALSE;
        }
    
        switch ($this->controller_id) {
            case 'nood-envelop':
            case 'open-vragen':
            case 'posten':
            case 'qr':
            case 'route':
            case 'groups':
            case 'deelnemers-event':
            case 'event-names':
                if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'open-nood-envelop':
            case 'post-passage':
                if ($this->hikeStatus > EventNames::STATUS_introductie AND
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'qr-check':
            case 'bonuspunten':
            case 'open-vragen-antwoorden':
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
            Yii::$app->user->identity->id == Yii::$app->request->get('id')) {
            return TRUE;
        }

        if (!isset($this->event_id)) {
            return FALSE;
        }

        switch ($this->controller_id) {
            case 'open-vragen-antwoorden':
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
            case 'qr-check':
                if (($this->hikeStatus == EventNames::STATUS_introductie or
                    $this->hikeStatus == EventNames::STATUS_gestart) and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'open-nood-envelop':
            case 'post-passage':
                if ($this->hikeStatus == EventNames::STATUS_gestart and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'nood-envelop':
            case 'open-vragen':
            case 'posten':
            case 'qr':
            case 'route':
                if ($this->hikeStatus == EventNames::STATUS_opstart and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'groups':
            case 'deelnemers-event':
            case 'groups':
                if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'event-names':
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
            case 'nood-envelop':
            case 'open-vragen':
            case 'posten':
            case 'qr':
            case 'groups':
                if (($this->hikeStatus == EventNames::STATUS_opstart or
                    $this->hikeStatus == EventNames::STATUS_introductie) and
                    $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
                break;
            case 'deelnemers-event':
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
            case 'event-names':
            case 'users':
                return TRUE;
                break;
            case 'qr-check':
            case 'open-vragen-antwoorden':
                if ($this->hikeStatus == EventNames::STATUS_introductie and
                    $this->rolPlayer == DeelnemersEvent::ROL_deelnemer) {
                    return TRUE;
                }

                if ($this->hikeStatus == EventNames::STATUS_gestart and
                    $this->rolPlayer == DeelnemersEvent::ROL_deelnemer and ( PostPassage::model()->isTimeLeftToday($event_id, $this->groupOfPlayer))) {
                    return TRUE;
                }
            // Hier geen break. OpenNoodenvelop en post-passage moeten uitgesloten worden voor de introductie.
            case 'open-nood-envelop':
            case 'post-passage':
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
            case 'nood-envelop':
            case 'open-vragen':
            case 'posten':
            case 'qr':
            case 'chart':
            case 'groups':
            case 'deelnemers-event':
            case 'event-names':
            case 'groups':
            case 'open-nood-envelop':
            case 'open-vragen-antwoorden':
            case 'post-passage':
            case 'qr-check':
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
    function overviewAllowed() {
        switch ($this->controller_id) {
//            case 'users':
//                if ($this->controller_id === 'users' &&
//                    Yii::$app->user->identity->id == Yii::$app->request->get('id')) {
//                    return TRUE;
//                }
//                return FALSE;
//            case 'nood-envelop':
//            case 'open-vragen':
//            case 'posten':
//            case 'qr':
//            case 'route':
//            case 'chart':
//            case 'groups':
//            case 'deelnemers-event':
//            case 'event-names':
//            case 'groups':
//            case 'open-nood-envelop':
//            case 'open-vragen-antwoorden':
//            case 'post-passage':
//            case 'qr-check':
              case 'organisatie':
                if (!isset($this->event_id)) {
                    return FALSE;
                }

                if ($this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    return TRUE;
                }
            default:
                return FALSE;
        }
    }

    function viewAllowed() {
        switch ($this->controller_id) {
            case 'users':
                if ($this->controller_id === 'users' &&
                    Yii::$app->user->identity->id == Yii::$app->request->get('id')) {
                    return TRUE;
                }
                return FALSE;
            case 'nood-envelop':
            case 'open-vragen':
            case 'posten':
            case 'qr':
            case 'route':
            case 'chart':
            case 'groups':
            case 'deelnemers-event':
            case 'event-names':
            case 'groups':
            case 'open-nood-envelop':
            case 'open-vragen-antwoorden':
            case 'post-passage':
            case 'qr-check':
                if (!isset($this->event_id)) {
                    return FALSE;
                }
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
            case 'qr-check':
            case 'open-vragen':
            case 'open-vragen-antwoorden':
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
            case 'nood-envelop':
            case 'open-nood-envelop':
            case 'post-passage':
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
            case 'open-vragen':
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
            case 'open-vragen':
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
            case 'nood-envelop':
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
            case 'friend-list':      
                if (in_array($this->action_id, ['decline', 'accept', 'connect', 'search-friends', 'search-new-friends', 'search-friend-requests'])) {
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
                    if ($this->controller_id === 'event-names' && $this->action_id == 'changeStatus') {
                        if (($this->hikeStatus == EventNames::STATUS_opstart or
                            $this->hikeStatus == EventNames::STATUS_introductie or
                            $this->hikeStatus == EventNames::STATUS_gestart) and
                            $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                            return TRUE;
                        }
                    }
                    if ($this->controller_id === 'event-names' && $this->action_id == 'changeDay') {
                        if ($this->hikeStatus == EventNames::STATUS_gestart and
                            $this->rolPlayer == DeelnemersEvent::ROL_organisatie) {
                            return TRUE;
                        }
                    }
                }
            default:
        }
        if ($this->controller_id === 'open-vragen-antwoorden') {
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

        if ($this->controller_id === 'post-passage') {

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
