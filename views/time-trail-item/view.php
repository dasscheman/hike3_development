<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrailItem */

$this->title = Yii::t('app', 'Time Trail Item');
?>
<div class="tbl-time-trail-item-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php

    echo Html::img(Url::to(['time-trail-item/qrcode', 'code' => $model->code]));

    ?>
</div>