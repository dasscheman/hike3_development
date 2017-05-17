<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?php echo Yii::t('app', 'Kiwi.run is based on open source:') ?>
    </p>
    
    <p>
        <?= Yii::powered() ?>
    </p>
    <p>
        Illustrations by <a align="center" href="https://vecteezy.com">Vecteezy!</a>
    </p>
</div>
