<?php

namespace app\controllers;

use Yii;
use app\models\Posten;
use app\models\PostenSearch;
use app\models\PostPassage;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use app\models\EventNames;

/**
 * PostenController implements the CRUD actions for Posten model.
 */
class PostenController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'ajaxupdate' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'map-update', 'move-up-down', 'lists-posts', 'ajaxupdate'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['organisatieOpstart', 'organisatieIntroductie'],
                    ],
                    [
                        'allow' => false, // deny all users
                        'roles' => ['*'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all Posten models.
     * @return mixed
     */
    public function actionIndex()
    {
        $eventNames = new EventNames();
        $event_Id = Yii::$app->user->identity->selected_event_ID;
        $startDate = $eventNames->getStartDate($event_Id);
        $endDate = $eventNames->getEndDate($event_Id);
        $searchModel = new PostenSearch();

        $this::setPostenIndexMessage($event_Id);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'startDate' => $startDate,
                'endDate' => $endDate
        ]);
    }

    /**
     * Creates a new Posten model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Posten();
        if (Yii::$app->request->post('Posten') &&
            $model->load(Yii::$app->request->post())) {
            $model->setNewOrderForPosten();
            if ($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Nieuwe post opgeslagen.'));
                return $this->redirect(['open-map/index']);
            } else {
                foreach ($model->getErrors() as $error) {
                    Yii::$app->session->setFlash('error', Json::encode($error));
                }
            }
        } else {
            $model->date = Yii::$app->request->get('date');
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
//            $this->setCookieIndexTab($model->date);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render([
                '/posten/create',
                'model' => $model
        ]);
    }

    /**
     * Without passing parameters this is used to determine what to do after a save.
     * When updating on the map page, the browser tab must be closed.
     *
     * @param type $post_ID
     * @return type
     */
    public function actionMapUpdate($post_ID)
    {
        return $this->actionUpdate($post_ID, true);
    }

    /**
     * Updates an existing Posten model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($post_ID, $map = null)
    {
        $model = $this->findModel($post_ID);
        if (Yii::$app->request->post('update') == 'delete') {
            $exist = PostPassage::find()
                ->where('event_ID=:event_id and post_ID=:post_ID')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':post_ID' => $model->post_ID
                ]
                )
                ->exists();
            if (!$exist && Yii::$app->user->can('organisatieOpstart')) {
                $model->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Post verwijderd.'));
            } else {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan post niet verwijderen, er is al een groep ingecheckt.'));
            }
            if ($map === true) {
                echo "<script>window.close(); window.opener.location.reload(true);</script>";
                return;
            }
            return $this->redirect(['posten/index']);
        }

        if (Yii::$app->request->post('Posten') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Wijzigingen opgeslagen'));

                if ($map === true) {
                    echo "<script>window.close(); window.opener.location.reload(true);</script>";
                    return;
                }
                return $this->redirect(['posten/index']);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', ['model' => $model]);
        }

        return $this->render(
                'update',
                ['model' => $model]
        );
    }

    /**
     * Finds the Posten model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Posten the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Posten::findOne([
                'post_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
//            $this->setCookieIndexTab($model->date);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMoveUpDown($post_ID, $up_down)
    {
        $model = $this->findModel($post_ID);
        if ($up_down === 'up') {
            $previousModel = Posten::find()
                ->where('event_ID =:event_id and date =:date and post_volgorde <:order')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':date' => $model->date, ':order' => $model->post_volgorde])
                ->orderBy('post_volgorde DESC')
                ->one();
        } elseif ($up_down === 'down') {
            $previousModel = Posten::find()
                ->where('event_ID =:event_id AND date =:date AND post_volgorde >:order')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':date' => $model->date, ':order' => $model->post_volgorde])
                ->orderBy('post_volgorde ASC')
                ->one();
        }

        // Dit is voor als er een reload wordt gedaan en er is geen previousModel.
        // Opdeze manier wordt er dan voorkomen dat er een fatal error komt.
        if (isset($previousModel)) {
            $tempCurrentVolgorde = $model->post_volgorde;
            $model->post_volgorde = $previousModel->post_volgorde;
            $previousModel->post_volgorde = $tempCurrentVolgorde;
            if ($model->validate() &&
                $previousModel->validate()) {
                $model->save();
                $previousModel->save();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan volgorde niet wijzigen.'));
            }
        }

        $event_Id = Yii::$app->user->identity->selected_event_ID;
        $eventNames = new EventNames;
        $startDate = $eventNames->getStartDate($event_Id);
        $endDate = $eventNames->getEndDate($event_Id);
        $searchModel = new PostenSearch();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('/posten/index', [
                    'searchModel' => $searchModel,
                    'startDate' => $startDate,
                    'endDate' => $endDate]);
        }

        return $this->render('/posten/index', [
                'searchModel' => $searchModel,
                'startDate' => $startDate,
                'endDate' => $endDate
        ]);
    }

    public function actionListsPosts()
    {
        $post_id = '';
        if (null !== Yii::$app->request->get('post_id')) {
            $post_id = Yii::$app->request->get('post_id');
        }

        $out = [];
        if (null !== Yii::$app->request->post('depdrop_parents')) {
            $parents = Yii::$app->request->post('depdrop_parents');
            if ($parents != null) {
                $date = $parents[0];
                $data = Posten::getPostNameOptionsToday($date);
                foreach ($data as $key => $item) {
                    $out[] = ['id' => $key, 'name' => $item];
                }
                echo Json::encode(['output' => $out, 'selected' => $post_id]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    protected function setPostenIndexMessage($event_id)
    {
        $posten = Posten::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => $event_id]);
        $event = EventNames::findOne($event_id);

        $datediff = strtotime($event->end_date) - strtotime($event->start_date);
        $days = Yii::$app->setupdatetime->convert($datediff, 'day');
        $tresholt = $days + 4;
        if ($posten->count() <= $tresholt) {
            Yii::$app->session->setFlash(
                'post',
                Yii::t(
                    'app',
                    'Hier zie je een overzicht van alle posten per dag.
                    Je moet voor elke dag een startpost maken, die je moet gebruiken om een groepje te laten starten.
                    De start post geef je 0 punten, tenzij je denkt dat het een prestatie is dat ze hike dag start.'
                )
            );
        }
    }

    public function actionAjaxupdate()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->latitude = Yii::$app->request->post('latitude');
        $model->longitude = Yii::$app->request->post('longitude');

        if ($model->save()) {
            return true;
        } else {
            foreach ($model->getErrors() as $error) {
                return Json::encode($error);
            }
        }
    }
}
