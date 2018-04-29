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
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use app\models\Users;
use dektrium\user\controllers\RecoveryController as Controller;
use dektrium\user\Finder;
use dektrium\user\helpers\Password;
use dektrium\user\Mailer;
use dektrium\user\models\Token;

/**
 * RecoveryController manages password recovery process.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RecoveryController extends Controller
{

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param array            $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, new Finder, $config);
    }

    /**
     * Shows page where user can request password recovery.
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRequest()
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        $model = new Users;
        if ($model->load(Yii::$app->request->post())) {
            $user = $model->findUserByEmail($model->email);
            if ($user !== null) {
                /** @var Token $token */
                $token = \Yii::createObject([
                        'class' => Token::className(),
                        'user_id' => $user->id,
                        'type' => Token::TYPE_RECOVERY,
                ]);

                $token->save(false);

                $mailer = new Mailer;
                $mailer->sendRecoveryMessage($user, $token);

                Yii::$app->session->setFlash('info', Yii::t(
                        'user',
                    'An email has been sent with instructions for resetting your password'
                ));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t(
                        'user',
                    'Unknown email'
                ));
            }
        }

        return $this->render('request', [
                'model' => $model,
        ]);
    }

    /**
     * Displays page where user can reset password.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset($id, $code)
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        $model = Users::findOne($id);
        /** @var Token $token */
        $finder = Yii::createObject(Finder::className());

        $token = $finder->findToken(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();

        if (empty($token) || !$token instanceof Token) {
            throw new NotFoundHttpException();
        }

        if ($token === null || $token->isExpired || $token->user === null) {
            Yii::$app->session->setFlash(
                'danger',
                Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.')
            );
            return $this->redirect(['/user/security/login']);
        }

        if (Yii::$app->getRequest()->post()) {
            $model = Users::findOne($token->user_id);
            if ($model->load(Yii::$app->getRequest()->post())) {
                $model->password_hash = Password::hash($model->password);
                if ($model->save()) {
                    Yii::$app->session->setFlash('info', Yii::t(
                        'user',
                        'Password has been changed'
                    ));
                } else {
                    foreach ($model->getErrors() as $error) {
                        Yii::$app->session->setFlash('warning', Yii::t(
                        'user',
                        'Password cannot be changed: ' . Json::encode($error)
                    ));
                    }
                }
                return $this->redirect(['/user/security/login']);
            }
        }

        return $this->render('reset', [
                'model' => $model,
        ]);
    }
}
