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
                'only' => ['index', 'create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => TRUE,
                    ],

                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'index-posten', 'update', 'delete', 'view', 'create'],
                'rules' => [
                    array(
                        'allow' => FALSE,  // deny all guest users
                        'roles' => array('?'),
                    ),
                    array(
                        'allow' => TRUE, // allow admin user to perform 'viewplayers' actions
                        'actions'=>array('index', 'index-posten', 'update', 'delete', 'view', 'create'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(NULL, NULL, ['group_ID' => Yii::$app->request->get('groups_ID')]);
                        }
                    ),
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ]
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
     * Displays a single Groups model.
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
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save changes to group.'));
        }

        // Group should be saved first to get a group_ID.
        // It is not a problem when group is saved and the save of the group members fail.
        // Because it is very easy to at them
        if (!Groups::addMembersToGroup($model->group_ID, Yii::$app->request->post('Groups')['users_temp'])) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save group members.'));
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
            $group_members = array();
            foreach ($model->deelnemersEvents as $item) {
                $model->users_temp[] = $item->user_ID;

            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }

        if (Yii::$app->request->post('submit') == 'delete' ||
            $model->load(Yii::$app->request->post())) {
            // Eerst verwijderen we alle leden van huidige group, om ze
            // vervolgens weer toe tevoegen. indien nodig
            $groups_leden = DeelnemersEvent::find()
                ->where(['group_ID' => $model->group_ID])
                ->andWhere(['event_ID' => Yii::$app->user->identity->selected])
                ->all();
            if ($groups_leden) {
                foreach ($groups_leden as $player) {
                    $player->delete();
                }
            }
        }

        if (Yii::$app->request->post('submit') == 'delete') {
            $model->delete();
            return $this->redirect(['site/overview']);
        }

        if (!Groups::addMembersToGroup($model->group_ID, Yii::$app->request->post('Groups')['users_temp'])) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save group members.'));
        }
        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save changes to group.'));
        }
        return $this->redirect(['site/overview']);
    }

	/**
	 * Lists all models.
	 */
	public function actionOverview()
	{
        //TODO dit moet anders, maar even kijken of deze functie nog gebruikt wordt.
//		$dataProvider=new CActiveDataProvider('Groups');
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//		));
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
        if (($model = Groups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
