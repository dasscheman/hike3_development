<?php

use yii\widgets\ListView;
use yii\data\ArrayDataProvider;
/* @var $this yii\web\View */

// $dataProvider = new ArrayDataProvider([
//     'allModels' => $groupModel->openVragenAntwoordens,
// ]);

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2> <?php echo Yii::$app->language ?></h2>
                <p>
                    <?php
                        echo ListView::widget([
                            'summary' => FALSE,
                            'pager' => FALSE,
                            'dataProvider' => $dataProvider,
                            'itemView' => '/open-vragen/_list',
                            'emptyText' => Yii::t('app', 'No question which should be checked'),
                        ]);
                    ?>
                </p>
                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>
                    <?php //$this->renderPartial('/open-vragen-antwoorden/view', ['model'=>$model]); ?>
                </p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>
                <?php //$this->renderPartial('/open-vragen-antwoorden/view', ['model'=>$model]); ?>
                </p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
