<?php

namespace app\controllers;

use Yii;
use app\models\QrCheck;
use app\models\QrCheckSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * QrCheckController implements the CRUD actions for QrCheck model.
 */
class QrCheckController extends Controller
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
                'only' => ['viewPlayers', 'update', 'index', 'delete', 'create'],
                'rules' => [
                    array(
                        'allow' => FALSE,
                        'roles'=>array('?'),
                    ),
                    array(
                        'allow' => TRUE,
                        'actions'=>array('viewPlayers', 'index', 'delete', 'create', 'update'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(NULL, NULL, ['qr_check_ID' => Yii::$app->request->get('id')]);
                        }
                    ),
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all QrCheck models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QrCheckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QrCheck model.
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
     * Creates a new QrCheck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $qr_code = $_GET['qr_code'];
        $event_id = $_GET['event_id'];
        $groupPlayer = DeelnemersEvent::getGroupOfPlayer($event_id,
                                      Yii::app()->user->id);

        $model=new QrCheck;

        $qr = Qr::find('event_ID =:event_id AND
                     qr_code =:qr_code',
                  array(':event_id' => $event_id,
                        ':qr_code'  => $qr_code));
        if (!isset($qr->qr_code)){
            throw new CHttpException(403,"Ongeldige QR code.");
        }

        if (Route::getDayOfRouteId($qr->route_ID) <> EventNames::getActiveDayOfHike($event_id)){
            throw new CHttpException(403,"Deze Qr code is niet voor vandaag...");
        }

        $qrCheck = QrCheck::find('event_ID =:event_id AND qr_ID =:qr_id AND group_ID =:group_id',
            [
                ':event_id' => $qr->event_ID,
                ':qr_id'  => $qr->qr_ID,
                ':group_id'  => $groupPlayer
            ]);
        if (isset($qrCheck->qr_check_ID)){
            throw new CHttpException(403,"Jullie groep heeft deze code al gescand");
        }

        $model->qr_ID = $qr->qr_ID;
        $model->event_ID = $qr->event_ID;
        $model->group_ID = $groupPlayer;

        if($model->save())
            $this->redirect(
                [
                    'viewPlayers',
                    'event_id'=>$model->event_ID,
                    'group_id'=>$model->group_ID
                ]
            );
    }

    /**
     * Updates an existing QrCheck model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->qr_check_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing QrCheck model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
    * Displays a particular model.
    * @param integer $id the ID of the model to be displayed
    */
    public function actionViewPlayers()
    {
        $event_id = $_GET['event_id'];
        $group_id = $_GET['group_id'];

        //$day_id = EventNames::model()->getActiveDayOfHike($event_id);

        $where = "event_ID = $event_id AND group_ID = $group_id";
        $qrCheckDataProvider=new CActiveDataProvider('QrCheck',
            array(
            'criteria'=>array(
                'condition'=>$where,
                'order'=>'qr_check_ID DESC',
                ),
            'pagination'=>array(
                'pageSize'=>40,
            ),
        ));

        return $this->render('viewPlayers',array(
            'qrCheckDataProvider'=>$qrCheckDataProvider,
        ));
    }

    /**
     * Finds the QrCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QrCheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QrCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
