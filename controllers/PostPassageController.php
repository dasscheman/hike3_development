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
                        'actions'=>array('cancel-beantwoording', 'cancel'),
                        'roles'=>array('@'),
                    ),
                    [
                        'allow' => TRUE,
                        'actions'=>array('start', 'checkin', 'checkout'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                [
                                    'post_ID' => Yii::$app->request->get('post_ID'),
                                    'group_ID' => Yii::$app->request->get('group_ID')
                                ]);
                        }
                    ],
                    [
                        'allow' => TRUE,
                        'actions'=>array('index', 'delete', 'createDayStart', 'updateVertrek', 'update'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                ['posten_passage_ID' => Yii::$app->request->get('posten_passage_ID')]);
                        }
                    ],
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
    public function actionStart($group_ID, $post_ID)
    {
        if(!Posten::isStartPost($post_ID)) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'This is not start station.'));
        }
        if (!PostPassage::istimeLeftToday(Yii::$app->user->identity->selected, $group_ID)) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'This group has no time left and cannot be checked in to this station.'));
        }

        $model = new PostPassage();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Could not check in to station.'));
            }

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_list', [
                    'model' => $model,
                ]);
            }
            return $this->redirect(['posten/index']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['groups/index-posten']);
    }

    /**
     * Creates a new PostPassage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCheckin($group_ID, $post_ID)
    {
        $model = new PostPassage();
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
        if (!PostPassage::istimeLeftToday(Yii::$app->user->identity->selected, $model->group_ID)) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'This group has no time left and cannot be checked in to this station.'));
        } elseif (!$model->save()) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Could not check in to station.'));
        }
        if (Yii::$app->request->isAjax) {
             return $this->renderAjax('_form', [
                 'model' => $model,
             ]);
        }
        return $this->redirect(['posten/index']);
    }

    /**
     * Updates an existing PostPassage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCheckout($posten_passage_ID)
    {
        $model = $this->findModel($posten_passage_ID);
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
        // $model = $this->findModel($posten_passage_ID);
        // $model->load(Yii::$app->request->post());

        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save changes.'));
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['posten/index']);
    }

    /**
     * Updates an existing PostPassage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $posten_passage_ID
     * @return mixed
     */
    public function actionUpdate($posten_passage_ID)
    {
        $model = $this->findModel($posten_passage_ID);
        if (!$model->load(Yii::$app->request->post())) {
           return $this->renderPartial('update', [
               'model' => $model,
           ]);
        }
        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save changes.'));
        }

        return $this->redirect(['groups/index-posten']);
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCancel($posten_passage_ID)
    {
        $model = $this->findModel($posten_passage_ID);
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
    public function actionDelete($posten_passage_ID)
    {
        $this->findModel($posten_passage_ID)->delete();

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
