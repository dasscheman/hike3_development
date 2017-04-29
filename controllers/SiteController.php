<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EventNames;
use app\models\RouteSearch;
use app\models\BonuspuntenSearch;
use app\models\QrCheckSearch;
use app\models\DeelnemersEvent;
use app\models\Groups;
use app\models\OpenVragenAntwoorden;
use app\models\OpenVragen;
use app\models\OpenVragenSearch;
use app\models\NoodEnvelopSearch;
use yii\web\Cookie;
use yii\data\ActiveDataProvider;
use app\models\HikeActivityFeed;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'overview', 'overview-players', 'overview-organisation'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions'=>['overview', 'overview-players', 'overview-organisation'],
                        'allow' => TRUE,
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        }
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionOverviewOrganisation()
    {
        if (!empty(Yii::$app->user->identity->selected)) {
            $event_id = Yii::$app->user->identity->selected;

            $eventModel = EventNames::find($event_id)
                ->where('event_ID =:event_id')
                ->addParams([':event_id' => $event_id])
                ->one();

            $queryOrganisatie = DeelnemersEvent::find()
                ->where(['=', 'event_ID', $event_id])
                ->andWhere(['<=', 'rol', DeelnemersEvent::ROL_post])
                ->orderby('rol ASC');

            $providerOrganisatie = new ActiveDataProvider([
                'query' => $queryOrganisatie,
                'pagination' => [
                    'pageSize' => 50,
                ],
            ]);
            $groupModel = new Groups;
            $queryGroups = Groups::find()
                ->where(['=', 'event_ID', $event_id])
                ->orderby('group_name ASC');
            $providerGroups = new ActiveDataProvider([
                'query' => $queryGroups,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            $queryCheckQuestions = OpenVragenAntwoorden::find()
                ->where('event_ID=:event_id and checked=:checked')
                ->addParams([
                    'event_id' => Yii::$app->user->identity->selected,
                    'checked' => 0,
                ]);
            $dataProviderCheck = new ActiveDataProvider([
                'query' => $queryCheckQuestions
            ]);

            $feed = new HikeActivityFeed;
            $feed->pageSize = 10;

    		return $this->render('/site/index-organisation', array(
                'eventModel' => $eventModel,
    			'organisatieData' => $providerOrganisatie,
    			'groupsData' => $providerGroups,
                'groupModel' => $groupModel,
                'dataProviderCheck' => $dataProviderCheck,
                'activityFeed' => $feed->getData(),
    		));
        }
        return $this->render('/site/index');
	}

    public function actionIndex()
    {
        if (!empty(Yii::$app->user->identity->selected)) {
            $event_id = Yii::$app->user->identity->selected;
            $user = DeelnemersEvent::find()
                ->where('event_ID =:event_id and user_ID =:user_id')
                ->params([':event_id' => Yii::$app->user->identity->selected, ':user_id' => Yii::$app->user->id])
                ->one();
            if(!isset($user->rol)) {
                return $this->render('/site/index');
            }
            if ($user->rol === DeelnemersEvent::ROL_deelnemer && !empty($user->group_ID)) {
                return $this->redirect(['/site/overview-players']);
            }
            if ($user->rol === DeelnemersEvent::ROL_organisatie) {
                return $this->redirect(['/site/overview-organisation']);
            }
        }

        if (!Yii::$app->user->isguest) {
            return $this->redirect(['/users/view']);
        }
        return $this->render('/site/index');

    }

    public function actionOverviewPlayers()
    {
        if (isset(Yii::$app->user->identity->selected)) {
            $event_id = Yii::$app->user->identity->selected;
            $group_id = DeelnemersEvent::find()
                ->select('group_ID')
                ->where('event_ID =:event_id and user_ID =:user_id')
                ->params([':event_id' => Yii::$app->user->identity->selected, ':user_id' => Yii::$app->user->id])
                ->one();

            $searchQuestionsModel = new OpenVragenSearch();
            $questionsData = $searchQuestionsModel->searchQuestionNotAnsweredByGroup(Yii::$app->request->queryParams);


            $searchHintsModel = new NoodEnvelopSearch();
            $hintsData = $searchHintsModel->searchNotOpenedByGroup(Yii::$app->request->queryParams);

            $searchBonusModel = new BonuspuntenSearch();
            $bonusData = $searchBonusModel->searchByGroup(Yii::$app->request->queryParams);

            $searchQrModel = new QrcheckSearch();
            $qrCheckData = $searchQrModel->searchByGroup(Yii::$app->request->queryParams);

            $groupModel = Groups::findOne($group_id);
            $groupModel->setGroupMembers();

            $feed = new HikeActivityFeed;
            $feed->pageSize = 5;
            $feed->pageCount = 3;

            return $this->render('index-players',[
                'groupModel' => $groupModel,
                'activityFeed' => $feed->getData(),
                'questionsData' => $questionsData,
                'hintsData' => $hintsData,
                'qrCheckData' => $qrCheckData,
                'bonusData' => $bonusData
            ]);
        }
        return $this->render('/site/index');
    }

    public function actionGameOverview()
    {
        $event_id = Yii::$app->user->identity->selected;
        $startDate=EventNames::getStartDate($event_id);
        $endDate=EventNames::getEndDate($event_id);

        $group_id = DeelnemersEvent::find()
            ->select('group_ID')
            ->where('event_ID =:event_id and user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':user_id' => Yii::$app->user->id])
            ->one();

        if(!isset($group_id->group_ID) || null === $group_id->group_ID) {
           return $this->render('index');
        }

        $searchModel = new RouteSearch();
        $queryParams = array_merge(array(),Yii::$app->request->getQueryParams());
        $queryParams["RouteSearch"]["event_ID"] = $event_id ;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('/game/overview',[
            'searchRouteModel' => $searchModel,
            'dataProvider'=>$dataProvider,
            'startDate'=>$startDate,
            'endDate'=>$endDate
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $last_login = $model->previous_login_time;
            Yii::$app->session->setFlash('success', Yii::t('app', 'Welcome ' . $model->username . '. Your last visit was on ' . $last_login));
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        $cookies = Yii::$app->getResponse()->getCookies();

        $cookies->remove('selected_event_ID');

        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionLanguage()
    {
        $language = Yii::$app->request->get('language');
        // TODO:
        $language = 'nl';

        Yii::$app->language = $language;
        $languageCookie = new Cookie([
            'name' => 'language',
            'value' => $language,
            'expire' => time() + 60 * 60 * 24 * 30, // 30 days
            ]);
        Yii::$app->response->cookies->add($languageCookie);
        return $this->render('index');
    }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionHelp()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		$this->layout='/layouts/column2';
		$this->render('help');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
//	public function actionError()
//	{
//		if($error=Yii::$app->errorHandler)
//		{
//			if(Yii::$app->request->isAjaxRequest)
//				echo $error['message'];
//			else
//				$this->render('error', $error);
//		}
//	}

//	/**
//	 * Displays the login page
//	 */
//	public function actionLogin()
//	{
//        //$this->layout='//layouts/column1';
//		$model=new LoginForm;
//
//		// if it is ajax validation request
//		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
//		{
//			echo Corm::validate($model);
//			Yii::app()->end();
//		}
//
//		// collect user input data
//		if(isset($_POST['LoginForm']))
//		{
//			$model->attributes=$_POST['LoginForm'];
//			// validate user input and redirect to the previous page if valid
//			if($model->validate() && $model->login())
//				$this->redirect(Yii::app()->user->returnUrl);
//		}
//		// display the login form
//		$this->render('login',array('model'=>$model));
//	}
//
//	/**
//	 * Logs out the current user and redirect to homepage.
//	 */
//	public function actionLogout()
//	{
//		Yii::app()->user->logout();
//		$this->redirect(Yii::app()->homeUrl);
//	}
}
