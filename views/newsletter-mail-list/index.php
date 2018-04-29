<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsletterMailListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Newsletter Mail Lists');
?>
<div class="newsletter-mail-list-index">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?php echo $this->render('/_alert'); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'newsletter.subject',
            'user_id',
            'email:email',
            'create_time',
            'send_time',
            'is_sent' => [
                'attribute' => 'is_sent',
                'filter'=> [0 => Yii::t('app', 'False'), 1 => Yii::t('app', 'True')],
                'value' => function ($model) {
                    if ($model->is_sent == 0) {
                        return Yii::t('app', 'False');
                    }
                    return Yii::t('app', 'True');
                },
            ],
            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
