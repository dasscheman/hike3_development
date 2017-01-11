<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\data\ArrayDataProvider;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-open-nood-envelop-view">

    <h1><?= Html::encode(Yii::t('app', 'Hints for') . ' ' . $model->route_name) ?></h1>

    <p>
    <?php

        $dataProvider = new ArrayDataProvider([
            'allModels' => $model->noodEnvelops,
        ]);

        echo ListView::widget([
            'summary' => FALSE,
            'pager' => FALSE,
            'dataProvider' => $dataProvider,
            'itemView' => '/open-nood-envelop/_list',
        ]);
    ?>
</div>
