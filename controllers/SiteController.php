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
use app\models\DeelnemersEvent;
use app\models\Groups;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actionIndex()
    {
        if (isset(Yii::$app->user->identity->selected)) {
            $event_id = Yii::$app->user->identity->selected;
            $startDate=EventNames::getStartDate($event_id);
            $endDate=EventNames::getEndDate($event_id);

            $group_id = DeelnemersEvent::find()
                ->select('group_ID')
                ->where('event_ID =:event_id and user_ID =:user_id')
                ->params([':event_id' => Yii::$app->user->identity->selected, ':user_id' => Yii::$app->user->id])
                ->one();
            $groupModel = Groups::findOne($group_id);

            if(!isset($group_id->group_ID) || null === $group_id->group_ID) {
               return $this->render('index');
            }

            $groupModel->setGroupMembers();
            $searchModel = new RouteSearch();
            $queryParams = array_merge(array(),Yii::$app->request->getQueryParams());
            $queryParams["RouteSearch"]["event_ID"] = $event_id ;
            $dataProvider = $searchModel->search($queryParams);

            return $this->render('/game/overview',[
                'groupModel' => $groupModel,
                'searchRouteModel' => $searchModel,
                'dataProvider'=>$dataProvider,
                'startDate'=>$startDate,
                'endDate'=>$endDate
            ]);
        }
        return $this->render('index');
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
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Dank voor de mail.');
				$this->refresh();
			}
		}
		$this->layout='//layouts/column2fb';
		$this->render('contact',array('model'=>$model));
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionLanguage()
    {
        $language = Yii::$app->request->get('language');
        Yii::$app->language = $language;

//        $languageCookie = new Cookie([
//            'name' => 'language',
//            'value' => $language,
//            'expire' => time() + 60 * 60 * 24 * 30, // 30 days
//        ]);
//        Yii::$app->response->cookies->add($languageCookie);
        
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
//			echo CActiveForm::validate($model);
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