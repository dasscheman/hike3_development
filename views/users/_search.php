<?php

use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-search">
<?php
    $form = ActiveForm::begin([
        'action' => ['/users/search-new-friends'],
            'options'=>[
                'data-pjax'=>true,
            ],
    ]);
    echo $form->field($model, 'search_friends')
            ->input('text', ['placeholder' => Yii::t('app', 'You can search for email, name and organisation. Minimal 3 characters')])->label(false)
             ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
<?php

    ActiveForm::end();
?>

</div>
