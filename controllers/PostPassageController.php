<?php

namespace app\controllers;

use Yii;
use app\models\PostPassage;
use app\models\PostPassageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PostPassageController implements the CRUD actions for PostPassage model.
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
                'only' => ['create', 'createDayStart', 'updateVertrek', 'index', 'update', 'delete', 'cancel'],
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
                        'actions'=>array( 'update', 'cancel-beantwoording'),
                        'roles'=>array('@'),
                    ),
                    array(
                        'allow' => TRUE,
                        'actions'=>array('index', 'delete', 'create', 'createDayStart', 'updateVertrek', 'cancel'),
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
     * Lists all PostPassage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostPassageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PostPassage model.
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
     * Creates a new PostPassage model.
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
     * Updates an existing PostPassage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model->load(Yii::$app->request->post())) {
           if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_form', [
                    'model' => $model,
                ]);
           }
           return $this->render('update', [
               'model' => $model,
           ]);
        }
        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save changes.'));
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['groups/index-posten']);
    }

    /**
     * Deletes an existing PostPassage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['groups/index-posten']);
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
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCancel($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list', [
                'model' => $model,
            ]);
        }
    //    return $this->redirect(['groups/index-posten']);
    }

    /**
     * Finds the PostPassage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PostPassage the loaded model
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
