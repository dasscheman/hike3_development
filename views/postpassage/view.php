<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TblPostPassage */

$this->title = $model->posten_passage_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Post Passages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-post-passage-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->posten_passage_ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->posten_passage_ID], [
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
            'posten_passage_ID',
            'post_ID',
            'event_ID',
            'group_ID',
            'gepasseerd',
            'binnenkomst',
            'vertrek',
            'create_time',
            'create_user_ID',
            'update_time',
            'update_user_ID',
        ],
    ]) ?>

</div>
