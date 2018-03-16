<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\controllers\user;

use Yii;
use dektrium\user\controllers\SecurityController as Controller;
use app\models\Users;
use dektrium\user\Finder;
use dektrium\user\Mailer;
use dektrium\user\models\Token;
use yii\web\NotFoundHttpException;
use dektrium\user\helpers\Password;
use app\models\LoginForm;

/**
 * Controller that manages user authentication process.
 *
 * @property Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SecurityController extends Controller {

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param array            $config
     */
    public function __construct($id, $module, $config = []) {
        parent::__construct($id, $module, new Finder, $config);
    }

    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = Yii::createObject(LoginForm::className());

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
                'model' => $model,
                'module' => $this->module,
        ]);
    }

}
