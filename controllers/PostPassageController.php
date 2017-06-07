<?php

namespace app\controllers;

use Yii;
use app\models\PostPassage;
use app\models\PostPassageSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
                'only' => ['update', 'start', 'checkin', 'checkout'],
                'rules' => [
                    [
                        'allow' => FALSE,
                        'roles'=> ['?'],
                    ],
                    [
                        'allow' => TRUE,
                        'actions'=> ['check-station'],
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                [
                                    'post_ID' => Yii::$app->request->get('post_ID'),
                                    'group_ID' => Yii::$app->request->get('group_ID'),
                                    'action' => Yii::$app->request->get('action')
                                ]);
                        }
                    ],
                    [
                        'allow' => TRUE,
                        'actions'=> ['update'],
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                [
                                    'posten_passage_ID' => Yii::$app->request->get('posten_passage_ID'),
                                ]
                            );
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
     * Updates an existing PostPassage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCheckStation()
    {
        // d('checkstation');
        // dd(Yii::$app->request->get());
        $action = Yii::$app->request->get('action');
        $post_ID = Yii::$app->request->get('post_ID');
        $group_ID = Yii::$app->request->get('group_ID');

        if($action === 'checkout') {
            $model = PostPassage::find()
                ->where('post_ID =:post_id AND group_ID =:group_id')
                ->params([':post_id' => $post_ID, ':group_id' => $group_ID])
                ->one();
        } else {
            $model = new PostPassage();
            $model->gepasseerd = TRUE;
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
            $model->post_ID = $post_ID;
            $model->group_ID = $group_ID;
        }
        if (Yii::$app->request->post('PostPassage') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if($action === 'start') {
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Started'));
                } else {
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Saved'));
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
            return $this->renderAjax('_form', [
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
     * Updates an existing PostPassage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $posten_passage_ID
     * @return mixed
     */
    public function actionUpdate($posten_passage_ID)
    {
        $model = $this->findModel($posten_passage_ID);
        $action = 'update';
        if (Yii::$app->request->post('PostPassage') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved changes.'));
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
    protected function findModel($id)
    {
        if (($model = PostPassage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
