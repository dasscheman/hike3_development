<?php

namespace app\controllers;

use Yii;
use app\models\Groups;
use app\models\GroupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\DeelnemersEvent;
use app\models\HikeActivityFeed;

/**
 * GroupsController implements the CRUD actions for Groups model.
 */
class GroupsController extends Controller
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
                        'actions' => ['index', 'index-posten'],
                        'roles' => ['deelnemer', 'organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'index-activity', 'update'],
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
     * Lists all Groups models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Groups models.
     * @return mixed
     */
    public function actionIndexActivity()
    {
        $feed = new HikeActivityFeed;
        return $this->render('index-activity', [
                'activityFeed' => $feed->getLastGroupsActivity(),
        ]);
    }

    /**
     * Lists all Groups models.
     * @return mixed
     */
    public function actionIndexPosten()
    {
        $searchModel = new GroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-posten', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Groups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Groups();

        if (!$model->load(Yii::$app->request->post())) {
            return $this->renderPartial('_form', [
                    'model' => $model,
            ]);
        }

        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Kan wijzigingen niet opslaan'));
            return $this->redirect(['site/overview-organisation']);
        }

        // Group should be saved first to get a group_ID.
        // It is not a problem when group is saved and the save of the group members fail.
        // Because it is very easy to add them
        $errors = false;
        if (!$model->addMembersToGroup($model->group_ID, Yii::$app->request->post('Groups')['users_temp'])) {
            $errors = true;
        }

        if (!$model->addEmailsToGroup($model->group_ID, Yii::$app->request->post('Groups')['users_email_temp'])) {
            $errors = true;
        }
        if (!$errors) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Groep {groupname} aangemaakt.', ['groupname' => $model->group_name]));
        }
        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Updates an existing Groups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($group_ID)
    {
        $model = $this->findModel($group_ID);
        if (!$model->load(Yii::$app->request->post())) {
            foreach ($model->deelnemersEvents as $item) {
                $model->users_temp[] = $item->user_ID;
            }
            return $this->render('update', [
                    'model' => $model,
            ]);
        }

        $groups_leden = DeelnemersEvent::find()
            ->where(['group_ID' => $model->group_ID])
            ->andWhere(['event_ID' => Yii::$app->user->identity->selected_event_ID])
            ->all();
        if (Yii::$app->request->post('action') == 'update' ||
            $model->load(Yii::$app->request->post())) {
            // Eerst verwijderen we alle leden van huidige group, om ze
            // vervolgens weer toe tevoegen. indien nodig
            if ($groups_leden) {
                foreach ($groups_leden as $player) {
                    if (array_search($player->user_ID, Yii::$app->request->post('Groups')['users_temp']) !== false) {
                        continue;
                    }
                    try {
                        $player->delete();
                    } catch (\yii\db\IntegrityException $e) {
                        throw new \yii\web\ForbiddenHttpException('Je kunt deze speler niet verwijderen.');
                    }
                }
            }
        }

        if (Yii::$app->request->post('action') == 'delete') {
            if ($groups_leden) {
                foreach ($groups_leden as $player) {
                    try {
                        $player->delete();
                    } catch (\yii\db\IntegrityException $e) {
                        throw new \yii\web\ForbiddenHttpException('Je kunt deze speler niet verwijderen.');
                    }
                }
            }
            try {
                $model->delete();
            } catch (\yii\db\IntegrityException $e) {
                throw new \yii\web\ForbiddenHttpException('Je kunt deze groep niet verwijderen.');
            }
            Yii::$app->session->setFlash('info', Yii::t('app', 'Groep {group} is van de hike verwijderd', ['group' => $model->group_name]));
            return $this->redirect(['site/overview-organisation']);
        }

        $errors = false;
        if (!$model->addMembersToGroup($model->group_ID, Yii::$app->request->post('Groups')['users_temp'])) {
            $errors = true;
        }

        if (!$model->addEmailsToGroup($model->group_ID, Yii::$app->request->post('Groups')['users_email_temp'])) {
            $errors = true;
        }
        if (!$errors) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Groep {groupname} is gewijzigd.', ['groupname' => $model->group_name]));
        }
        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Groups::findOne([
                'group_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
