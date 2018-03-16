<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign up');
echo $this->render('/_alert', ['module' => Yii::$app->getModule('user')]);
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'registration-form',
                    'enableAjaxValidation' => FALSE,
                    'enableClientValidation' => false,
                ]); ?>

                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'voornaam') ?>
                <?= $form->field($model, 'achternaam') ?>
                <?= $form->field($model, 'organisatie') ?>
                <?= $form->field($model, 'birthdate')->input(Form::INPUT_WIDGET, [
                    'options' => [
                        'placeholder' => Yii::t('app', 'dd-mm-yyyy'),
                        'value'=> isset($model->birthdate)? Yii::$app->setupdatetime->displayFormat($model->birthdate, 'date'): '',
                    ],
                    'pluginOptions' => [
                         'format' => 'dd-mm-yyyy',
                         'todayHighlight' => true
                     ]
                ])?>

                <?php if ($module->enableGeneratingPassword == false): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                <?php endif ?>

                <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <p class="text-center">
            <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
        </p>
    </div>
</div>
