<?php

namespace app\controllers;

use Yii;
use app\models\Route;
use app\models\RouteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Users;
/**
 * RouteController implements the CRUD actions for TblRoute model.
 */
class RouteController extends Controller
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
                'only' => ['index', 'update', 'delete', 'create', 'viewIntroductie', 'moveUpDown', 'view'],
                'rules' => [
                    array(
                        'deny', // deny all users
                        'users'=>array('?'),
                    ),
                    array(
                        'allow', // allow authenticated user to perform 'create'
                        'actions'=>array('view'),
                        'users'=>array('@'),),
                    array(	
                        'allow', // only when $_GET are set
                        'actions'=>array('moveUpDown'),
                        'expression'=> Yii::$app->user->identity->isActionAllowed()),
                    array(	
                        'allow', // allow admin user to perform 'viewplayers' actions
                        'actions'=>array('index', 'update', 'delete', 'create', 'viewIntroductie'),
                        'expression'=> Yii::$app->user->identity->isActionAllowed()),
                    array(
                        'deny', //deny all users
                        'users'=>array('*'),
                    ),
                ]
            ]
        ];
    }

    /**
     * Lists all TblRoute models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (isset($_GET['date'])) {
            Route::setActiveTab($_GET['date']);
        }

        $event_Id = $_GET['event_id'];
        $startDate=EventNames::getStartDate($event_Id);
        $endDate=EventNames::getEndDate($event_Id);
        
        $searchModel = new RouteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'startDate'=>$startDate,
            'endDate'=>$endDat
        ]);
    }

    /**
     * Displays a single TblRoute model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $route_id = $_GET['route_id'];
        $event_id = $_GET['event_id'];

        $where = "event_ID = $event_id AND route_ID =$route_id";

        $vragenDataProvider=new CActiveDataProvider('OpenVragen',
            array(
            'criteria'=>array(
                'condition'=>$where,
                'order'=>'vraag_volgorde ASC',
             ),
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));

        $envelopDataProvider=new CActiveDataProvider('NoodEnvelop',
            array(
            'criteria'=>array(
                'condition'=>$where,
                'order'=>'nood_envelop_volgorde ASC',
             ),
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));

        $qrDataProvider=new CActiveDataProvider('Qr', array(
            'criteria'=>array(
                'condition'=>$where,
                'order'=>'qr_volgorde ASC',
             ),
            'pagination'=>array(
                'pageSize'=>15,
            ),
        ));
        return $this->render('view', [
            'model' => $this->findModel($id),
            'vragenDataProvider'=>$vragenDataProvider,
            'envelopDataProvider'=>$envelopDataProvider,
            'qrDataProvider'=>$qrDataProvider,
        ]);
    }

    /**
     * Creates a new TblRoute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Route();
        $qrModel=new Qr;

        if(isset($_POST['Route']))
        {
            $model->attributes = $_POST['Route'];
            $model->day_date = $_GET['date'];
            $model->event_ID = $_GET['event_id'];
            $model->route_volgorde = Route::getNewOrderForDateRoute($_GET['event_id'], $_GET['date']);

            // Wanneer er een route onderdeel aangemaakt wordt, dan moet er gecheckt woren of er voor die dag al een
            // begin aangemaakt is.  Als dat niet het geval is dan moet die nog aangemaakt worden.
            if (!Posten::startPostExist($_GET['event_id'], $_GET['date'])) {

                $modelStartPost = new Posten;
                $modelStartPost->event_ID = $_GET['event_id'];
                $modelStartPost->post_name = 'Dag Start';
                $modelStartPost->date = $_GET['date'];
                $modelStartPost->post_volgorde = 1;
                $modelStartPost->score = 0;
            }

            // validate BOTH $model, $modelStartPost.
            $valid=$model->validate();
            if (isset($modelStartPost)) {
                $valid=$modelStartPost->validate() && $valid;
            }

            if($valid)
            {
                $model->save(false);
                if (isset($modelStartPost))
                {
                    $modelStartPost->save(false);
                }
                // QR record can only be set after the routemodel save.
                // Because route_ID is not available before save.
                // Furthermore it is not a problem when route record is saved and
                // an error occured on qr save. Therefore this easy and fast solution is choosen.
                if (!Qr::qrExistForRouteId($_GET['event_id'], $model->route_ID)) {
                    $qrModel->qr_name = $model->route_name;
                    $qrModel->qr_code = Qr::model()->getUniqueQrCode();
                    $qrModel->event_ID = $_GET['event_id'];
                    $qrModel->route_ID = $model->route_ID;
                    $qrModel->qr_volgorde = Qr::getNewOrderForQr($_GET['event_id'], $model->route_ID);
                    $qrModel->score = 5;
                    // use false parameter to disable validation
                    $qrModel->save(false);
                }
                return $this->redirect(array(
                    '/route/index',
                    'event_id'=>$model->event_ID,
                    'date'=>$model->day_date));
            }
        }
    }

    /**
     * Updates an existing TblRoute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

		if(isset($_POST['Route']))
		{
			$model->attributes=$_POST['Route'];
			if($model->save())
			{
				return $this->redirect(array(
                    '/route/index',
                    'event_id'=>$model->event_ID));
			}
		}
        
		return $this->render(
            'update',array(
			'model'=>$model,
		));
    }

    /**
     * Deletes an existing TblRoute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
        }
            catch(CDbException $e)
		{
			throw new CHttpException(400,"Je kan dit routeonderdeel niet verwijderen. Verwijder eerst alle onderdelen van deze route (vragen, stille posten)");
		}
        
        return $this->redirect(isset($_POST['returnUrl']) ?
			$_POST['returnUrl'] : array('/startup/startupOverview',
							'event_id'=>$_GET['event_id']));
    }

	/**
	 * Lists all models.
	 */
	public function actionViewIntroductie()
	{
		$event_Id = $_GET['event_id'];
		$introductieId = Route::getIntroductieRouteId($event_Id);

		if (! isset($introductieId))
			$introductieId = 0;

		$where = "event_ID = $event_Id AND route_ID = $introductieId";
		$openVragenDataProvider=new CActiveDataProvider('OpenVragen',
			array(
			 'criteria'=>array(
				'condition'=>$where,
				'order'=>'vraag_volgorde ASC',
			  ),
			'pagination'=>array(
				'pageSize'=>30,
			),
		));

		$qrDataProvider=new CActiveDataProvider('Qr',
			array(
			 'criteria'=>array(
				'condition'=>$where,
				'order'=>'qr_volgorde ASC',
			  ),
			'pagination'=>array(
				'pageSize'=>30,
			),
		));

		$dataModel=array(
			'vragenData'=>$openVragenDataProvider,
			'qrData'=>$qrDataProvider
		);

		return $this->render('viewIntroductie', $dataModel);
	}

	/*
	 * Deze actie wordt gebruikt voor de grid velden. 
	 */
	public function actionMoveUpDown()
	{
		$event_id = $_GET['event_id'];
		$route_id = $_GET['route_id'];
		$date = $_GET['date'];
		$route_volgorde = $_GET['volgorde'];
		$up_down = $_GET['up_down'];

		$currentModel = Route::findByPk($route_id);

		$criteria = new CDbCriteria;

		if ($up_down=='up')
		{
			$criteria->condition = 'event_ID =:event_id AND day_date =:date AND route_volgorde <:order';
			$criteria->params=array(':event_id' => $event_id, ':date' => $date, ':order' => $route_volgorde);
			$criteria->order= 'route_volgorde DESC';
		}
		if ($up_down=='down')
		{
			$criteria->condition = 'event_ID =:event_id AND day_date =:date AND route_volgorde >:order';
			$criteria->params=array(':event_id' => $event_id, ':date' => $date, ':order' => $route_volgorde);
			$criteria->order= 'route_volgorde ASC';
		}
		$criteria->limit=1;
		$previousModel = Route::findAll($criteria);

		$tempCurrentVolgorde = $currentModel->route_volgorde;
		$currentModel->route_volgorde = $previousModel[0]->route_volgorde;
		$previousModel[0]->route_volgorde = $tempCurrentVolgorde;

		$currentModel->save();
		$previousModel[0]->save();

        return $this->redirect(array(
            '/route/index',
            'event_id'=>$event_id,
            'date'=>$date));
	}
    
    /**
     * Finds the TblRoute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblRoute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblRoute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
