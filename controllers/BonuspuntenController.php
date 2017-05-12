<?php

namespace app\controllers;

use Yii;
use app\models\Bonuspunten;
use app\models\BonuspuntenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BonuspuntenController implements the CRUD actions for Bonuspunten model.
 */
class BonuspuntenController extends Controller
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
                // We will override the default rule config with the new AccessRule class
                'only' => ['index', 'view', 'create', 'update', 'delete', 'cancel'],
                'rules' => [
                    [
                        'actions' => ['index', 'delete', 'cancel',  'create', 'update', 'view'],
                        'allow' => TRUE,
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isActionAllowed(NULL, NULL, ['bonuspunten_ID' => Yii::$app->request->get('bonuspunten_ID')]);
                        }
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ],
        ];
    }



    /**
     * Lists all Bonuspunten models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BonuspuntenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bonuspunten model.
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
     * Creates a new Bonuspunten model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bonuspunten();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['site/index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCancel($bonuspunten_ID)
    {
        $model = $this->findModel($bonuspunten_ID);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['bonuspunten/index']);
    }

    /**
     * Deletes an existing Bonuspunten model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($bonuspunten_ID)
    {
        if ($this->findModel($bonuspunten_ID)->delete()) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Bonuspoints are deleted'));
            return $this->redirect(['bonuspunten/index']);
        }
        $model = $this->findModel($bonuspunten_ID);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['bonuspunten/index']);
    }

    /**
     * Updates an existing Bonuspunten model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($bonuspunten_ID)
    {
        $model = $this->findModel($bonuspunten_ID);

        if ($model->load(Yii::$app->request->post())) {
            if (isset(Yii::$app->request->post('Bonuspunten')['date'])) {
                $model->date = Yii::$app->setupdatetime->convert(Yii::$app->request->post('Bonuspunten')['date']);
            }

            if (!$model->save()) {
                foreach ($model->getErrors() as $error) {
                    Yii::$app->session->setFlash('error', Json::encode($error));
                }
            } else {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Changes are saved.'));
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['bonuspunten/index']);
    }

    /**
     * Finds the Bonuspunten model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bonuspunten the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bonuspunten::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
