<?php

namespace app\controllers;

use Yii;
use app\models\Newsletter;
use app\models\NewsletterMailList;
use app\models\NewsletterSearch;
use app\models\Users;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;

/*
 * NewsletterController implements the CRUD actions for Newsletter model.*
 */
class NewsletterController extends Controller
{

    /**
     * @inheritdoc
     */
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
                        'actions' => ['unsubscribe', 'subscribe'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'index', 'delete'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin ;
                        }
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
     * Lists all Newsletter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsletterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Campaigns model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Newsletter();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $users = Users::find()
                    ->where(['newsletter' => true])
                    ->andWhere('ISNULL(blocked_at)')
                    ->all();

                foreach ($users as $user) {
                    $mail_list = new NewsletterMailList();
                    $mail_list->newsletter_id = $model->id;
                    $mail_list->user_id = $user->id;
                    $mail_list->email = $user->email;
                    $mail_list->send_time = null;
                    $mail_list->is_sent = '0';
                    if (!$mail_list->save()) {
                        foreach ($model->getErrors() as $error) {
                            Yii::$app->session->setFlash('error', Json::encode($error));
                        }
                    }
                }

                Yii::$app->session->setFlash('success', 'Campaign created successfully.');

                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', 'Unable to create campaign.');
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Newsletter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
//            $model->schedule_date_time = Yii::$app->formatter->asDatetime(strtotime($model->schedule_date_time), "php:Y-m-d H:i:s");
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Newsletter created successfully.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', 'Unable to create newsletter.');
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Newsletter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $newsletter = $this->findModel($id);

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($newsletter->getNewsletterMailLists()->all() as $mail) {
                if ($mail->is_sent == true) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'This newsletter is already sent to at least some users, set field "active" on false to avoid further sending.'));
                    $dbTransaction->rollBack();
                } else {
                    $mail->delete();
                }
            }
            if (!$newsletter->delete()) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Could not delete this newsletter.'));
                $dbTransaction->rollBack();
                return;
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Could not delete this newsletter: ' .  $e));
        }

        return $this->redirect(['index']);
    }

    public function actionUnsubscribe($user_id, $email)
    {
        $user = Users::findOne($user_id);

        if ($user->email === $email) {
            $user->newsletter = false;
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Unsubscribed for newsletters.');
            } else {
                Yii::$app->session->setFlash('warning', 'Could not change user settings.');
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Unknown user credentials');
        }
        return $this->render('unsubscribe');
    }

    public function actionSubscribe($user_id, $email)
    {
        $user = Users::findOne($user_id);

        if ($user->email === $email) {
            $user->newsletter = true;
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Aangemeld voor nieuwsbrief.');
            } else {
                Yii::$app->session->setFlash('warning', 'Kan wijzigingen niet opslaan.');
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Onbekende gebruiker.');
        }
        return $this->render('unsubscribe');
    }

    /**
     * Finds the Newsletter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Newsletter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Newsletter::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
