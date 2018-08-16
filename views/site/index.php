<?php

use app\components\CustomAlertBlock;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Hike-app';
?>

<div class="site-index">
    <?php
        echo CustomAlertBlock::widget([
            'type' => CustomAlertBlock::TYPE_ALERT,
            'useSessionFlash' => true,
            'delay' => FALSE,
        ]);
    ?>
    <div class="jumbotron">
        <h1><?php echo Yii::t('app', 'Welcome') ?></h1>
		<h3>Dit is de Hike-app</h3>
        <p>
            <?php
            echo Html::a(
                    Yii::t('app', 'Create new hike-app account'),
                    ['/user/registration/register'],
                    ['class' => 'btn btn-xl btn-success']
                );?>
        </p>
        <p>
            <?php
            echo Html::a(
                    Yii::t('app', 'Or login if you have an account'),
                    ['/user/security/login']
                );?>
        </p>
        <p>
            <?php
            echo Html::a(
                Yii::t('app', 'Forgot password'),
                ['/user/recovery/request']
            );?>
        </p>
    </div>
</div>
