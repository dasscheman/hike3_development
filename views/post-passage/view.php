<?php

use prawee\widgets\ButtonAjax;
use yii\widgets\ListView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblPostPassage */

//$this->title = Yii::t('app', 'Groups passed') . ' ' . $model->post_name;
?>
<div class="tbl-post-passage-view">

    <h1><?= Html::encode($this->title) ?></h1>

       <p>
        <?php

        echo ButtonAjax::widget([
            'name'=>Yii::t('app', 'Check group in station'),
             'route'=>['post-passagae/create', ['post_id' => $model->post_ID]],
             'modalId'=>'#main-modal',
             'modalContent'=>'#main-content-modal',
             'options'=>[
                 'class'=>'btn btn-success',
                 'title'=>'Button for create application',
                 'disabled' => !Yii::$app->user->identity->isActionAllowed('post-passage', 'create'),
             ]
         ]);
        ?>
    </p>
    <?php

    $dataProvider = new yii\data\ArrayDataProvider([
        'allModels' => $model->postPassages,
    ]);

    yii\widgets\Pjax::begin(['id' => 'post-passage-view', 'enablePushState' => TRUE]);

    echo ListView::widget([
        'summary' => FALSE,
        'pager' => FALSE,
        'dataProvider' => $dataProvider,
        'itemView' => '/post-passage/_list',
        'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
    ]);

    yii\widgets\Pjax::end();
?>
</div>
