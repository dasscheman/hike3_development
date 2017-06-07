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
        ?>
        <p>
            <h3>
                <?php echo Html::encode($model->group_name); ?>
            </h3>
            <?php
            $action = PostPassage::determineAction($post_id, $model->group_ID);
            $title = PostPassage::getActionTitle($action, $model->group_name);
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
                     'options' => [
                         'class' => 'btn btn-success',
                         'title' => $title,
                     ]
                 ]);
            }
            if($postPassage->exists()) {
                $postPassageData = $postPassage->one();
                ?>
                </br>
                <b>
                <?php echo Html::encode($postPassageData->getAttributeLabel('gepasseerd')); ?>
                </b>
                <?php echo GeneralFunctions::printGlyphiconCheck($postPassageData->gepasseerd); ?></br>
                <b>
                <?php echo Html::encode($postPassageData->getAttributeLabel('binnenkomst')); ?>
                </b>
                <?php echo Html::encode($postPassageData->binnenkomst); ?></br>
                <b>
                <?php echo Html::encode($postPassageData->getAttributeLabel('vertrek')); ?>
                </b>
                <?php echo Html::encode($postPassageData->vertrek); ?></br>
            <?php } ?>
        </p>
        <?php Pjax::end(); ?>
        </div>
    </div>
</div>
