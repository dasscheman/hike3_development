<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\Groups;
use yii\data\ActiveDataProvider;

/**
 * BonuspuntenController implements the CRUD actions for TblBonuspunten model.
 */
class OrganisatieController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                // We will override the default rule config with the new AccessRule class
//                'ruleConfig' => [
//                    'class' => AccessRule::className(),
//                ],
                'only' => ['index', 'overview'],
                'rules' => [
//                    [
//                        'actions' => ['index'],
//                        'allow' => true,
//                        // Allow users, moderators and admins to create
//                        'roles' => ['@'],
//                    ],
                    [
                        'actions' => ['overview'],
                        'allow' => TRUE, /*EventNames::isActionAllowed(
                            Yii::$app->controller->id,
                            Yii::$app->controller->action->id,
                            Yii::$app->user->identity->selected_event_ID),*/
                    ],
                    
                ],
            ],
        ];
    }
    
    /**
     * Displays the overview of a hike event
     * @return mixed
     */
    public function actionOverview()
    {
        $event_id = Yii::$app->user->identity->selected_event_ID;

        $queryOrganisatie = DeelnemersEvent::find()
            ->where(['=', 'event_ID', $event_id])
            ->andWhere(['<=', 'rol', DeelnemersEvent::ROL_post])
            ->orderby('rol ASC');
        
        $providerOrganisatie = new ActiveDataProvider([
            'query' => $queryOrganisatie,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
                
        $queryGroups = Groups::find()
            ->where(['=', 'event_ID', $event_id])
            ->orderby('group_name ASC');
        $providerGroups = new ActiveDataProvider([
            'query' => $queryGroups,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
		return $this->render('/organisatie/overview', array(
            'eventModel' => EventNames::find($event_id)->one(),
			'organisatieData' => $providerOrganisatie,
			'groupsData' => $providerGroups,
		));
	}
}