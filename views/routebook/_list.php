<?php
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use app\models\NoodEnvelop;
use app\models\NoodEnvelopSearch;
use app\models\OpenNoodEnvelopSearch;
use app\models\OpenVragen;
use app\models\OpenVragenAntwoordenSearch;
use app\models\OpenVragenSearch;
use app\models\QrCheckSearch;
use app\models\QrSearch;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <?php
            echo Html::tag('h2', Html::encode($model->route->route_name));

            if(isset($model->route->start_datetime) ||
              isset($model->route->end_datetime)){
                $time_valid =
                    ' (' .
                    Yii::$app->setupdatetime->displayFormat($model->route->start_datetime, 'datetime_short', false, false)
                    . ' - ' .
                    Yii::$app->setupdatetime->displayFormat($model->route->end_datetime, 'datetime_short', false, false) .')';

                echo Html::encode($time_valid);
            }
            ?>
            <div class="routebook-tekst">
                <?php echo $model->tekst; ?></br>
            </div>
            <?php

            $vragenModel = new OpenVragen;
            $vragenData = $vragenModel->find()
                ->where([
                    'route_ID' => $model->route_ID,
                ]);
            $vragenProvider = new ActiveDataProvider([
                'query' => $vragenData,
                'pagination' => [
                    'pageSize' => 1,
                ],
            ]);
            if ($vragen && $vragenData->exists() ) {
                echo Html::tag(
                    'div',
                    Yii::$app->controller->renderPartial('/open-vragen/view-route', [
                        'model' => $vragenProvider,
                        'route_id' => $model->route_ID
                    ]),
                    ['class' => 'well']
                );
            }

            $searchQuestionsModel = new OpenVragenSearch([
                'route_ID' => $model->route_ID,
                'group_id' => $group_id
            ]);
            $questionsData = $searchQuestionsModel->searchQuestionNotAnsweredByGroup([]);
            if ($openVragen && !empty($questionsData->getModels())) {
                echo Html::tag(
                    'div',
                    Yii::$app->controller->renderPartial('/open-vragen-antwoorden/view-route-vraag', [
                        'model' => $questionsData,
                        'route_id' => $model->route_ID
                    ]),
                    ['class' => 'well']
                );
            }

            $searchOpenHintsModel = new OpenNoodEnvelopSearch([
                'route_id' => $model->route_ID,
                'group_ID' => $group_id
            ]);

            $openHintsData = $searchOpenHintsModel->searchOpenedByGroup([]);
            if ($openHints && $openHintsData->getModels()) {
                echo Html::tag(
                    'div',
                    Yii::$app->controller->renderPartial('/open-nood-envelop/view-route-open', [
                        'model' => $openHintsData,
                        'route_id' => $model->route_ID
                    ]),
                    ['class' => 'well']
                );
            }
            $searchHintsModel = new NoodEnvelopSearch([
                'route_ID' => $model->route_ID,
                'group_id' => $group_id
            ]);
            $closedHintsData = $searchHintsModel->searchNotOpenedByGroup([]);
            if ($closedHints && $closedHintsData->getModels()) {
                echo Html::tag(
                    'div',
                    Yii::$app->controller->renderPartial('/nood-envelop/view-dashboard-closed', [
                        'model' => $closedHintsData,
                        'route_id' => $model->route_ID
                    ]),
                    ['class' => 'well']
                );
            }

            $hintModel = new NoodEnvelop;
            $hintData = $hintModel->find()
                ->where([
                    'route_ID' => $model->route_ID,
                ]);

            $hintProvider = new ActiveDataProvider([
                'query' => $hintData,
                'pagination' => [
                    'pageSize' => 1,
                ],
            ]);
            if ($hints && $hintData->exists() ) {
                echo Html::tag(
                    'div',
                    Yii::$app->controller->renderPartial('/nood-envelop/view-route', [
                        'model' => $hintProvider,
                        'route_id' => $model->route_ID
                    ]),
                    ['class' => 'well']
                );
            }
            $searchAnswersModel = new OpenVragenAntwoordenSearch([
                'route_id' => $model->route_ID,
                'group_ID' => $group_id
            ]);
            $answerData = $searchAnswersModel->searchQuestionAnsweredByGroup([]);
            if ($beantwoordeVragen && $answerData->getModels()) {
                echo Html::tag(
                    'div',
                    Yii::$app->controller->renderPartial('/open-vragen-antwoorden/view-route-antwoord', [
                        'model'=>$answerData,
                        'route_id' => $model->route_ID
                    ]),
                    ['class' => 'well']
                );
            }

            $searchQrModel = new QrCheckSearch([
                'route_id' => $model->route_ID,
                'group_ID' => $group_id
            ]);
            $qrCheckData = $searchQrModel->searchByGroup([]);
            if ($qrCheck && $qrCheckData->getModels()) {
                echo Html::tag(
                    'div',
                    Yii::$app->controller->renderPartial('/qr-check/view-route', [
                        'model' => $qrCheckData,
                        'route_id' => $model->route_ID
                    ]),
                    ['class' => 'well']
                );

            }

            $searchQrModel = new QrSearch([
                'route_ID' => $model->route_ID
            ]);
            $qrData = $searchQrModel->search([]);
            if ($qr && $qrData->getModels()) {
                echo Html::tag(
                    'div',
                    Yii::$app->controller->renderPartial('/qr/view-route', [
                        'model' => $qrData,
                        'route_id' => $model->route_ID
                    ]),
                    ['class' => 'well']
                );
            }
            ?>
        </div>
    </div>
</div>
