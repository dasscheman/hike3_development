<?php

namespace app\controllers;

use Yii;
use app\models\Bonuspunten;
use app\models\BonuspuntenSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['post'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => false, // deny all users
                        'roles' => ['*'],
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
     * @deprecated maart 2018
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

        if (!$model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax) {
                return $this->renderPartial('create', [
                    'model' => $model,
                ]);
            }
            return $this->render('create', [
                    'model' => $model,
            ]);
        }

        if($model->save()) {
            Yii::$app->cache->flush();
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Kan wijzigingen niet opslaan.'));
        }

        return $this->redirect(['site/index']);
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
            Yii::$app->cache->flush();
            Yii::$app->session->setFlash('info', Yii::t('app', 'Bonuspunten zijn verwijdered'));
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
        $action = 'update';

        if (Yii::$app->request->post('update') == 'delete') {
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Bonuspunten zijn verwijderd.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan bonuspunten niet verwijderen.'));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        if (Yii::$app->request->post('Bonuspunten') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Wijzigingen opgeslagen.'));
//                if (Yii::$app->request->isAjax) {
//                    return $this->renderAjax('_list', ['model' => $model]);
//                }
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                    'model' => $model,
                    'action' => $action,
            ]);
        }
        return $this->render('update', [
                'model' => $model,
                'action' => $action,
        ]);
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
        $model = Bonuspunten::findOne([
                'bouspunten_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
