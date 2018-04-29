<?php

use yii\helpers\Html;
use app\components\CustomAlertBlock;

/* @var $this yii\web\View */

$this->title = 'Unsubscribe newsletter';
?>
<div class="newsletter-unsubscribe">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php 
    echo $this->render('/_alert');
    ?>
</div>
