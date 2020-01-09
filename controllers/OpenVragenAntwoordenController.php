<?php

namespace app\controllers;

use Yii;
use app\models\OpenVragen;
use app\models\OpenVragenAntwoorden;
use app\models\OpenVragenAntwoordenSearch;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\helpers\Json;

/**
 * OpenVragenAntwoordenController implements the CRUD actions for OpenVragenAntwoorden model.
 */
class OpenVragenAntwoordenController extends Controller {

    public function behaviors() {
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
                        'actions' => ['index',  'update-organisatie'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['antwoord-goed',  'antwoord-fout'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['beantwoorden', 'update'],
                        'roles' => ['deelnemerIntroductie', 'deelnemerGestartTime'],
                    ],
                    [
                        'allow' => FALSE, // deny all users
                        'roles' => ['*'],
                    ],
                ]
            ],
        ];
    }

    /**
     * Lists all OpenVragenAntwoorden models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new OpenVragenAntwoordenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBeantwoorden($open_vragen_ID) {
        $model = new OpenVragenAntwoorden;
        $modelVraag = OpenVragen::findOne($open_vragen_ID);

        if($modelVraag->event_ID != Yii::$app->user->identity->selected_event_ID) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Deze vraag is niet voor deze hike.'));
            return $this->redirect(['site/overview-players']);
        }

        if (isset($modelVraag->route->start_datetime) && $modelVraag->route->start_datetime > $now) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Deze vraag hoort bij routeonderdeel die nog niet gestart is.'));
            return $this->redirect(['site/overview-players']);
        }

        if (isset($modelVraag->route->end_datetime) && $modelVraag->route->end_datetime < $now) {
          Yii::$app->session->setFlash('error', Yii::t('app', 'Deze vraag hoort bij routeonderdeel die al afegelopen is..'));
          return $this->redirect(['site/overview-players']);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->renderPartial('beantwoorden', [
                'model' => $model,
                'modelVraag' => $modelVraag,
            ]);
        }
        $deelnemersEvent = new DeelnemersEvent();
        if (Yii::$app->request->post('beantwoord') == 'beantwoord-vraag') {
            $groupPlayer = $deelnemersEvent->getGroupOfPlayer($modelVraag->event_ID);
            if (!$groupPlayer) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Je mag deze vraag niet beantwoorden.'));
                return $this->redirect(['site/index']);
            }

            $model->group_ID = $groupPlayer;
            $model->checked = 0;

            if (!$model->save()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan je antwoord niet opslaan.'));
            } else {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Vraag is beantwoord.'));
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
    public function actionUpdateOrganisatie($open_vragen_antwoorden_ID) {
        $model = $this->findModel($open_vragen_antwoorden_ID);

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan wijzigingen niet opslaan.') . ' ' . Json::encode($error));
            }
        } else {
            Yii::$app->cache->flush();
            Yii::$app->session->setFlash('info', Yii::t('app', 'Wijzigingen zijn opgeslagen.'));
        }

        return $this->redirect(['open-vragen-antwoorden/index']);
    }

    public function actionAntwoordGoed($open_vragen_antwoorden_ID) {
        $model = $this->findModel($open_vragen_antwoorden_ID);
        $model->checked = 1;
        $model->correct = 1;
        if (!$model->save()) {
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan vraag niet beoordelen.') . ' ' . Json::encode($error));
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

    public function actionAntwoordFout($open_vragen_antwoorden_ID) {
        $model = $this->findModel($open_vragen_antwoorden_ID);
        $model->checked = 1;
        $model->correct = 0;
        if (!$model->save()) {
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan vraag niet beoordelen.') . ' ' . Json::encode($error));
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
    protected function findModel($id) {
        $model = OpenVragenAntwoorden::findOne([
                'open_vragen_antwoorden_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
