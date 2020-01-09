<?php

namespace app\controllers;

use Yii;
use app\models\DeelnemersEvent;
use app\models\Posten;
use app\models\PostPassage;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PostPassageController implements the CRUD actions for PostPassage model.
 */
class PostPassageController extends Controller {

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
                        'allow' => FALSE,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['check-station','update', 'create'],
                        'matchCallback' => function ($rule, $action) {
                              $params = [
                                  'action' => Yii::$app->getRequest()->get('action'),
                                  'post_id' => Yii::$app->getRequest()->get('post_ID'),
                                  'group_id' => Yii::$app->getRequest()->get('group_ID'),
                              ];
                              return Yii::$app->user->can('organisatiePostCheck', $params);
                          }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['self-check'],
                        'roles' => ['deelnemerGestartTime'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['organisatieGestart'],
                    ],
                    [
                        'allow' => FALSE, // deny all users
                        'roles' => ['*'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Updates an existing PostPassage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCheckStation() {
        Yii::$app->cache->flush();
        $action = Yii::$app->request->get('action');
        $post_ID = Yii::$app->request->get('post_ID');
        $group_ID = Yii::$app->request->get('group_ID');

        if ($action === 'checkout') {
            $model = PostPassage::find()
                ->where('post_ID =:post_id AND group_ID =:group_id')
                ->params([':post_id' => $post_ID, ':group_id' => $group_ID])
                ->one();
        } else {
            $model = new PostPassage();
            $model->gepasseerd = 1;
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
            $model->post_ID = $post_ID;
            $model->group_ID = $group_ID;
        }
        if (Yii::$app->request->post('PostPassage') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->cache->flush();
                if ($action === 'start') {
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Gestart'));
                } else {
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Opgeslagen'));
                }
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_list-groups', [
                            'model' => $model,
                    ]);
                }
                return $this->redirect(['posten/index']);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('check-station', [
                    'model' => $model,
                    'action' => $action,
            ]);
        }
        return $this->render('check-station', [
                'model' => $model,
                'action' => $action,
        ]);
    }

    /**
     * Creates a new PostPassage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSelfCheck()
    {
        $post_code = Yii::$app->request->get('post_code');
        $even_id = Yii::$app->request->get('event_id');

        $now = date('Y-m-d H:i:s');

        $posten = Posten::find()
            ->where('event_ID =:event_id AND (incheck_code =:post_code OR uitcheck_code =:post_code ) ')
            ->params([
                ':event_id' => $even_id,
                ':post_code' => $post_code])
            ->one();


        if (!isset($posten->post_ID)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Dit is geen geldige post.'));
            return $this->redirect(['site/overview-players']);
        }
        $action = null;
        if($posten->incheck_code == $post_code) {
          $action = 'incheck';
        }
        if($posten->uitcheck_code == $post_code) {
          $action = 'uitcheck';
        }

        if ($posten->event_ID != Yii::$app->user->identity->selected_event_ID) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Deze post is niet voor huidige geselecteerde hike.'));
            return $this->redirect(['site/overview-players']);
        }

        $deelnemersEvent = new DeelnemersEvent;
        $groupPlayer = $deelnemersEvent->getGroupOfPlayer($posten->event_ID);

        if (!$groupPlayer) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Dit is geen groep gevonden.'));
            return $this->redirect(['site/index']);
        }

        if (isset($posten->start_datetime) && $posten->start_datetime > $now) {
          Yii::$app->session->setFlash('error', Yii::t('app', 'Deze postcode is nog niet open gestart.'));
          return $this->redirect(['site/overview-players']);
        }

        if (isset($posten->end_datetime) && $posten->end_datetime < $now) {
          Yii::$app->session->setFlash('error', Yii::t('app', 'Deze post is al afegelopen.'));
          return $this->redirect(['site/overview-players']);
        }

        $postenPassage = PostPassage::find()
            ->where('event_ID =:event_id AND post_ID =:post_id AND group_ID =:group_id')
            ->params([
                ':event_id' => $posten->event_ID,
                ':post_id' => $posten->post_ID,
                ':group_id' => $groupPlayer
            ])
            ->one();
        if($action == 'uitcheck') {
            if (!$postenPassage->gepasseerd || $postenPassage->binnenkomst == null ) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Je moet eerst inchecken om uit te kunnen checken.'));
                return $this->redirect(['site/overview-players']);
            }

            if ($postenPassage->vertrek !== null ) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Je bent al uigecheckt.'));
                return $this->redirect(['site/overview-players']);
            }
            $postenPassage->vertrek = $now;
            if ($postenPassage->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Uitgecheckt om ' . $now));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan niet uitchecken'));
            }
            return $this->redirect(['site/overview-players']);
        }

        if($action == 'incheck'){
            if (isset($postenPassage->posten_passage_ID) && ($postenPassage->gepasseerd || $postenPassage->binnenkomst != null )) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Je bent al ingecheckt op deze post.'));
                return $this->redirect(['site/overview-players']);
            }
            // Every thing is checked, now we can create the checked qr record.
            $model = new PostenPassage;
            $model->gepasseerd = 1;
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
            $model->post_ID = $posten->post_ID;
            $model->group_ID = $groupPlayer;

            if ($model->save()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Ingecheckt!'));
                return $this->redirect(['posten/index']);
            }
        }
        Yii::$app->session->setFlash('error', Yii::t('app', 'Er is iets mis gegaan!'));
        return $this->redirect(['site/overview-players']);
    }

    /**
     * Updates an existing PostPassage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $posten_passage_ID
     * @return mixed
     */
    public function actionUpdate($posten_passage_ID) {
        $model = $this->findModel($posten_passage_ID);
        $action = 'update';

        if (Yii::$app->request->post('update') == 'delete') {
            if ($model->delete()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'In/uit check verwijderd.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan in/uit check niet verwijderen.'));
            }
            return $this->redirect(['groups/index-posten']);
        }

        if (Yii::$app->request->post('PostPassage') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Wijzigingen opgeslagen.'));
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_list', ['model' => $model]);
                }
                return $this->redirect(['groups/index-posten']);
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
     * Finds the PostPassage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PostPassage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $model = PostPassage::findOne([
                'posten_passage_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
