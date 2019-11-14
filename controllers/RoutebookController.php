<?php

namespace app\controllers;

use Yii;
use app\models\Routebook;
use app\models\Route;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


/**
 * RoutebookController implements the CRUD actions for Routebook model.
 */
class RoutebookController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Routebook models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Routebook::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Routebook model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModels($id),
        ]);
    }

    /**
     * Updates an existing Routebook model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($route_ID)
    {
        $model = $this->findModel($route_ID);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['route/index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpload()
    {
        $route = new RouteBook;
        $route->image = UploadedFile::getInstanceByName('upload');

        $filename = Yii::$app->request->get('routebook_ID')
        . '-' . Yii::$app->request->get('event_ID')
        . '-' . Yii::$app->request->get('route_ID');
        $uniqueFilename = $route->getUniquePath($filename);
        
        $path = Yii::$app->params['routebookimages'] . $uniqueFilename . '.jpg';

        $url = Url::to('@web/uploads/routebook/' . $uniqueFilename . '.jpg');

        if(!$route->validate('image')){
            echo "<script type='text/javascript'>alert('Geen geldige plaatje!');</script>";
            return;
        }

        $route->image->saveAs($path);
        $funcNum = $_GET['CKEditorFuncNum'] ;

        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '');</script>";
      }

    /**
     * Deletes an existing Routebook model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Routebook model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Routebook the loaded model
     */
    protected function findModel($route_id)
    {
        if (($model = Routebook::findOne(['route_ID' => $route_id])) === null) {
            $route = Route::findOne($route_id);
            $model = new Routebook();
            $model->event_ID = $route->event_ID;
            $model->route_ID = $route->route_ID;
            return $model;
        }

        return $model;
    }

    /**
     * Finds the Routebook models based on route_ID.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Routebook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModels($id)
    {
        if (($models = Routebook::find(['route_ID' => $id])->all()) !== null) {
            return $models;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
