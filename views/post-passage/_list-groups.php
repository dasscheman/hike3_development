<?php
use app\components\GeneralFunctions;
use yii\helpers\Html;
use kartik\widgets\AlertBlock;
use yii\widgets\Pjax;
use app\models\Posten;
use prawee\widgets\ButtonAjax;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
        <?php
            Pjax::begin([
                'id' => 'post-passage-list-groups' . $model->group_ID,
                'enablePushState' => false
            ]);
            echo AlertBlock::widget([
                'type' => AlertBlock::TYPE_ALERT,
                'useSessionFlash' => true,
                'delay' => 4000,
            ]);
        ?>
        <p>
            <h3>
                <?php echo Html::encode($model->group_name); ?>
            </h3>
            <?php
            $posten = new Posten;
            if ($posten->isStartPost($post_id)) {
                echo ButtonAjax::widget([
                    'name' => Yii::t('app', 'Start hike for {group}', ['group' => $model->group_name,]),
                     'route'=>['post-passage/start', ['group_ID' => $model->group_ID, 'post_ID' => $post_id]],
                     'modalId'=>'#main-modal',
                     'modalContent'=>'#main-content-modal',
                     'options'=>[
                         'class'=>'btn btn-success',
                         'title'=>'Start',
                         'disabled' => !Yii::$app->user->identity->isActionAllowed(
                             'post-passage',
                             'start',
                             [
                                 'group_ID' => $model->group_ID,
                                 'post_ID' => $post_id
                             ]),
                     ]
                 ]);
             }

            if (Yii::$app->user->identity->isActionAllowed('post-passage', 'checkin', ['group_ID' => $model->group_ID, 'post_ID' => $post_id])) {
                echo ButtonAjax::widget([
                    'name' => Yii::t('app', 'Check in {group}', ['group' => $model->group_name,]),
                     'route'=>['post-passage/checkin', ['group_ID' => $model->group_ID, 'post_ID' => $post_id]],
                     'modalId'=>'#main-modal',
                     'modalContent'=>'#main-content-modal',
                     'options'=>[
                         'class'=>'btn btn-success',
                         'title'=>'Checkin',
                         'disabled' => !Yii::$app->user->identity->isActionAllowed(
                             'post-passage',
                             'checkin',
                             [
                                 'group_ID' => $model->group_ID,
                                 'post_ID' => $post_id
                             ]),
                     ]
                ]);
            }

            if (Yii::$app->user->identity->isActionAllowed('post-passage', 'checkout', ['group_ID' => $model->group_ID, 'post_ID' => $post_id])) {
                echo ButtonAjax::widget([
                   'name' => Yii::t('app', 'Check out {group}', ['group' => $model->group_name,]),
                    'route'=>['post-passage/checkout', ['group_ID' => $model->group_ID, 'post_ID' => $post_id]],
                    'modalId'=>'#main-modal',
                    'modalContent'=>'#main-content-modal',
                    'options'=>[
                        'class'=>'btn btn-info',
                        'title'=>'Checkout',
                        'disabled' => !Yii::$app->user->identity->isActionAllowed(
                            'post-passage',
                            'checkin',
                            [
                                'group_ID' => $model->group_ID,
                                'post_ID' => $post_id
                            ]),
                    ]
                ]);
            }
        ?>
        </p>
        <?php
        $postPassage = $model->getPostPassages()
            ->where(['post_ID' => $post_id]);

        $db = $model::getDb();
        $postPassageExists = $db->cache(function ($db) use($postPassage){
            return $postPassage->exists();
        });
        if ($postPassageExists) {
            $postPassageData = $db->cache(function ($db) use($postPassage){
                return $postPassage->one();
            }); ?>
            <b>
            <?php echo Html::encode($postPassageData->getAttributeLabel('gepasseerd')); ?>
            </b>
            <?php echo GeneralFunctions::printGlyphiconCheck($postPassageData->gepasseerd); ?></br>
            <b>
            <?php echo Html::encode($postPassageData->getAttributeLabel('binnenkomst')); ?>
            </b>
            <?php echo Html::encode($postPassageData->binnenkomst); ?></br>
            <b>
        <?php
        }
        Pjax::end(); ?>
        </div>
    </div>
</div>
