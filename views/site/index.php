<?php

use app\components\CustomAlertBlock;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Kiwi.run';
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

        <p>
            <?php
            echo Html::a(
                    Yii::t('app', 'Create new kiwi.run account'),
                    ['/users/create'],
                    ['class' => 'btn btn-xl btn-success']
                );?>
        </p>
        <p>
            <?php
            echo Html::a(
                    Yii::t('app', 'Or login if you have an account'),
                    ['/site/login']
                );?>
        </p>
    </div>
</div>
