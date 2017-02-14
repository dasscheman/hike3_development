<?php

namespace app\controllers;

use Yii;
use app\models\OpenVragen;
use app\models\OpenVragenAntwoorden;
use app\models\OpenVragenAntwoordenSearch;
use app\models\DeelnemersEvent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\OpenVragenSearch;
use \yii\helpers\Json;

/**
 * OpenVragenAntwoordenController implements the CRUD actions for OpenVragenAntwoorden model.
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
                'only' => [
                    'index', 'delete', 'view-controle', 'updateOrganisatie',
                    'viewPlayers', 'update', 'cancel',  'create',
                    'antwoordGoedOfFout', 'beantwoorden', 'beantwoorden-dashboard',
                    'cancel-beantwoording', 'cancel-beantwoording-dashboard'],
                'rules' => [
                    [
                        'allow' => FALSE,
                        'roles'=>['?'],
                    ],
                    array(
                        'allow' => TRUE,
                        'actions'=>array('cancel-beantwoording', 'cancel-beantwoording-dashboard', 'cancel'),
                        'roles'=>array('@'),
                    ),
                    [
                        'allow' => TRUE,
                        'actions'=>[
                            'index', 'delete', 'view-controle',
                            'updateOrganisatie', 'viewPlayers', 'update',
                            'create', 'antwoordGoedOfFout', 'beantwoorden',
                            'beantwoorden-dashboard'],
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        }
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=>['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all OpenVragenAntwoorden models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OpenVragenAntwoordenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OpenVragenAntwoorden model.
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
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new OpenVragenAntwoorden;
        $modelVraag = OpenVragen::findOne($id);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'modelVraag' => $modelVraag,
            ]);
        }
        return $this->render('create', [
             'model' => $model,
             'modelVraag' => $modelVraag,
        ]);
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBeantwoorden($id)
    {
        $model = new OpenVragenAntwoorden;
        $modelVraag = OpenVragen::findOne($id);

        $model->event_ID = $modelVraag->event_ID;
        $model->group_ID = DeelnemersEvent::getGroupOfPlayer($modelVraag->event_ID, Yii::$app->user->id);
        $model->open_vragen_ID = $id;
        $model->checked = 0;

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            foreach ($model->getErrors() as $error) {
               Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save the question.') . ' ' . Json::encode($error));
            }
        } else {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Changes are saved.'));
        }

        $modelVraag = OpenVragen::findOne($id);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list', [
                'model' => $modelVraag,
            ]);
        }
        return $this->redirect(['site/index ']);
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBeantwoordenDashboard($id)
    {
        $model = new OpenVragenAntwoorden;
        $modelVraag = OpenVragen::findOne($id);

        $model->event_ID = $modelVraag->event_ID;

        $model->group_ID = DeelnemersEvent::getGroupOfPlayer($modelVraag->event_ID, Yii::$app->user->id);
        $model->open_vragen_ID = $id;
        $model->checked = 0;

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            foreach ($model->getErrors() as $error) {
               Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save the question.') . ' ' . Json::encode($error));
            }
        } else {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Changes are saved.'));
        }

        $searchQuestionsModel = new OpenVragenSearch();
        $questionsData = $searchQuestionsModel->searchQuestionNotAnsweredByGroup(Yii::$app->request->queryParams);
        if (Yii::$app->request->isAjax) {
            return Yii::$app->controller->renderAjax('/open-vragen-antwoorden/view-dashboard', ['model'=>$questionsData]);
            // return $this->renderAjax('_list-dashboard', [
            //     'model' => $modelVraag,
            // ]);
        }
        return $this->redirect(['site/index ']);
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCancelBeantwoording($id)
    {
        $model = OpenVragen::findOne($id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['site/game-overview']);
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCancelBeantwoordingDashboard($id)
    {
        $model = OpenVragen::findOne($id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list-dashboard', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['site/game-overview']);
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
            return $this->renderAjax('_form-organisation', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['open-vragen-antwoorden/index']);
    }

    /**
     * Updates an existing OpenVragenAntwoorden model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateOrganisatie($id)
    {
        $model = $this->findModel($id);

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            foreach ($model->getErrors() as $error) {
               Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save the question.') . ' ' . Json::encode($error));
            }
        } else {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Changes are saved.'));
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-organisation', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['open-vragen-antwoorden/index']);
    }

    /**
     * Deletes an existing OpenVragenAntwoorden model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
     * Finds the OpenVragenAntwoorden model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OpenVragenAntwoorden the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OpenVragenAntwoorden::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
