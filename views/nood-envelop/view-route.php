<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\data\ActiveDataProvider;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-nood-envelop-view-route">
      <h3> <?php echo Yii::t('app', 'Hint') ?> </h3>
      <p>
          <?php
          Pjax::begin(['id' => 'nood-envelop-view-route-' . $route_id, 'enablePushState' => false]);
          ?>
      </p>
      <?php
          echo ListView::widget([
              'summary' => false,
              'pager' => [
                  'prevPageLabel' => Yii::t('app', 'previous'),
                  'nextPageLabel' => Yii::t('app', 'next'),
                  'maxButtonCount' => 0,
                  'options' => [
                     'class' => 'pagination pagination-sm',
                  ],
              ],
              'dataProvider' => $model,
              'itemView' => '/nood-envelop/_list-route',
              'emptyText' => Yii::t('app', 'No hints that are opened.'),
          ]);
      ?>
      <?php Pjax::end(); ?>
</div>
