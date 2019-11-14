<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\EventNames;
use app\models\BonuspuntenSearch;
use app\models\Qr;
use app\models\QrCheckSearch;
use app\models\DeelnemersEvent;
use app\models\Groups;
use app\models\Route;
use app\models\Routebook;
use app\models\RouteTrack;
use app\models\Posten;
use app\models\OpenNoodEnvelopSearch;
use app\models\OpenVragen;
use app\models\OpenVragenAntwoorden;
use app\models\OpenVragenAntwoordenSearch;
use app\models\OpenVragenSearch;
use app\models\NoodEnvelop;
use app\models\NoodEnvelopSearch;
use app\models\TimeTrailCheckSearch;
use yii\web\Cookie;
use yii\data\ActiveDataProvider;
use app\models\HikeActivityFeed;
use app\models\ExportImport;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'cookie' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error', 'about', 'contact', 'captcha', 'quick-start'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'overview', 'cookie', 'cache-flush'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['overview-players'],
                        'roles' => ['deelnemer', 'organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['overview-organisation'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => false, // deny all users
                        'roles' => ['*'],
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
                'fixedVerifyCode' => YII_ENV_TEST || YII_ENV_DEV ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isguest) {
            if (Yii::$app->user->identity->getDeelnemersEventsByUserID()->exists()) {
                Yii::$app->user->identity->setSelectedEventID();
            } else {
                Yii::$app->session->setFlash(
                    'warning',
                    Yii::t(
                        'app',
                        'Je bent niet ingeeschreven voor een hike. Als je er een organiseerd, maak er dan een hike aan.
                        Als je mee wilt doen aan een hike zoek dan een vriend die een hike organiseerd en vraag hem jou profiel aan de hike toe tevoegen.'
                    )
                );
                return $this->redirect(['/users/view']);
            }
            if (!empty(Yii::$app->user->identity->selected_event_ID)) {
                $event_id = Yii::$app->user->identity->selected_event_ID;
                $user = DeelnemersEvent::find()
                    ->where('event_ID =:event_id and user_ID =:user_id')
                    ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
                    ->one();
                if (!isset($user->rol)) {
                    return $this->render('/site/index');
                }
                if ($user->rol === DeelnemersEvent::ROL_deelnemer && !empty($user->group_ID)) {
                    return $this->redirect(['/site/overview-players']);
                }
                if ($user->rol === DeelnemersEvent::ROL_organisatie) {
                    return $this->redirect(['/site/overview-organisation']);
                }
            }
        }
        return $this->render('/site/index');
    }

    public function actionOverviewOrganisation()
    {
        if (!empty(Yii::$app->user->identity->selected_event_ID)) {
            $event_id = Yii::$app->user->identity->selected_event_ID;
            $this->setSiteIndexMessage($event_id);

            $eventModel = EventNames::find($event_id)
                ->where('event_ID =:event_id')
                ->addParams([':event_id' => $event_id])
                ->one();

            $eventModel->setDaterange();

            $queryOrganisatie = DeelnemersEvent::find()
                ->where(['=', 'event_ID', $event_id])
                ->andWhere(['!=', 'rol', DeelnemersEvent::ROL_deelnemer])
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
                'event_id' => Yii::$app->user->identity->selected_event_ID,
                'checked' => 0,
            ]);
            $dataProviderCheck = new ActiveDataProvider([
                'query' => $queryCheckQuestions
            ]);

            $routebookModel = Routebook::find()
                ->where('event_ID=:event_id')
                ->addParams([
                'event_id' => Yii::$app->user->identity->selected_event_ID,
            ]);

            $routeTrackModel = new RouteTrack;

            $routeModel = new Route;
            $timeTableData = $routeModel->timeTable();

            $feed = new HikeActivityFeed;
            $feed->pageSize = 10;

            return $this->render('/site/index-organisation', array(
                    'eventModel' => $eventModel,
                    'organisatieData' => $providerOrganisatie,
                    'groupsData' => $providerGroups,
                    'groupModel' => $groupModel,
                    'eventNames' => new EventNames,
                    'routebookModel' => $routebookModel->all(),
                    'dataProviderCheck' => $dataProviderCheck,
                    'activityFeed' => $feed->getData(),
                    'routeTrackModel' => $routeTrackModel,
                    'modelDeelnemer' => new DeelnemersEvent,
                    'importModel' => new ExportImport,
                    'timeTableData' => $timeTableData
            ));
        }
        return $this->render('/site/index');
    }

    public function actionOverviewPlayers()
    {
        $deelnemersEvent = new DeelnemersEvent();
        if (isset(Yii::$app->user->identity->selected_event_ID)) {
            $event_id = Yii::$app->user->identity->selected_event_ID;

            if (null !== Yii::$app->request->get('group_ID') &&
                $deelnemersEvent->getRolOfCurrentPlayerCurrentGame() === DeelnemersEvent::ROL_organisatie) {
                $group_id = Yii::$app->request->get('group_ID');
            } else {
                $temp = DeelnemersEvent::find()
                    ->select('group_ID')
                    ->where('event_ID =:event_id and user_ID =:user_id')
                    ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
                    ->one();
                $group_id = $temp->group_ID;
            }

            $searchBonusModel = new BonuspuntenSearch();
            $bonusData = $searchBonusModel->searchByGroup(Yii::$app->request->queryParams, $group_id);

            $groupModel = Groups::findOne($group_id);
            $groupModel->setGroupMembers();
            $routebook = new Routebook();
            $queryRoutebook = $routebook->getRoutebook();

            $routeModel = new Route;
            $timeTableData = $routeModel->timeTable();

            $searchTimeTrailCheckModel = new TimeTrailCheckSearch();
            $timeTrailCheckDataLastItem = $searchTimeTrailCheckModel->searchLastItem(Yii::$app->request->queryParams, $group_id);
            $timeTrailCheckData = $searchTimeTrailCheckModel->search(Yii::$app->request->queryParams, $group_id);


            return $this->render('index-players', [
                    'groupModel' => $groupModel,
                    'bonusData' => $bonusData,
                    'routebookModel' => $queryRoutebook->all(),
                    'timeTrailCheckData' => $timeTrailCheckData,
                    'timeTrailCheckDataLastItem' => $timeTrailCheckDataLastItem,
                    'timeTableData' => $timeTableData
            ]);
        }
        return $this->render('/site/index');
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->contact()) {
                Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->refresh();
            }
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionQuickStart()
    {
        return $this->render('quick-start');
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

        $this->layout = '/layouts/column2';
        $this->render('help');
    }

    protected function setSiteIndexMessage($event_id)
    {
        $model = EventNames::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => $event_id])
            ->one();

        if ($model->status == EventNames::STATUS_opstart) {
            Yii::$app->session->setFlash(
                'info',
                Yii::t(
                    'app',
                    'De hike heeft status \'Uitzetten\'.
                    De spelers kunnen nog niets van de hike zien.
                    Ze kunnen de verschillende onderdelen van de hike pas zien als de status \'Introdutie\' of \'Gestart\' is.'
                )
            );
        }

        $station = Posten::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => $model->event_ID])
            ->count();
        if ($station < 4) {
            Yii::$app->session->setFlash(
                'post',
                Yii::t(
                    'app',
                    'Je hebt geen of weinig posten, klik op het menu item
                    \'Kaarten\' om posten aan te maken.
                    LET OP! Je moet voor elke dag een startpost maken, die je moet gebruiken om een groepje te laten starten.
                    De startpost geef je 0 punten, tenzij je denkt dat het een prestatie is dat ze hike dag start.'
                )
            );
        }
    }

    public function actionCacheFlush()
    {
        Yii::$app->cache->flush();
        return $this->redirect(['/site/overview-organisation']);
    }

    public function actionCookie()
    {
        $screen_heigth = Yii::$app->request->post('screen_heigth');
        $cookies = Yii::$app->getResponse()->getCookies();
        $cookies->remove('screen_size');
        $cookie = new Cookie([
            'name' => 'screen_size',
            'value' => $screen_heigth,
            'expire' => time() + 86400 * 365,
        ]);
        $cookies->add($cookie);
    }
}
