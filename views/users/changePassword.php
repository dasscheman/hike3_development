<?php
// Created: 2014
// Modified: 22 feb 2015

/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Profiel'=>array('/game/viewUser'),
);
?>

<h1>Wijzig wachtwoord voor <?php echo $model->username; ?></h1>

<?php echo $this->renderPartial('_changePassword', array('model'=>$model)); ?>