<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TblDeelnemersEvent */

$this->title = $model->deelnemers_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Deelnemers Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-deelnemers-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'deelnemers_ID' => $model->deelnemers_ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'deelnemers_ID' => $model->deelnemers_ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'deelnemers_ID',
            'event_ID',
            'user_ID',
            'rol',
            'group_ID',
            'create_time',
            'create_user_ID',
            'update_time',
            'update_user_ID',
        ],
    ]) ?>

</div>
