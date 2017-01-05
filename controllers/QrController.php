<?php

namespace app\controllers;

use Yii;
use app\models\TblQr;
use app\models\TblQrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\QrCheck;

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
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        },
                    ),
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ]
                ],
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
        $model = new Qr();
        if ($model->load(Yii::$app->request->post())) {
			$model->event_ID = Yii::$app->user->identity->selected;
			$model->qr_code = Qr::getUniqueQrCode();
			$model->route_ID = Yii::$app->request->get(1)['route_id'];
			$model->qr_volgorde = Qr::getNewOrderForQr($model->route_ID);

			if($model->save()) {

                $event_Id = Yii::$app->user->identity->selected;
                $startDate = EventNames::getStartDate($event_Id);
                $endDate = EventNames::getEndDate($event_Id);

                $searchModel = new RouteSearch();

                return $this->render('/route/index', [
                    'searchModel' => $searchModel,
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ]);
            }
        } else {
            return $this->renderPartial('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblQr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model->load(Yii::$app->request->post())) {
            return $this->renderPartial('update', [
                'model' => $model,
            ]);
        }

        if (Yii::$app->request->post('submit') == 'delete') {
            $exist = QrCheck::find()
               ->where('event_ID=:event_id and qr_ID=:qr_id')
               ->addParams(
                   [
                       ':event_id' => Yii::$app->user->identity->selected,
                       ':qr_id' => $model->qr_ID
                   ])
               ->exists();

            if (!$exist) {
                $model->delete();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete silent station, it contains items which should be removed first.'));
            }
        }
        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save changes to silent station.'));
        }

        $event_Id = Yii::$app->user->identity->selected;
        $startDate = EventNames::getStartDate($event_Id);
        $endDate = EventNames::getEndDate($event_Id);

        $searchModel = new RouteSearch();

        return $this->render('/route/index', [
            'searchModel' => $searchModel,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    /**
     * Deletes an existing TblQr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        dd('NIET MEER NODIG ??');
        $model = $this->findModel($id);

        $exist = QrCheck::find()
           ->where('event_ID=:event_id and qr_ID=:qr_id')
           ->addParams(
               [
                   ':event_id' => Yii::$app->user->identity->selected,
                   ':qr_id' => $model->qr_ID
               ])
           ->exists();

        if (!$exist) {
            $model->delete();
        }

        return $this->render('/route/index', [
            'searchModel' => $searchModel,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
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
