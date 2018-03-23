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
use yii\web\Cookie;

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
        $event_Id = Yii::$app->user->identity->selected_event_ID;
        $startDate = EventNames::getStartDate($event_Id);
        $endDate = EventNames::getEndDate($event_Id);
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
                Yii::$app->session->setFlash('info', Yii::t('app', 'Saved new station.'));
                return $this->redirect(['map/index']);
            } else {
                foreach ($model->getErrors() as $error) {
                    Yii::$app->session->setFlash('error', Json::encode($error));
                }
            }
        } else {
            $model->date = Yii::$app->request->get(date);
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
            $this->setCookieIndexTab($model->date);
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
            if (!$exist) {
                $model->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted station.'));
            } else {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete station, it is already awnseredby at least one group.'));
            }
            if ($map === true) {
                echo "<script>window.close(); window.opener.location.reload(true);</script>";
                return;
            }
            return $this->redirect(['route/index']);
        }

        if (Yii::$app->request->post('Posten') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved changes to station.'));

                if ($map === true) {
                    echo "<script>window.close(); window.opener.location.reload(true);</script>";
                    return;
                }
                return $this->redirect(['route/index']);
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
            $this->setCookieIndexTab($model->date);
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
                Yii::$app->session->setFlash('error', Yii::t('app', 'Cannot change order.'));
            }
        }

        $event_Id = Yii::$app->user->identity->selected_event_ID;
        $startDate = EventNames::getStartDate($event_Id);
        $endDate = EventNames::getEndDate($event_Id);
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





        $event_id = $_GET['event_id'];
        $post_id = $_GET['post_id'];
        $date = $_GET['date'];
        $post_volgorde = $_GET['volgorde'];
        $up_down = $_GET['up_down'];

        $currentModel = Posten::findByPk($post_id);

        $criteria = new CDbCriteria;

        if ($up_down == 'up') {
            $criteria->condition = 'event_ID =:event_id AND date =:date AND post_volgorde <:order';
            $criteria->params = array(':event_id' => $event_id, ':date' => $date, ':order' => $post_volgorde);
            $criteria->order = 'post_volgorde DESC';
        }
        if ($up_down == 'down') {
            $criteria->condition = 'event_ID =:event_id AND date =:date AND post_volgorde >:order';
            $criteria->params = array(':event_id' => $event_id, ':date' => $date, ':order' => $post_volgorde);
            $criteria->order = 'post_volgorde ASC';
        }
        $criteria->limit = 1;
        $previousModel = Posten::findAll($criteria);

        $tempCurrentVolgorde = $currentModel->post_volgorde;
        $currentModel->post_volgorde = $previousModel[0]->post_volgorde;
        $previousModel[0]->post_volgorde = $tempCurrentVolgorde;

        $currentModel->save();
        $previousModel[0]->save();

        $startDate = EventNames::getStartDate($event_id);
        $endDate = EventNames::getEndDate($event_id);

        $this->redirect(array('/posten/index',
            'event_id' => $event_id,
            'date' => $date));
    }

    public function actionListsPosts()
    {
        $out = [];
        if (null !== Yii::$app->request->post('depdrop_parents')) {
            $parents = Yii::$app->request->post('depdrop_parents');
            if ($parents != null) {
                $date = $parents[0];
                $data = Posten::getPostNameOptionsToday($date);
                foreach ($data as $key => $item) {
                    $out[] = ['id' => $key, 'name' => $item];
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
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
        $days = Yii::$app->setupdatetime->convert($datediff, 'days');
        $tresholt = $days + 4;
        if ($posten->count() <= $tresholt) {
            Yii::$app->session->setFlash(
                'post',
                Yii::t(
                    'app',
                    'Here you can create stations for each day.
                  For each day an start station is made, you have to use this when you want a group to start.
                  The start station should have a score of 0, unless you think starting your hike is a challange on it self and deserves points.'
                )
            );
        }
    }

    public function setCookieIndexTab($date)
    {
        $cookies = Yii::$app->getResponse()->getCookies();
        $cookies->remove('posten_day_tab');
        $cookie = new Cookie([
            'name' => 'posten_day_tab',
            'value' => $date,
            'expire' => time() + 86400 * 365,
        ]);
        $cookies->add($cookie);
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
