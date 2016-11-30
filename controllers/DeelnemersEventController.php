<?php

namespace app\controllers;

use Yii;
use app\models\DeelnemersEvent;
use app\models\DeelnemersEventSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DeelnemersEventController implements the CRUD actions for DeelnemersEvent model.
 */
class DeelnemersEventController extends Controller
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
                'only' => ['dynamicrol', 'index','view', 'update', 'delete', 'viewPlayers', 'create'],
                'rules' => [
                    [
                        'allow' => FALSE,  // deny all guest users
                        'roles'=>array('?'),
                    ],
                    array(
                        'allow' => TRUE,
                        'actions'=>array('dynamicrol'),
                        'roles'=>array('@'),
                    ),			
                    array(	
                        'allow' => TRUE,
                        'actions'=>array('index', 'view', 'update', 'delete', 'viewPlayers', 'create'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        }
                    ),
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all DeelnemersEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeelnemersEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeelnemersEvent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Creates a new DeelnemersEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeelnemersEvent();

        if ($model->load(Yii::$app->request->post())) {
            $model->attributes=$_POST['DeelnemersEvent'];
			$message = new YiiMailMessage(); 
			$message->view = "sendInschrijving"; 
			$params = array('mailEventId'=>$model->event_ID,
							'mailUsersId'=>$model->user_ID,
							'mailRol'=>$model->rol,
							'mailGroupId'=>$model->group_ID,
							'mailOrganisatie'=>$model->create_user_ID);  
			
			$message->subject = 'Inschrijving Hike';
			$message->from = 'noreply@biologenkantoor.nl';
			$message->setBody($params, 'text/html');

            if($model->save()) {
                return $this->redirect(['/startup/startupOverview', 'event_id' => $model->event_ID]);
            }
        } else {
            return $this->render('_formAdd', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates an existing DeelnemersEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->rol <> DeelnemersEvent::ROL_deelnemer) {
				$model->group_ID = "";
			}
            
            if($model->save()) {
                return $this->redirect(['/startup/startupOverview', 'event_id' => $model->event_ID]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }    
    
    /**
     * Deletes an existing DeelnemersEvent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try
        {
            $this->findModel($id)->delete();
        }
		catch(CDbException $e)
		{		
			throw new CHttpException(400,"Je kan deze deelnemer niet verwijderen.");
		}
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
            '/startup/startupOverview',
            'event_id'=>$_GET['event_id']));
    }
    
    /*
	 * Deze actie wordt gebruikt voor de form velden. Op basis van een hike
	 * en een dag wordt bepaald welke posten er beschikbaar zijn. 
	 */
	public function actionDynamicRol()
	{
		if(Yii::$app->request->post('rol')==DeelnemersEvent::ROL_deelnemer)
		{
			$data = Groups::getGroupOptionsForEvent(Yii::$app->request->post('event_id'));
			foreach($data as $value=>$name)
			{
				echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
			}
		}
	}

    /**
     * Finds the DeelnemersEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeelnemersEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeelnemersEvent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}