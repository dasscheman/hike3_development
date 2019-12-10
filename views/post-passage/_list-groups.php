<?php
use app\components\GeneralFunctions;
use yii\helpers\Html;
use kartik\widgets\AlertBlock;
use yii\widgets\Pjax;
use app\models\Posten;
use app\models\PostPassage;
use prawee\widgets\ButtonAjax;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
        <?php
            Pjax::begin([
                'id' => 'post-passage-list-groups' . $model->group_ID . '-' . $post_id,
                'enablePushState' => false
            ]);
            echo AlertBlock::widget([
                'type' => AlertBlock::TYPE_ALERT,
                'useSessionFlash' => true,
                'delay' => 4000,
            ]);

            echo Html::tag('h3', Html::encode($model->group_name));
            $postPassage = new PostPassage;
            $action = $postPassage->determineAction($post_id, $model->group_ID);
            $title = $postPassage->getActionTitle($action, $model->group_name);
            $postPassage = PostPassage::find()
                ->where('post_ID =:post_id AND group_ID =:group_id')
                ->params([':post_id' => $post_id, ':group_id' => $model->group_ID]);

            if ($action) {
                echo ButtonAjax::widget([
                    'name' => $title,
                     'route'=>[
                         'post-passage/check-station',
                         'group_ID' => $model->group_ID,
                         'post_ID' => $post_id,
                         'action' => $action
                     ],
                     'modalId' => '#main-modal',
                     'modalContent' => '#main-content-modal',
                     'id' => 'check-post-' . $post_id .'-'. $model->group_ID,
                     'options' => [
                        'class' => 'btn btn-success',
                        'title' => $title,
                        'disabled' =>  !Yii::$app->user->can('organisatiePostCheck', [
                            'group_id' => $model->group_ID,
                            'post_id' => $post_id,
                            'action' => $action]),
                     ]
                 ]);
            }
            if($postPassage->exists()) {
                $postPassageData = $postPassage->one();
                echo Html::tag('b', Html::encode($postPassageData->getAttributeLabel('gepasseerd')) . ': ' );
                echo GeneralFunctions::printGlyphiconCheck($postPassageData->gepasseerd);

                if(!$postPassageData->post->isStartPost()) {
                    echo Html::tag('br');
                    echo Html::tag('b', Html::encode($postPassageData->getAttributeLabel('binnenkomst')) . ': ');
                    echo Html::encode($postPassageData->binnenkomst);

                    if(Yii::$app->setupdatetime->displayRealTime($postPassageData->binnenkomst, 'datetime')){
                        echo  Html::tag('br');
                        echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($postPassageData->binnenkomst, 'datetime')), ['class'=>'btn-xs']);
                    }
                }

                if(!$postPassageData->post->isEndPost()) {
                    echo Html::tag('br');
                    echo Html::tag('b', Html::encode($postPassageData->getAttributeLabel('vertrek')) . ': ');
                    echo Html::encode($postPassageData->vertrek);
                    if(Yii::$app->setupdatetime->displayRealTime($postPassageData->vertrek, 'datetime')){
                        echo  Html::tag('br');
                        echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($postPassageData->vertrek, 'datetime')), ['class'=>'btn-xs']);
                    }
                }
            }
            Pjax::end(); ?>
        </div>
    </div>
</div>
