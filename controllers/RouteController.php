<?php

namespace app\controllers;

use Yii;
use app\models\Route;
use app\models\RouteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Qr;
use app\models\EventNames;
use app\models\Bonuspunten;
use app\models\NoodEnvelop;
use app\models\OpenVragen;

use dosamigos\qrcode\QrCode;
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
                        'allow' => FALSE,
                        'roles' => array('?'),
                    ),
                    array(
                        'allow' => TRUE,
                        'actions'=>array('index', 'update', 'delete', 'create', 'viewIntroductie', 'moveUpDown', 'view'),
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
     * Lists all Route models.
     * @return mixed
     */
    public function actionIndex()
    {
        $event_Id = Yii::$app->user->identity->selected;
        $startDate=EventNames::getStartDate($event_Id);
        $endDate=EventNames::getEndDate($event_Id);

        $searchModel = new RouteSearch();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'startDate' => $startDate,
            'endDate' => $endDate
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
        $model = new Route;

        if (Yii::$app->request->post('Route') && $model->load(Yii::$app->request->post())) {
            $model->setAttributes([
                'event_ID' => Yii::$app->user->identity->selected,
                'day_date' => Yii::$app->request->get('date')
            ]);
            var_dump(Yii::$app->request->get('date'));
            $model->setRouteOrder();

            // Wanneer er een route onderdeel aangemaakt wordt, dan moet er
            // gecheckt woren of er voor die dag al een begin aangemaakt is.
            // Als dat niet het geval is dan moet die nog aangemaakt worden.
            if (!Posten::startPostExist($model->event_ID, $model->day_date)) {

                $modelStartPost = new Posten;
                $modelStartPost->setAttributes([
                    'event_ID' => $model->event_ID,
                    'post_name' => Yii::t('app', 'Start day'),
                    'date' => $model->day_date,
                    'post_volgorde'=> 1,
                    'score' => 0,
                ]);
            }

            // validate BOTH $model, $modelStartPost.
            $valid=$model->validate();
            if (isset($modelStartPost)) {
                $valid=$modelStartPost->validate() && $valid;
            }

            if($valid && $model->save(false)) {
                if(isset($modelStartPost)) {
                    $modelStartPost->save(false);
                }

                // QR record can only be set after the routemodel save.
                // Because route_ID is not available before save.
                // Furthermore it is not a problem when route record is saved and
                // an error occured on qr save. Therefore this easy and fast solution is choosen.
                if (!Qr::qrExistForRouteId($model->evnt_ID, $model->route_ID)) {
                    $qrModel=new Qr;
                    $qrModel->setAttributes([
                        'qr_name' => $model->route_name,
                        'qr_code' => Qr::getUniqueQrCode(),
                        'event_ID' => $model->evnt_ID,
                        'route_ID' => $model->route_ID,
                        'score' => 5,
                    ]);

                    $qrModel->setNewOrderForQr();

                    // use false parameter to disable validation
                    $qrModel->save(false);
                }
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('/route/index', ['model' => $model]);
                }
                return $this->render([
                    '/route/index',
                    'event_id'=>$model->event_ID
                ]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render([
            '/route/create',
            'model' => $model
        ]);
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
        if (!$model->load(Yii::$app->request->post())) {
            return $this->render('update', [
                'model' => $model,
            ]);
        }

        if (Yii::$app->request->post('submit') == 'delete') {
             $exist = Qr::find()
                ->where('event_ID=:event_id and route_id=:route_id')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected,
                        ':route_id' => $model->route_ID
                    ])
                ->exists();

            if (!$exist) {
                $exist = OpenVragen::find()
                    ->where('event_ID=:event_id and route_id=:route_id')
                    ->addParams(
                        [
                            ':event_id' => Yii::$app->user->identity->selected,
                            ':route_id' => $model->route_ID
                        ])
                    ->exists();
            }

            if (!$exist) {
                $exist = NoodEnvelop::find()
                    ->where('event_ID=:event_id and route_id=:route_id')
                    ->addParams(
                        [
                            ':event_id' => Yii::$app->user->identity->selected,
                            ':route_id' => $model->route_ID
                        ])
                    ->exists();
            }

            if (!$exist) {
                $model->delete();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete route, it contains items which should be removed first.'));
            }
        }

        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save changes to route.'));
        }
        return $this->redirect(['route/index']);
    }

    /**
     * Deletes an existing TblRoute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        dd('NIET MEER NODIG?');
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
	public function actionMoveUpDown($id, $up_down)
	{
        $model = $this->findModel($id);
        if ($up_down === 'up') {
            $previousModel = Route::find()
                ->where('event_ID =:event_id and day_date =:date and route_volgorde <:order')
                ->params([':event_id' => Yii::$app->user->identity->selected, ':date' => $model->day_date, ':order' => $model->route_volgorde])
                ->orderBy('route_volgorde DESC')
                ->one();
        } elseif ($up_down === 'down') {
            $previousModel = Route::find()
                ->where('event_ID =:event_id AND day_date =:date AND route_volgorde >:order')
                ->params([':event_id' => Yii::$app->user->identity->selected, ':date' => $model->day_date, ':order' => $model->route_volgorde])
                ->orderBy('route_volgorde ASC')
                ->one();
        }

        $tempCurrentVolgorde = $model->route_volgorde;
        $model->route_volgorde = $previousModel->route_volgorde;
        $previousModel->route_volgorde = $tempCurrentVolgorde;

        $model->save();
        $previousModel->save();

        $startDate=EventNames::getStartDate(Yii::$app->user->identity->selected);
        $endDate=EventNames::getEndDate(Yii::$app->user->identity->selected);
        $searchModel = new RouteSearch();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('/route/index', [
                'searchModel' => $searchModel,
                'startDate' => $startDate,
                'endDate' => $endDate]);
        }
        return $this->render('/route/index',[
            'searchModel' => $searchModel,
            'startDate' => $startDate,
            'endDate' => $endDate]);
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
        if (($model = Route::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
