<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use prawee\widgets\ButtonAjax;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-nood-envelop-view">

    <h1><?= Html::encode(Yii::t('app', 'Hints for') . ' ' . $model->route_name) ?></h1>
    <?php

    $pages = new yii\data\Pagination(['pageSize' => 100]);
    $dataProvider = new yii\data\ArrayDataProvider([
        'allModels' => $model->noodEnvelops,
        'pagination' => $pages,
    ]);

    echo ListView::widget([
        'summary' => false,
        'pager' => false,
        'dataProvider' => $dataProvider,
        'itemView' => '/nood-envelop/_list',
        'emptyText' => Yii::t('app', 'There are no hints for this route section'),
    ]);
?>
</div>
