<?php

namespace app\controllers\user;

use Yii;
use dektrium\user\controllers\AdminController as BaseAdminController;
use yii\filters\AccessControl;
use app\models\Users;
use yii\filters\VerbFilter;
use dektrium\user\filters\AccessRule;

class AdminController extends BaseAdminController {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                // We will override the default rule config with the new AccessRule class
                'only' => ['index', 'update', 'update-profile', 'create', 'info'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'update-profile', 'create', 'info'],
                        'roles' => ['admin', 'beheerder'],
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var User $user */
        $user = new Users();
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);
        if ($user->load(\Yii::$app->request->post()) && $user->create()) {
            Yii::$app->db->createCommand()->insert('auth_assignment',
                [
                    'user_id' =>  $user->id,
                    'item_name' => 'gebruiker'
                ])
                ->execute();
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been created'));
            
            $this->trigger(self::EVENT_AFTER_CREATE, $event);
            return $this->redirect(['update', 'id' => $user->id]);
        }

        return $this->render('create', [
            'user' => $user,
        ]);
    }

}
