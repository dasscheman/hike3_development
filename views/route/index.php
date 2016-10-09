<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblRouteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Routes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-route-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Route'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php
   $activeTab = Route::model()->getDefaultActiveTab($startDate);
	$count=0;



	while(strtotime($startDate) <= strtotime($endDate)) {
		if (Route::model()->isActionAllowed("route", "create", $_GET['event_id'])) {
			$newButton = CHtml::link(
					'<span class="fa-stack fa-lg">
						<td style="text-align:right;">
							<i class="fa fa-plus fa-stack-1x">Nieuw</i>
						</td>
					</span>',
						$this->createAbsoluteUrl(
							'create',
							array('event_id'=>$_GET['event_id'],
								'date'=>$startDate)));
		} else {
			$newButton = '';
		}
		// dit moet nog een vervangen worden door javascript.
		//Met de volgende code wordt de active tab onthouden.
		$active=false;
		if (isset($activeTab))
		{
			if ($activeTab==$startDate)
				$active=true;
		}
		else
		{
			if ($count==0)
				$active=true;
		}

	    $dataArray[$count]=array(
		    'label' =>$startDate,
		    'active'=>$active,
		    'content' =>$newButton
            .$this->widget(
		    	'bootstrap.widgets.TbGridView',
				array(
					'id'=>'route-grid',
					'dataProvider'=>$routeData->searchRoute($_GET['event_id'], $startDate),
					'columns'=>array(
                        array(
                            'header'=>'Route Onderdeel',
                            'value'=>'Route::model()->getRouteName($data->route_ID)'),
                        array(
                            'header'=>'#Vragen',
                            'value'=>'OpenVragen::model()->getNumberVragenRouteId($data->event_ID, $data->route_ID)'),
                        array(
                            'header'=>'#Hints',
                            'value'=>'NoodEnvelop::model()->getNumberNoodEnvelopRouteId($data->event_ID, $data->route_ID)'),
                        array(
                            'header'=>'#Stille posten',
                            'value'=>'Qr::model()->getNumberQrRouteId($data->event_ID, $data->route_ID)'),
                        array(
                            'header'=>'Aangemaakt',
                            'value'=>'Users::model()->getUserName($data->create_user_ID)'),
                       	array(
                            'header'=>'Laatst Bijgewerkt',
                            'value'=>'Users::model()->getUserName($data->update_user_ID)'),
                        'route_volgorde',
						array(
							'header'=>'Opties',
							'class'=>'CButtonColumn',
							'template'=>'{details}{omhoog}{omlaag}',
							'buttons'=>array(
								'details' => array(
									'label'=>'<span class="fa-stack fa-lg">
												<i class="fa fa-search fa-stack-1x"></i>
											  </span>',
									'options'=>array('title'=>'Bekijk deze hike'),
									'url'=>'Yii::app()->createUrl("route/view", array(
										"route_id"=>$data->route_ID,
										"event_id"=>$data->event_ID))',
								),
								'omhoog' => array(
									'label'=>'<span class="fa-stack fa-lg">
										<i class="fa fa-level-up fa-stack-1x"></i>
										</span>',
									'options'=>array('title'=>'Schuif omhoog'),
									'url'=>'Yii::app()->createUrl("/route/moveUpDown", array(
										"event_id"=>$data->event_ID,
										"date"=>$data->day_date,
										"up_down"=>"up",
										"route_id"=>$data->route_ID,
										"volgorde"=>$data->route_volgorde))',
                                    'visible'=>'Route::model()->isActionAllowed(
										"route",
										"moveUpDown",
                                        $data->event_ID,
										"",
										"",
                                        $data->day_date,
                                        $data->route_volgorde,
										"up")',
								),
								'omlaag' => array(
									'label'=>'<span class="fa-stack fa-lg">
										<i class="fa fa-level-down fa-stack-1x"></i>
										</span>',
									'options'=>array('title'=>'Schuif omlaag'),
									'url'=>'Yii::app()->createUrl("/route/moveUpDown", array(
										"event_id"=>$data->event_ID,
										"date"=>$data->day_date,
										"up_down"=>"down",
										"route_id"=>$data->route_ID,
										"volgorde"=>$data->route_volgorde))',
                                    'visible'=>'Route::model()->isActionAllowed(
										"route",
										"moveUpDown",
                                        $data->event_ID,
										"",
										"",
                                        $data->day_date,
                                        $data->route_volgorde,
										"down")',
								),
							),
						),
			    ),
			),
			true
		    ),
		//),
	    );
		$startDate = date('Y-m-d', strtotime($startDate. ' + 1 days'));
	    $count++;
		// more then 10 days is unlikly, therefore break.
		if ($count == 10) {
			break;
		}
	}

$this->widget('bootstrap.widgets.TbTabs', array(
	'tabs'=>$dataArray
    )
);
 ?>

    
    
    
    ?>

</div>
