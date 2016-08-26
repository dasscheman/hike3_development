<?php

namespace app\controllers;

use Yii;
use app\models\TblOpenVragenAntwoorden;
use app\models\TblOpenVragenAntwoordenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * OpenVragenAntwoordenController implements the CRUD actions for TblOpenVragenAntwoorden model.
 */
class OpenVragenAntwoordenController extends Controller
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
                'only' => ['connect', 'accept', 'decline','update', 'delete', 'create'],
                'rules' => [ 
                    [
                        'deny',  // deny all users
                        'users'=>['?'],
                    ],
                    [
                        'allow', // allow admin user to perform 'viewplayers' actions
                        'actions'=>['antwoordGoedOfFout'],
                        'expression'=> OpenVragenAntwoorden::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id,
                            $_GET["event_id"],
                            $_GET["id"]),
                    ],
                    [	
                        'allow', // allow admin user to perform 'viewplayers' actions
                        'actions'=>['viewPlayers', 'update',  'create'],
                        'expression'=> OpenVragenAntwoorden::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id,
                            $_GET["event_id"],
                            "",
                            $_GET["group_id"]),
                    ],
                    [	
                        'allow', // allow admin user to perform 'viewplayers' actions
                        'actions'=>['index', 'delete', 'viewControle', 'updateOrganisatie'],
                        'expression'=> OpenVragenAntwoorden::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id,
                            $_GET["event_id"]),
                    ],
                    [   
                        'deny',  // deny all users
                        'users'=>['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all TblOpenVragenAntwoorden models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OpenVragenAntwoordenSearch();
        $dataProvider = $searchModel->searchAnswered(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }    
    
    /**
     * Displays a single TblOpenVragenAntwoorden model.
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
     * Creates a new TblOpenVragenAntwoorden model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OpenVragenAntwoorden();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(
                [
                    '/game/groupOverview',
                    'event_id'=>$_GET['event_id'],
                    'group_id'=>$_GET['group_id']]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblOpenVragenAntwoorden model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(isset($_GET['event_id']) AND
            isset($_GET['group_id']) AND
            isset($_GET['vraag_id'])) {
                $data=OpenVragenAntwoorden::find('event_ID =:event_id AND
                                        group_ID =:group_id AND
                                        open_vragen_ID=:vraag_id',
                                    array(':event_id'=>$_GET['event_id'],
                                          ':group_id'=>$_GET['group_id'],
                                          ':vraag_id'=>$_GET['vraag_id']));
        }

        if(isset($data->open_vragen_antwoorden_ID) && $data->checked) {
            throw new CHttpException(403,"Vraag is al gecontroleerd!!");
        }
        
        if(isset($data->open_vragen_antwoorden_ID)) {
            $id = $data->open_vragen_antwoorden_ID;
            $model=$this->findModel($id);
        } else {
            $this->render('update',array('model'=>$model,	));
        }
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/game/groupOverview',
						      'event_id'=>$model->event_ID,
						      'group_id'=>$model->group_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblOpenVragenAntwoorden model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionViewControle()
    {
        $event_id = $_GET['event_id'];
        $where = "event_ID = $event_id AND
			  checked = 0 ";

        $DataProvider=new CActiveDataProvider('OpenVragenAntwoorden',
						       array('criteria'=>array('condition'=>$where,
									       'order'=>'group_ID DESC',
										),
							     'pagination'=>array('pageSize'=>10,),
							     )
						       );
        $this->render('viewControle',array(
            'dataProvider'=>$DataProvider,
        ));
    }


    public function actionViewPlayers()
    {
        $event_id = $_GET['event_id'];
        $group_id = $_GET['group_id'];

        $testwhere = "event_ID = $event_id AND group_ID = $group_id";
        $openVragenAntwoordenDataProvider=new CActiveDataProvider('OpenVragenAntwoorden',
            array(
                'criteria'=>
                    array(
                        'condition'=>$testwhere,
                        'order'=>'create_time DESC',
                    ),
                'pagination'=>array(
                    'pageSize'=>30,
                ),
            ));
        
        $this->render('viewPlayers',array(
        'openVragenAntwoordenDataProvider'=>$openVragenAntwoordenDataProvider,
        ));
    }

    public function actionAntwoordGoedOfFout()
    {

        $model=$this->loadModel($_GET["id"]);

        $model->checked = 1;
        $model->correct = $_GET['goedfout'];
        $model->save();

        $event_id = $_GET['event_id'];
        $where = "event_ID = $event_id AND
              checked = 0 ";

        $DataProvider=new CActiveDataProvider('OpenVragenAntwoorden',
                               array('criteria'=>array('condition'=>$where,
                                           'order'=>'group_ID DESC',
                                        ),
                                 'pagination'=>array('pageSize'=>10,),
                                 )
                               );
        $this->render('viewControle',array(
            'dataProvider'=>$DataProvider,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'groupOverview' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdateOrganisatie()
    {
        if(isset($_GET['event_id']) AND
           isset($_GET['group_id']) AND
           isset($_GET['vraag_id'])) {
            $data=OpenVragenAntwoorden::find('event_ID =:event_id AND
                                    group_ID =:group_id AND
                                    open_vragen_ID=:vraag_id',
                                array(':event_id'=>$_GET['event_id'],
                                      ':group_id'=>$_GET['group_id'],
                                      ':vraag_id'=>$_GET['vraag_id']));}
        if(isset($data->open_vragen_antwoorden_ID))
        {$id = $data->open_vragen_antwoorden_ID;}

        $model=$this->findModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['OpenVragenAntwoorden']))
        {
            $model->attributes=$_POST['OpenVragenAntwoorden'];
            if($model->save())
                $this->redirect(array('/game/groupOverview',
                              'event_id'=>$model->event_ID,
                              'group_id'=>$model->group_ID));
        }

        $this->render('updateOrganisatie',
                      array('model'=>$model,
                            )
                      );
    }
    
    /**
     * Finds the TblOpenVragenAntwoorden model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblOpenVragenAntwoorden the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblOpenVragenAntwoorden::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
