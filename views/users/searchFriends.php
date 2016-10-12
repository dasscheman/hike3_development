<?php




/* @var $this UsersController */
/* @var $model Users */

?>

<h1>Vrienden zoeken</h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'showOnEmpty'=>false,
    'summary'=>'',
    'showFooter'=>false,
    'showHeader' => false,
    'bordered' => false,
    'striped' => false,
    'hover' => true,
    //'options' => ['class' => 'grid-view'],
    //'layout' => "{summary}\n{items}\n{pager}",

    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        //'nome',
        'descricao',
        'data',
        // No model -> getImageurl()
        [
            'label' => '',
            'format' => 'raw',
            'value'=> function($data) { return Html::a(Html::img($data->imageurl, ['width'=>'300', 'height' => "170"]), $data->foto); },
        ],
        [
            'label' => '',
            'format' => 'raw',
            'value'=> function($data) { return Html::a(Html::img($data->imageurl2, ['width'=>'300', 'height' => "170"]), $data->foto2); },
        ],

        ['class' => 'yii\grid\ActionColumn', 'template' => ''],
    ],
]); ?>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'users-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'user_ID',
		'username',
		'voornaam',
		'achternaam',
		'organisatie',
		//'email',
		'last_login_time',
		array(
			'header'=>'Uitnodigen',
			'class'=>'CButtonColumn',
			'template'=>'{connect}',
			'buttons'=>array
			(
				'connect' => array
				(
					'label'=>'
						<span class="fa-stack fa-lg">
							<i class="fa fa-check fa-stack-1x"></i>
						</span>',
                    'options'=>array('title'=>'Nodig uit om vrienden te worden.'),
					'url'=>'Yii::app()->createUrl("friend-list/connect", array("user_id"=>$data->user_ID))',
				),
			),
		),
	),
)); ?>
