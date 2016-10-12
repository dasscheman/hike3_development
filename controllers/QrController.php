<?php

namespace app\controllers;

use Yii;
use app\models\TblQr;
use app\models\TblQrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * QrController implements the CRUD actions for TblQr model.
 */
class QrController extends Controller
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
                'only' => ['index', 'update', 'delete', 'create', 'report', 'createIntroductie','moveUpDown'],
                'rules' => [			
                    array(
                        'allow' => FALSE,
                        'users'=>array('?'),),
                    array(	
                        'allow' => TRUE,
                        'actions'=>array('index', 'update', 'delete', 'create', 'report', 'createIntroductie', 'moveUpDown'),
                        'matchCallback'=> Yii::$app->user->identity->isActionAllowed()),
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all TblQr models.
     * @return mixed
     */
    public function actionIndex()
    {
		$event_id = $_GET['event_id'];
		$where = "event_ID = $event_id";
        
        $searchModel = new QrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

//        $dataProvider=new CActiveDataProvider('Qr',
//		    array(
//			'criteria'=>array(
//			    'condition'=>$where,
//			    'order'=>'route_ID ASC, qr_volgorde ASC',
//			    ),
//			'pagination'=>array(
//				'pageSize'=>15,
//			),
//		));
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

		
    /**
     * Displays a single TblQr model.
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
     * Creates a new TblQr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Qr;

        if(isset($_POST['Qr']))
		{

			$model->qr_code = Qr::getUniqueQrCode();
			$model->event_ID = $_GET['event_id'];
			$model->route_ID = $_GET['route_id'];
			$model->qr_volgorde = Qr::getNewOrderForQr($_GET['event_id'], $_GET['route_id']);
			$model->attributes=$_POST['Qr'];
			if($model->save())
				return $this->redirect(array(
					'/route/view',
					'event_id'=>$model->event_ID,
					'route_id'=>$model->route_ID));
		}
        
		return $this->render('create',
					  array('model'=>$model,
		));
    }

    /**
     * Updates an existing TblQr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $qr_id = $_GET['qr_id'];
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if( Route::model()->routeIdIntroduction($model->route_ID) ){
                return $this->redirect(array(
                    '/route/viewIntroductie',
                    'route_id'=>$model->route_ID,
                    'event_id'=>$model->event_ID));
            } else {
                return $this->redirect(array(
                    '/route/view',
                    'route_id'=>$model->route_ID,
                    'event_id'=>$model->event_ID));
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblQr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $qr_id = $_GET['qr_id'];
		try
		{
			$this->findModel($id)->delete();
		}
		catch(CDbException $e)
		{
			throw new CHttpException(400,"Je kan deze stille post niet verwijderen.");
		}

		if (Route::routeIdIntroduction($_GET['route_id'])){
			return $this->redirect(isset($_POST['returnUrl']) ?
					$_POST['returnUrl'] : array('/route/viewIntroductie',
									'event_id'=>$_GET['event_id'],
									'route_id'=>$_GET['route_id']));
		} else {
			return $this->redirect(isset($_POST['returnUrl']) ?
					$_POST['returnUrl'] : array('/route/view',
									'event_id'=>$_GET['event_id'],
									'route_id'=>$_GET['route_id']));
		}
    }
    
    public function actioncreateIntroductie()
	{
		$model = new Qr;
		if(isset($_GET['event_id']))
		{
			$model->qr_name = "Introductie";
			$model->qr_code = Qr::getUniqueQrCode();
			$model->event_ID = $_GET['event_id'];
			$model->route_ID = Route::getIntroductieRouteId($_GET['event_id']);
			$model->qr_volgorde = Qr::getNewOrderForIntroductieQr($_GET['event_id']);
			$model->score = 5;

			if($model->save());
			{
				return $this->redirect(array('route/viewIntroductie','event_id'=>$_GET['event_id']));
			}
		}
	}

	
	public function actionReport()
	{
		$id = $_GET['id'];
		$model=Qr::findByPk($id);
		if ($model->qr_code == $_GET['qr_code'] and
			$model->event_ID == $_GET['event_id']) {
			$this->renderPartial("reportview", $id);
		}
		throw new CHttpException(404,'Ongeldige QR code, daarom kun je deze QR niet printen.');
	}

	public function actionMoveUpDown()
    {
		$event_id = $_GET['event_id'];
		$qr_id = $_GET['qr_id'];
		$qr_volgorde = $_GET['volgorde'];
		$up_down = $_GET['up_down'];
		$route_id = Qr::getQrRouteID($qr_id);

		$currentModel = Qr::findByPk($qr_id);

		$criteria = new CDbCriteria;

		if ($up_down=='up')
		{
			$criteria->condition = 'event_ID =:event_id AND
									qr_ID !=:id AND
									route_ID=:route_id AND
									qr_volgorde <=:order';
			$criteria->params=array(':event_id' => $event_id,
									':id' => $qr_id,
									':route_id' => $route_id ,
									':order' => $qr_volgorde);
			$criteria->order= 'qr_volgorde DESC';
		}
		if ($up_down=='down')
		{
			$criteria->condition = 'event_ID =:event_id AND
									qr_ID !=:id AND
								 	route_ID=:route_id AND
									qr_volgorde >:order';
			$criteria->params=array(':event_id' => $event_id,
									':id' => $qr_id,
									':route_id' => $route_id ,
									':order' => $qr_volgorde);
			$criteria->order= 'qr_volgorde ASC';
		}
			$criteria->limit=1;
		$previousModel = Qr::find($criteria);

		$tempCurrentVolgorde = $currentModel->qr_volgorde;
		$currentModel->qr_volgorde = $previousModel->qr_volgorde;
		$previousModel->qr_volgorde = $tempCurrentVolgorde;

		$currentModel->save();
		$previousModel->save();

		if (Route::routeIdIntroduction($currentModel->route_ID))
		{
			return $this->redirect(array('route/viewIntroductie',
				"route_id"=>$currentModel->route_ID,
				"event_id"=>$currentModel->event_ID,));
		}
		else
		{
			return $this->redirect(array('route/view',
				"route_id"=>$currentModel->route_ID,
				"event_id"=>$currentModel->event_ID,));
		}
	}

    /**
     * Finds the Qr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Qr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Qr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
