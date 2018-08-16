<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?php echo Yii::t('app', 'Hike-app is based on open source:') ?>
    </p>

    <p>
        <?= Yii::powered() ?>
    </p>
    <p>
        Illustrations by <a align="center" href="https://vecteezy.com">Vecteezy!</a>
    </p>
    <p>
        Icons by <a align="center" href="https://mapicons.mapsmarker.com/">Maps Icons Collection</a>
    </p>
    <h2><?= Html::encode('change log') ?></h2>
    <h3><?= Html::encode('3.2') ?></h3>
    <p>
        Added new user dektrium yii2-user and dektrium yii2-rbac.
    </p>
    <h3><?= Html::encode('3.1') ?></h3>
    <p>
        Added overview of activity per group.
    </p>
    <p>
        Added search for hints page.
    </p>
    <p>
        Added Export functionality. You can export a complete hike.
    </p>
    <p>
        Added Time Trail. This is comparible with the silent stations.
        But the qr code of a time trail have a time restriction. The player have
        to scan them within a certain time. Time trail can run over several day's.
    </p>
    <p>
        Added manuals. This is not complete and we are still experimenting with different formats.
    </p>
    <p>
        Implemented caching for optimization.
    </p>
    <p>
        Added the functionality to delete an hike.
    </p>
    <p>
        Added change log.
    </p>
    <p>
        Several small bugs.
    </p>
    <h3><?= Html::encode('3.0') ?></h3>
    <p>
        Complete rewrite in Yii2
    </p>
</div>
