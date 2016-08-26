<?php
 
namespace app\models;
 
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use app\models\Groups;


abstract class HikeActiveRecord extends ActiveRecord
{
    public $actionAllowed;
    public $indexAllowed;
    public $updateAllowed;
    public $viewAllowed;
    public $viewPlayersAllowed;
    public $deleteAllowed;
    public $createAllowed;

    /**
    * Attaches the timestamp behavior to update our create and update times
    */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_user_ID',
                'updatedByAttribute' => 'update_user_ID',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                'value' => function() { return date('Y-m-d H:i:s'); },
            ],
        ];
    }

    // access it by Yii::app()->user->isActionAllowed($this->id, $this->action->id, $event_id)
    // $this->id is same as Yii::app()->controller->id)
    // $this->action->id is same as Yii::app()->controller->action->id)
    function isActionAllowed($controller_id = null,
                            $action_id = null,
                            $model_id = null,
                            $group_id = null)
    {
        $actionAllowed = false;

        if ($controller_id == null) {
            $controller_id = Yii::$app->controller->id;
        }

        if ($action_id == null) {
            $action_id = Yii::$app->controller->action->id;
        }

        if (!isset(Yii::$app->user->identity->selected_event_ID)) {
            return false;
        }
        
        $event_id = Yii::$app->user->identity->selected_event_ID;
        
        switch ($action_id)
        {
            case 'index':
                $actionAllowed = HikeActiveRecord::setIndexAllowed($controller_id, $action_id, $event_id);
                break;
            case 'view':
                $actionAllowed = HikeActiveRecord::setViewAllowed($controller_id, $action_id, $event_id, $model_id, $group_id);
                break;
            case 'create':
                $actionAllowed = HikeActiveRecord::setCreateAllowed($controller_id, $action_id, $event_id, $model_id, $group_id);
                break;
            case 'createIntroductie':
                $actionAllowed = HikeActiveRecord::setCreateIntroductieAllowed($controller_id, $action_id, $event_id);
                break;
            case 'update':
            case 'updateImage':
                $actionAllowed = HikeActiveRecord::setUpdateAllowed($controller_id, $action_id, $event_id, $model_id, $group_id);
                break;
            case 'delete':
                $actionAllowed = HikeActiveRecord::setDeleteAllowed($controller_id, $action_id, $event_id, $model_id);
                break;
            case 'viewPlayers':
                $actionAllowed = HikeActiveRecord::setViewPlayersAllowed($controller_id, $action_id, $event_id, $model_id, $group_id);
                break;
            default:
        }
            return $actionAllowed;
    }

    function setIndexAllowed($controller_id = null, $action_id = null, $event_id = null)
    {
        $indexAllowed = false;
        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);

        switch ($controller_id) {
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'route':
            //case chart:
            case 'groups':
            case 'deelnemersEvent':
            case 'eventNames':
            //case friendList:
            case 'groups':
            case 'route':
            case 'startup':
                if ($rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    $indexAllowed = true;
                }
                break;
            case 'openNoodEnvelop':
            case 'postPassage':
                if ($hikeStatus > EventNames::STATUS_introductie AND
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $indexAllowed = true;
                }
                break;
            case 'qrCheck':
            case 'bonuspunten':
            case 'openVragenAntwoorden':
                if ($hikeStatus <> EventNames::STATUS_opstart AND
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $indexAllowed = true;
                }
            //case users:
            default:
        }
        return $indexAllowed;
    }

    function setUpdateAllowed($controller_id = null, $action_id = null, $event_id = null, $model_id = null, $group_id = null)
    {
        $updateAllowed = false;
        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);
        if ($rolPlayer == DeelnemersEvent::ROL_deelnemer) {
            $groupOfPlayer = DeelnemersEvent::getGroupOfPlayer($event_id, Yii::app()->user->id);
        }

        switch ($controller_id) {
            case 'openVragenAntwoorden':
                if ($hikeStatus == EventNames::STATUS_introductie and
                    $rolPlayer == DeelnemersEvent::ROL_deelnemer and
                    $groupOfPlayer == $group_id) {
                        $updateAllowed = true;
                }
                if ($hikeStatus == EventNames::STATUS_gestart and
                    $rolPlayer == DeelnemersEvent::ROL_deelnemer and
                    $groupOfPlayer == $group_id and
                    PostPassage::model()->isTimeLeftToday($event_id, $group_id)) {
                        $updateAllowed = true;
                }
            case 'bonuspunten':
            case 'qrCheck':
                if (($hikeStatus == EventNames::STATUS_introductie or
                    $hikeStatus == EventNames::STATUS_gestart) and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $updateAllowed = true;
                }
                break;
            case 'openNoodEnvelop':
            case 'postPassage':
                if ($hikeStatus == EventNames::STATUS_gestart and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $updateAllowed = true;}
                break;
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'route':
                if ($hikeStatus == EventNames::STATUS_opstart and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie ){
                        $updateAllowed = true;
                }
                break;
            // case 'chart':
            case 'groups':
            case 'deelnemersEvent':
            case 'groups':
                if ($rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    $updateAllowed = true;
                }
                break;
            case 'eventNames':
                if ($hikeStatus == EventNames::STATUS_opstart and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie)	{
                        $updateAllowed = true;
                }
                break;
            case 'users':
                break;
            default:
        }
        return $updateAllowed;
    }

    function setCreateAllowed($controller_id = null, $action_id = null, $event_id = null, $model_id = null, $group_id = null)
    {
        $createAllowed = false;
        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);
        if ($rolPlayer == DeelnemersEvent::ROL_deelnemer) {
            $groupOfPlayer = DeelnemersEvent::getGroupOfPlayer($event_id, Yii::app()->user->id);
        }

        switch ($controller_id) {
            case 'bonuspunten':
                if ($hikeStatus >= EventNames::STATUS_introductie and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $createAllowed = true;}
                break;
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'groups':
                if (($hikeStatus == EventNames::STATUS_opstart or
                    $hikeStatus == EventNames::STATUS_introductie) and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $createAllowed = true;
                }
                break;
            case 'deelnemersEvent':
                if (($hikeStatus == EventNames::STATUS_opstart or
                    $hikeStatus == EventNames::STATUS_introductie) and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $createAllowed = true;
                }
                break;
            case 'route':
                    if( $hikeStatus == EventNames::STATUS_opstart and
                        $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                            $createAllowed = true;
                    }
                    break;
            case 'eventNames':
            case 'users':
                    $createAllowed = true;
                    break;
            //case 'chart':
            //case friendList:
            case 'qrCheck':
            case 'openVragenAntwoorden':
                    if ($hikeStatus == EventNames::STATUS_introductie and
                        $rolPlayer == DeelnemersEvent::ROL_deelnemer) {
                            $createAllowed = true;}

                    if ($hikeStatus == EventNames::STATUS_gestart and
                        $rolPlayer == DeelnemersEvent::ROL_deelnemer and
                        (PostPassage::model()->isTimeLeftToday($event_id, $groupOfPlayer))) {
                            $createAllowed = true;}
                    // Hier geen break. OpenNoodenvelop en postPassage moeten uitgesloten worden voor de introductie.
            case 'openNoodEnvelop':
            case 'postPassage':
                    if ($hikeStatus == EventNames::STATUS_gestart and
                        $rolPlayer == DeelnemersEvent::ROL_deelnemer and
                        $groupOfPlayer == $group_id and
                        PostPassage::model()->istimeLeftToday($event_id, $group_id)) {
                            $createAllowed = true;}
                    break;
            default:
        }
        return $createAllowed;
    }

    function setDeleteAllowed($controller_id = null, $action_id = null, $event_id = null, $model_id = null)
    {
        $deleteAllowed = false;
        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);

        switch ($controller_id) {
            case 'bonuspunten':
                if (($hikeStatus == EventNames::STATUS_introductie or
                    $hikeStatus == EventNames::STATUS_gestart) and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $deleteAllowed = true;}
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'chart':
            case 'groups':
            case 'deelnemersEvent':
            case 'eventNames':
            //case friendList:
            case 'groups':
            case 'openNoodEnvelop':
            case 'openVragenAntwoorden':
            case 'postPassage':
            case 'qrCheck':
            case 'route':
                if ($hikeStatus == EventNames::STATUS_opstart and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie ){
                        $deleteAllowed = true;
                    }
                break;
            case 'users':
                    break;
            default:
        }
        return $deleteAllowed;
    }

    function setViewAllowed($controller_id = null, $action_id = null, $event_id = null, $model_id = null, $group_id = null)
    {
        $viewAllowed = false;
        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);
        if ($rolPlayer == DeelnemersEvent::ROL_deelnemer) {
            $groupOfPlayer = DeelnemersEvent::getGroupOfPlayer($event_id, Yii::app()->user->id);}

        switch ($controller_id) {
            case 'noodEnvelop':
            case 'openVragen':
            case 'posten':
            case 'qr':
            case 'route':
            case 'chart':
            case 'groups':
            case 'deelnemersEvent':
            case 'eventNames':
            //case friendList:
            case 'groups':
            case 'openNoodEnvelop':
            case 'openVragenAntwoorden':
            case 'postPassage':
            case 'qrCheck':
                if ($rolPlayer == DeelnemersEvent::ROL_organisatie) {
                    $viewAllowed = true;}
                if (($hikeStatus == EventNames::STATUS_introductie or
                    $hikeStatus == EventNames::STATUS_gestart) and
                    $rolPlayer == DeelnemersEvent::ROL_post) {
                        $viewAllowed = true;}
                if ($hikeStatus == EventNames::STATUS_beindigd ) {
                        $viewAllowed = true;}
            case 'users':
                break;
            default:
        }
        return $viewAllowed;
    }

    function setViewPlayersAllowed($controller_id = null, $action_id = null, $event_id = null, $model_id = null, $group_id = null)
    {
        $viewPlayersAllowed = false;
        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);
        if ($rolPlayer == DeelnemersEvent::ROL_deelnemer) {
            $groupOfPlayer = DeelnemersEvent::getGroupOfPlayer($event_id, Yii::app()->user->id);}

        switch ($controller_id) {
            case 'bonuspunten':
            case 'qrCheck':
            case 'openVragen':
            case 'openVragenAntwoorden':
                if (($hikeStatus == EventNames::STATUS_introductie or
                    $hikeStatus == EventNames::STATUS_gestart) and
                    $rolPlayer == DeelnemersEvent::ROL_deelnemer and
                    $groupOfPlayer == $group_id) {
                        $viewPlayersAllowed = true;
                }
                if (($hikeStatus == EventNames::STATUS_introductie or
                    $hikeStatus == EventNames::STATUS_gestart) and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $viewPlayersAllowed = true;
                }

                if (($hikeStatus == EventNames::STATUS_introductie or
                    $hikeStatus == EventNames::STATUS_gestart) and
                    $rolPlayer == DeelnemersEvent::ROL_post) {
                        $viewPlayersAllowed = true;
                }
                if ($hikeStatus == EventNames::STATUS_beindigd) {
                    $viewPlayersAllowed = true;
                }
                break;
            case 'noodEnvelop':
            case 'openNoodEnvelop':
            case 'postPassage':
                if ($hikeStatus == EventNames::STATUS_gestart AND
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $viewPlayersAllowed = true;
                }
                if ($hikeStatus == EventNames::STATUS_gestart and
                    $rolPlayer == DeelnemersEvent::ROL_post) {
                        $viewPlayersAllowed = true;
                }
                if ($hikeStatus == EventNames::STATUS_beindigd) {
                    $viewPlayersAllowed = true;
                }

                if ($hikeStatus == EventNames::STATUS_gestart and
                    $rolPlayer == DeelnemersEvent::ROL_deelnemer and
                    $groupOfPlayer == $group_id) {
                        $viewPlayersAllowed = true;
                }
                break;
            default:
        }
        return $viewPlayersAllowed;
    }

    function setCreateIntroductieAllowed($controller_id = null, $action_id = null, $event_id = null)
    {
        $createInroductieAllowed = false;
        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);

        switch ($controller_id) {
            case 'openVragen':
            case 'qr':
                if ($hikeStatus == EventNames::STATUS_opstart and
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $createInroductieAllowed = true;
                }
                break;
        }
        return $createInroductieAllowed;
    }
}
?>