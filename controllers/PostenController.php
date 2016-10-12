<?php

namespace app\controllers;

use Yii;
use app\models\TblPosten;
use app\models\TblPostenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PostenController implements the CRUD actions for TblPosten model.
 */
class PostenController extends Controller
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
                'only' => ['index', 'update', 'delete', 'create', 'view',  'moveUpDown'],
                'rules' => [
                    array(
                        'allow' => FALSE,
                        'roles'=>array('?'),),
                    array(	
                        'allow' => TRUE,
                        'actions'=>array('index', 'update', 'delete', 'create', 'view', 'moveUpDown'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        }
                    ),
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all TblPosten models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (isset($_GET['date'])) {
			Posten::setActiveTab($_GET['date']);
		}
        $event_Id = $_GET['event_id'];
		$startDate=EventNames::model()->getStartDate($event_Id);
		$endDate=EventNames::model()->getEndDate($event_Id);
        
        $searchModel = new PostenSearch();
        $postenData = $searchModel->searchPostDate(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
			'postenData'=>$postenData,
			'startDate'=>$startDate,
			'endDate'=>$endDate
        ]);
    }

    /**
     * Displays a single TblPosten model.
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
     * Creates a new TblPosten model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Posten();

        if ($model->load(Yii::$app->request->post())) {
            $model->event_ID = $_GET['event_id'];
			$model->date = $_GET['date'];
			$model->post_volgorde = Posten::getNewOrderForPosten($_GET['event_id'], $model->date);
			if($model->save()) {
				$this->redirect(
                    array(
                        '/posten/index',
                        'event_id'=>$model->event_ID,
                        'date'=>$model->date
                    )
                );
            }
		}
        return $this->render('create', ['model' => $model,]);
    }
    
    /**
     * Updates an existing TblPosten model.
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
     * Deletes an existing TblPosten model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $post_id = $_GET['post_id'];
		$event_id = $_GET['event_id'];
		try
		{
			$this->findModel($id)->delete();
		}
		catch(CDbException $e)
		{
			throw new CHttpException(400,"Je kan deze post niet verwijderen.");
		}
		
        return $this->redirect(isset($_POST['returnUrl']) ?
            $_POST['returnUrl'] : array('/posten/index', 'event_id'=>$event_id));
    }

    /**
     * Finds the TblPosten model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblPosten the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblPosten::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionMoveUpDown()
    {
		$event_id = $_GET['event_id'];
		$post_id = $_GET['post_id'];
		$date = $_GET['date'];
		$post_volgorde = $_GET['volgorde'];
		$up_down = $_GET['up_down'];

		$currentModel = Posten::findByPk($post_id);

		$criteria = new CDbCriteria;

		if ($up_down=='up')
		{
			$criteria->condition = 'event_ID =:event_id AND date =:date AND post_volgorde <:order';
			$criteria->params=array(':event_id' => $event_id, ':date' => $date, ':order' => $post_volgorde);
			$criteria->order= 'post_volgorde DESC';
		}
		if ($up_down=='down')
		{
			$criteria->condition = 'event_ID =:event_id AND date =:date AND post_volgorde >:order';
			$criteria->params=array(':event_id' => $event_id, ':date' => $date, ':order' => $post_volgorde);
			$criteria->order= 'post_volgorde ASC';
		}
		$criteria->limit=1;
		$previousModel = Posten::findAll($criteria);

		$tempCurrentVolgorde = $currentModel->post_volgorde;
		$currentModel->post_volgorde = $previousModel[0]->post_volgorde;
		$previousModel[0]->post_volgorde = $tempCurrentVolgorde;

		$currentModel->save();
		$previousModel[0]->save();

		$startDate=EventNames::getStartDate($event_id);
		$endDate=EventNames::getEndDate($event_id);

		$this->redirect(array('/posten/index',
						'event_id'=>$event_id,
						'date'=>$date));
   }
}
