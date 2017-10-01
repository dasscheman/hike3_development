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
                    'viewPlayers', 'update', 'create',
                    'antwoord-fout', 'antwoord-goed', 'beantwoorden'],
                'rules' => [
                    [
                        'allow' => FALSE,
                        'roles'=>['?'],
                    ],
                    [
                        'allow' => TRUE,
                        'actions' => ['beantwoorden'],
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                [
                                    'open_vragen_ID' => Yii::$app->request->get('open_vragen_ID'),
                                    'group_ID' => Yii::$app->request->get('group_ID')
                                ]);
                        },
                    ],
                    [
                        'allow' => TRUE,
                        'actions'=>[
                            'index', 'delete', 'view-controle',
                            'updateOrganisatie', 'viewPlayers', 'update',
                            'create', 'antwoord-fout', 'antwoord-goed'],
                            'matchCallback'=> function () {
                                return Yii::$app->user->identity->isActionAllowed(
                                    NULL,
                                    NULL,
                                    ['open_vragen_antwoorden_ID' => Yii::$app->request->get('open_vragen_antwoorden_ID')]);
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
    public function actionBeantwoorden($open_vragen_ID)
    {
        $model = new OpenVragenAntwoorden;
        $modelVraag = OpenVragen::findOne($open_vragen_ID);
        if (!$model->load(Yii::$app->request->post())) {
            return $this->renderPartial('beantwoorden', [
                'model' => $model,
                'modelVraag' => $modelVraag,
            ]);
        }
        if (Yii::$app->request->post('submit') == 'beantwoord-vraag') {
            $model->group_ID = DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID, Yii::$app->user->id);
            $model->checked = 0;
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not open the hint.'));
            }  else {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Question is answered.'));
            }
        }

        return $this->redirect(['site/overview-players']);
    }

    /**
     * Updates an existing OpenVragenAntwoorden model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateOrganisatie($open_vragen_antwoorden_ID)
    {
        $model = $this->findModel($open_vragen_antwoorden_ID);

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            foreach ($model->getErrors() as $error) {
               Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save the question.') . ' ' . Json::encode($error));
            }
        } else {
            Yii::$app->cache->flush();
            Yii::$app->session->setFlash('info', Yii::t('app', 'Changes are saved.'));
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

    public function actionAntwoordGoed($open_vragen_antwoorden_ID)
    {
        $model = $this->findModel($open_vragen_antwoorden_ID);
        $model->checked = 1;
        $model->correct = 1;
        if (!$model->save()) {
            foreach ($model->getErrors() as $error) {
               Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save the question.') . ' ' . Json::encode($error));
            }
        } else {
            Yii::$app->cache->flush();
        }
        // TODO
        // Now the overview page is completly reloaded after a question check.
        // Maybe later a ajax refresh.
        // if (Yii::$app->request->isAjax) {
        //     return $this->renderAjax('_list-controle', [
        //         'model' => $model]);
        // }

        return $this->redirect(['site/overview-organisation']);
    }

    public function actionAntwoordFout($open_vragen_antwoorden_ID)
    {
        $model = $this->findModel($open_vragen_antwoorden_ID);
        $model->checked = 1;
        $model->correct = 0;
        if ( !$model->save()) {
            foreach ($model->getErrors() as $error) {
               Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save the question.') . ' ' . Json::encode($error));
            }
        } else {
            Yii::$app->cache->flush();
        }
        // TODO
        // Now the overview page is completly reloaded after a question check.
        // Maybe later a ajax refresh.
        // if (Yii::$app->request->isAjax) {
        //     return $this->renderAjax('_list-controle', [
        //         'model' => $model]);
        // }
        return $this->redirect(['site/overview-organisation']);
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
