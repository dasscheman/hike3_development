<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragenAntwoorden */

$this->title = $model->open_vragen_antwoorden_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Open Vragen Antwoordens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-open-vragen-antwoorden-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->open_vragen_antwoorden_ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->open_vragen_antwoorden_ID], [
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
            'open_vragen_antwoorden_ID',
            'open_vragen_ID',
            'event_ID',
            'group_ID',
            'antwoord_spelers',
            'checked',
            'correct',
            'create_time',
            'create_user_ID',
            'update_time',
            'update_user_ID',
        ],
    ]) ?>

</div>
