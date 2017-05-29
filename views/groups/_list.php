<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

use kartik\widgets\Select2;
/* @var $this GroupsController */
/* @var $data Groups */

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
                   'disabled' => !Yii::$app->user->identity->isActionAllowed('groups', 'update', ['group_ID' => $model->group_ID]),
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
        }?>
        </p>
      </div>
    </div>
</div>
