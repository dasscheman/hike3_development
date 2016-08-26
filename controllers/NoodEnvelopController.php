<?php

namespace app\controllers;

use Yii;
use app\models\TblNoodEnvelop;
use app\models\TblNoodEnvelopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
                        'deny',  // deny all guest users
                        'users'=>array('?'),
                    ),
                    array(	
                        'deny',  // deny if event_id is not set
                        'actions'=>array('create'),
                        'expression'=> !isset($_GET["route_id"]),
                    ),
                    array(	
                        'deny',  // deny if event_id is not set
                        'actions'=>array('delete', 'update'),
                        'expression'=> !isset($_GET["nood_envelop_id"]),
                    ),
                    array(	
                        'deny',  // deny if group_id is not set
                        'actions'=>array('viewPlayers'),
                        'expression'=> !isset($_GET["group_id"]),
                    ),
                    array(	
                        'allow', // only when $_GET are set
                        'actions'=>array('moveUpDown'),
                        'expression'=> NoodEnvelop::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id,
                            $_GET["event_id"],
                            $_GET["nood_envelop_id"],
                            "",
                            $_GET["date"],
                            $_GET["volgorde"],
                            $_GET["up_down"])),
                    array(
                        'allow', // allow authenticated user to perform 'index' actions
                        'actions'=>array('viewPlayers'),
                        'expression'=> NoodEnvelop::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id,
                            $_GET["event_id"],
                            "",
                            $_GET["group_id"]),
                    ),
                    array(
                        'allow', // allow authenticated user to perform 'index' actions
                        'actions'=>array('create', 'index', 'update', 'delete'),
                        'expression'=> NoodEnvelop::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id,
                            $_GET["event_id"]),
                    ),
                    array(
                        'deny',  // deny all users
                        'users'=>array('*'),
                    ),
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
            $model->attributes=$_POST['NoodEnvelop'];
            $model->event_ID = $_GET['event_id'];
            $model->route_ID = $_GET['route_id'];
            $model->nood_envelop_volgorde = NoodEnvelop::getNewOrderForNoodEnvelop(
                $_GET['event_id'],
                $_GET['route_id']);

            if($model->save())
                return $this->redirect(array(
                    '/route/view',
                    'event_id'=>$model->event_ID,
                    'route_id'=>$model->route_ID));
        }
      
        return $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates an existing TblNoodEnvelop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $nood_envelop_id = $_GET['nood_envelop_id'];
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                '/route/view',
                'event_id'=>$model->event_ID,
                'route_id'=>$model->route_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing TblNoodEnvelop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
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
