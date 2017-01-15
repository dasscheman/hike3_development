<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use prawee\widgets\ButtonAjax;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-nood-envelop-view">
<?php
    // d($model->deelnemersEvents);
    //dd($model->getDeelnemersEvents());
?>
    <h3><?= Html::encode(Yii::t('app', 'Members of group') . ' ' . $model->group_name) ?></h3>

    <?php

    $dataProvider = new yii\data\ArrayDataProvider([
        'allModels' => $model->deelnemersEvents,
    ]);

    echo ListView::widget([
        'summary' => FALSE,
        'pager' => FALSE,
        'dataProvider' => $dataProvider,
        'itemView' => '/users/_list',
    ]);
?>
</div>
