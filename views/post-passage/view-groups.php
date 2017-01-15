<?php

use prawee\widgets\ButtonAjax;
use yii\widgets\ListView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblPostPassage */

$this->title = Yii::t('app', 'Stations passed by') . ' ' . $model->group_name;
?>
<div class="tbl-post-passage-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php

    $dataProvider = new yii\data\ArrayDataProvider([
        'allModels' => $model->postPassages,
    ]);

    yii\widgets\Pjax::begin(['id' => 'post-passage-view', 'enablePushState' => TRUE]);

    echo ListView::widget([
        'summary' => FALSE,
        'pager' => FALSE,
        'dataProvider' => $dataProvider,
        'itemView' => '/post-passage/_list',
        'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
    ]);

    yii\widgets\Pjax::end();
?>
</div>