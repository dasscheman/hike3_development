<?php

namespace app\controllers;

use Yii;
use app\models\NoodEnvelop;
use app\models\EventNames;
use app\models\RouteSearch;
use app\models\NoodEnvelopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\OpenNoodEnvelop;

/**
 * NoodEnvelopController implements the CRUD actions for TblNoodEnvelop model.
 */
class NoodEnvelopController extends Controller
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
                'only' => ['viewPlayers', 'moveUpDown','viewPlayers', 'create', 'index', 'update', 'delete'],
                'rules' => [
                    array(
                        'allow' => FALSE,
                        'roles'=>array('?'),
                    ),
                    array(
                        'allow' => TRUE,
                        'actions'=>array('create', 'index', 'update', 'delete', 'viewPlayers', 'moveUpDown'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(NULL, NULL, ['nood_envelop_ID' => Yii::$app->request->get('id')]);
                        },
                        'roles'=>array('@'),
                    ),
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ]
            ],
        ];
    }

    /**
     * Lists all TblNoodEnvelop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NoodEnvelopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblNoodEnvelop model.
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
     * Creates a new TblNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NoodEnvelop();
        if ($model->load(Yii::$app->request->post())) {
			$model->event_ID = Yii::$app->user->identity->selected;
			$model->setNewOrderForNoodEnvelop();

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
            $model->route_ID = Yii::$app->request->get(1)['route_id'];
            return $this->renderPartial('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblNoodEnvelop model.
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
            $exist = OpenNoodEnvelop::find()
               ->where('event_ID=:event_id and nood_envelop_ID=:nood_envelop_id')
               ->addParams(
                   [
                       ':event_id' => Yii::$app->user->identity->selected,
                       ':nood_envelop_id' => $model->nood_envelop_ID
                   ])
               ->exists();

            if (!$exist) {
                $model->delete();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete hint, it contains items which should be removed first.'));
            }
        }

        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save changes to hint.'));
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
     * Deletes an existing TblNoodEnvelop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        dd('NIET MEER NODIG');
        $model = $this->findModel($id);

        $exist = OpenNoodEnvelop::find()
           ->where('event_ID=:event_id and nood_envelop_ID=:nood_envelop_id')
           ->addParams(
               [
                   ':event_id' => Yii::$app->user->identity->selected,
                   ':nood_envelop_id' => $model->nood_envelop_ID
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




        $nood_envelop_ID = $_GET['nood_envelop_id'];

        try
        {
            $this->findModel($id)->delete();
        }
        catch(CDbException $e)
        {
            throw new CHttpException(400,"Je kan deze hint niet verwijderen.");
        }
        return $this->redirect(isset($_POST['returnUrl']) ?
					$_POST['returnUrl'] : array('/route/view',
								    'event_id'=>$_GET['event_id'],
								    'route_id'=>$_GET['route_id']));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionViewPlayers()
    {
        $event_id = $_GET['event_id'];

        $active_day = EventNames::getActiveDayOfHike($event_id);

        $noodEnvelopDataProvider=new CActiveDataProvider('NoodEnvelop',
            array(
                'criteria'=>array(
                     'join'=>'JOIN tbl_route route ON route.route_ID = t.route_ID',
                     'condition'=>'route.day_date =:active_day
                                    AND route.event_ID =:event_id',
                     'params'=>array(':active_day'=>$active_day,
                                      ':event_id'=>$event_id),
                     'order'=>'route_ID ASC, nood_envelop_volgorde ASC'
                    ),
                'pagination'=>array(
                    'pageSize'=>30,
                ),
            )
        );

        return $this->render('viewPlayers',array(
            'noodEnvelopDataProvider'=>$noodEnvelopDataProvider,
        ));
    }

    public function actionMoveUpDown()
    {
        $event_id = $_GET['event_id'];
        $nood_envelop_id = $_GET['nood_envelop_id'];
        $nood_envelop_volgorde = $_GET['volgorde'];
        $up_down = $_GET['up_down'];
        $route_id = NoodEnvelop::getRouteIdOfEnvelop($_GET['nood_envelop_id']);

        $currentModel = NoodEnvelop::findByPk($nood_envelop_id);

        $criteria = new CDbCriteria;

        if ($up_down=='up')
        {
            $criteria->condition = 'event_ID =:event_id AND route_ID=:route_id AND nood_envelop_volgorde <:order';
            $criteria->params=array(':event_id' => $event_id, ':route_id' => $route_id , ':order' => $nood_envelop_volgorde);
            $criteria->order= 'nood_envelop_volgorde DESC';
        }
        if ($up_down=='down')
        {
            $criteria->condition = 'event_ID =:event_id AND route_ID=:route_id AND nood_envelop_volgorde >:order';
            $criteria->params=array(':event_id' => $event_id, ':route_id' => $route_id , ':order' => $nood_envelop_volgorde);
            $criteria->order= 'nood_envelop_volgorde ASC';
        }
        $criteria->limit=1;
        $previousModel = NoodEnvelop::findAll($criteria);

        $tempCurrentVolgorde = $currentModel->nood_envelop_volgorde;
        $currentModel->nood_envelop_volgorde = $previousModel[0]->nood_envelop_volgorde;
        $previousModel[0]->nood_envelop_volgorde = $tempCurrentVolgorde;

        $currentModel->save();
        $previousModel[0]->save();

        if (Route::routeIdIntroduction($currentModel->route_ID))
        {
            return $this->redirect(array('route/viewIntroductie',
                "route_id"=>$currentModel->route_ID,
                "event_id"=>$currentModel->event_ID,));
        } else {
            return $this->redirect(array(
                'route/view',
                "route_id"=>$currentModel->route_ID,
                "event_id"=>$currentModel->event_ID,));
        }
    }

    /**
     * Finds the TblNoodEnvelop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblNoodEnvelop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NoodEnvelop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
