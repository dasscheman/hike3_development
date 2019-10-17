<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\models\PostPassage;
use app\models\Posten;
use kartik\widgets\Select2;
use prawee\widgets\ButtonAjax;

/* @var $this GroupsController */
/* @var $data Groups */

$postPassage = new PostPAssage();
$posten = new Posten();
?>
<div class="row">
  <div class="col-sm-12">
    <div class="well">
        <p>
        <?php
        echo Html::a(
            $model->group_name,
            [
                '/site/overview-players',
                'group_ID' =>  $model->group_ID,
            ],
            [
                'class' => 'btn btn-xs btn-success',
                'value'=> $model->group_name,
                'name'=>'action'
            ]);
        ?>
        </p>
        <p>
        <?php
        Modal::begin(
           [
               'options' => [
                   'tabindex' => true // important for Select2 to work properly
               ],
               'toggleButton' => [
                   'label' => Yii::t('app', 'Edit'),
                   'id' => 'modalAddOrganisationButton',
                   'class' => 'btn btn-xs btn-link',
                   'disabled' => !Yii::$app->user->can('organisatie'),
               ],
           ]
        );
        foreach ($model->deelnemersEvents as $item) {
            $model->users_temp[] = $item->user_ID;
        }
        echo $this->render('/groups/update', ['model' => $model]);
        Modal::end();

        $printSeparator = false;
        foreach ($model->deelnemersEvents as $player )
        {
            if ($printSeparator){
                echo " - ";
            }
            echo $player->user->voornaam . ' ' . $player->user->achternaam;
            $printSeparator = true;
        }


        if(!$postPassage->isGroupStarted($model->group_ID) && !empty($model->event->active_day)) {
            $post_id = $posten->getStartPost($model->event->active_day);
            echo ButtonAjax::widget([
                'name' => 'Start',
                 'route'=>[
                     'post-passage/check-station',
                     'group_ID' => $model->group_ID,
                     'post_ID' => $post_id,
                     'action' => 'start'
                 ],
                 'modalId' => '#main-modal',
                 'modalContent' => '#main-content-modal',
                 'id' => 'check-post-' . $post_id .'-'. $model->group_ID,
                 'options' => [
                    'class' => 'btn btn-xs btn-success',
                    'title' => 'Start',
                    'disabled' =>  !Yii::$app->user->can('organisatiePostCheck', [
                        'group_id' => $model->group_ID,
                        'post_id' => $post_id,
                        'action' => 'start']),
                 ]
             ]);
          }
        ?>
        </p>
      </div>
    </div>
</div>
