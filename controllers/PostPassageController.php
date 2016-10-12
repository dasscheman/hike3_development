<?php

namespace app\controllers;

use Yii;
use app\models\TblPostPassage;
use app\models\TblPostPassageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PostPassageController implements the CRUD actions for TblPostPassage model.
 */
class PostPassageController extends Controller
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
                'only' => ['dynamicpostscore', 'dynamicpostid', 'create', 'createDayStart', 'updateVertrek', 'index', 'update', 'delete'],
                'rules' => [
                    array(
                        'allow' => FALSE,
                        'roles'=>array('?'),
                    ),
                    array(
                        'allow' => TRUE,
                        'actions'=>array('dynamicpostscore', 'dynamicpostid'),
                        'roles'=>array('@'),
                    ),
                    array(	
                        'allow' => TRUE,
                        'actions'=>array('index', 'update', 'delete', 'create', 'createDayStart', 'updateVertrek'),
                        'matchCallback'=> Yii::$app->user->identity->isActionAllowed(),
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
     * Lists all TblPostPassage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new lPostPassageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single TblPostPassage model.
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
     * Creates a new TblPostPassage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PostPassage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                '/game/groupOverview',
                'event_id'=>$model->event_ID,
                'group_id'=>$model->group_IDD]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates an existing TblPostPassage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                '/game/groupOverview',
                'event_id'=>$model->event_ID,
                'group_id'=>$model->group_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing TblPostPassage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
            '/game/groupOverview',
            'event_id'=>$model->event_ID,
            'group_id'=>$model->group_ID));
    }

    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'groupOverview' page.
     */
    public function actionCreateDayStart()
    {
        $model = new PostPassage;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['PostPassage']))
        {
            $model->attributes=$_POST['PostPassage'];
            $model->post_ID = Posten::getStartPost($_GET['event_id']);
            $model->event_ID = $_GET['event_id'];
            $model->group_ID = $_GET['group_id'];

            if($model->save())
                return $this->redirect(array(
                    '/game/groupOverview',
                    'event_id'=>$model->event_ID,
                    'group_id'=>$model->group_ID));
        }

        return $this->render('createDayStart',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'groupOverview' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdateVertrek($id)
    {
        $model=$this->findModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['PostPassage']))
        {
            $model->attributes=$_POST['PostPassage'];
            if($model->save())
                return $this->redirect(array('/game/groupOverview',
                              'event_id'=>$model->event_ID,
                              'group_id'=>$model->group_ID));
        }

        return $this->render('updateVertrek',array(
            'model'=>$model,
        ));
    }


    /**
     * Deze actie wordt gebruikt voor de form velden.
     * Returns score depending on post_ID and event_id
     * Deze moet anders, want er wordt sowieso altijd maar 1 waarde gereturnd, dus list is niet nodig.
     */
    public function actionDynamicPostScore()
    {
        $data=Posten::findAll('post_ID =:post_id, event_ID =:event_id',
              array(':post_id'=>$_POST['post_ID'],
                ':event_id'=>$_GET['event_id']));

        $data=CHtml::listData($data,'score','score');

        foreach($data as $value=>$name)
        {
            echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
        }
    }

    /**
     * Deze actie wordt gebruikt voor de form velden.
     * Returns list with available posten depending on day and event.
     */
    public function actionDynamicPostId()
    {
        $day_id = $_POST['day_id'];
        $event_id = $_POST['event_id'];


        $data=Posten::findAll('day_ID =:day_id AND event_ID =:event_id',
              array(':day_id'=>$day_id,
                ':event_id'=>$event_id));
        $mainarr = array();

        foreach($data as $obj)
        {
            //De post naam moet gekoppeld worden aan de post_id:
            $mainarr["$obj->post_ID"] = Posten::getPostName($obj->post_ID);
        }

        foreach($mainarr as $value=>$name)
        {
            echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
        }
    }

    /**
     * Finds the TblPostPassage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblPostPassage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostPassage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
