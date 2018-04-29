<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsletterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Newsletter';
?>
<div class="newsletter-index">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?php echo $this->render('/_alert'); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Newsletter'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'subject',
            'body:ntext',
            'is_active' => [
                'attribute' => 'is_active',
                'filter'=> [0 => Yii::t('app', 'False'), 1 => Yii::t('app', 'True')],
                'value' => function ($model) {
                    if ($model->is_active == 0) {
                        return Yii::t('app', 'False');
                    }
                    return Yii::t('app', 'True');
                },
            ],
            'schedule_date_time',
            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>
</div>
