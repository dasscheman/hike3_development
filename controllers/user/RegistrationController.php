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
use dektrium\user\controllers\RegistrationController as Controller;
use app\models\Users;
use dektrium\user\Finder;
use dektrium\user\Mailer;
use dektrium\user\models\Token;

class RegistrationController extends Controller {

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param array            $config
     */
    public function __construct($id, $module, $config = []) {
        parent::__construct($id, $module, new Finder, $config);
    }

    /** @inheritdoc */
    public function actionRegister() {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }

        $model = new Users();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->redirect(['/user/security/login']);
        }

        return $this->render('register', [
                'model' => $model,
                'module' => $this->module,
        ]);
    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code) {
        $model = new Users;
        $user = $model->findIdentity($id);

        if ($user === null || $this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        if ($user->attemptConfirmation($code)) {
            return $this->redirect(['/user/security/login']);
        }

        return $this->redirect(['/user/registration/resend']);
    }

    /**
     * Displays page where user can request new confirmation token. If resending was successful, displays message.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionResend() {
        if ($this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        $model = new Users;
        if ($model->load(\Yii::$app->request->post())) {

            $user = $model->findUserByEmail($model->email);
            if (!$user->isConfirmed) {
                /** @var Token $token */
                $token = \Yii::createObject([
                        'class' => Token::className(),
                        'user_id' => $user->id,
                        'type' => Token::TYPE_CONFIRMATION,
                ]);
                $token->save(false);
                $mailer = new Mailer;
                $mailer->sendConfirmationMessage($user, $token);

                Yii::$app->session->setFlash('info', Yii::t(
                        'user', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'
                ));
            } else {
                Yii::$app->session->setFlash('info', Yii::t(
                        'user', 'Account is already confirmed.'
                ));
            }
            return $this->redirect(['/user/security/login']);
        }

        return $this->render('resend', [
                'model' => $model,
        ]);
    }

}
