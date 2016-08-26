<?php

namespace app\controllers;

use Yii;
use app\models\TblGroups;
use app\models\TblGroupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * GroupsController implements the CRUD actions for TblGroups model.
 */
class GroupsController extends Controller
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
                'only' => ['index', 'update', 'delete', 'view', 'create'],
                'rules' => [			
                    array(
                        'deny',  // deny all guest users
                        'users'=>array('?'),
                    ),			
                    array(	
                        'allow', // allow admin user to perform 'viewplayers' actions
                        'actions'=>array('index', 'update', 'delete', 'view', 'create'),
                        'expression'=> Groups::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id,
                            $_GET["event_id"]),
                    ),
                    array('deny',  // deny all users
                        'users'=>array('*'),
                    ),
                ],
            ]
        ];
    }

    /**
     * Lists all TblGroups models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
     
    /**
     * Displays a single TblGroups model.
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
     * Creates a new TblGroups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Groups();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/startup/startupOverview', 'event_id' => $model->event_ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates an existing TblGroups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/startup/startupOverview', 'event_id' => $model->event_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
		
    /**
     * Deletes an existing TblGroups model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $event_id)
    {		
        try
		{
            $this->findModel($id)->delete();
        }
		catch(CDbException $e)
		{		
			throw new CHttpException(400,"Je kan deze groep niet verwijderen.");
		}

        return $this->redirect(isset($_POST['returnUrl']) ?
					$_POST['returnUrl'] : array('/startup/startupOverview',
								    'event_id'=>$event_id));
    }

	/**
	 * Lists all models.
	 */
	public function actionOverview()
	{
        //TODO dit moet anders, maar even kijken of deze functie nog gebruikt wordt.
//		$dataProvider=new CActiveDataProvider('Groups');
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//		));
	}
    
    /**
     * Finds the TblGroups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblGroups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblGroups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
