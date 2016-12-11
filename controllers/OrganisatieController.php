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
                'only' => ['overview'],
                'rules' => [
                    [
                        'allow' => FALSE,
                        'roles'=>['?'],
                    ],
                    [
                        'actions' => ['overview'],
                        'allow' => TRUE,
                        'matchCallback' =>  function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        }
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
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
        $event_id = Yii::$app->user->identity->selected;

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
        $groupModel = new Groups;        
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
            'groupModel' => $groupModel,
		));
	}
}