<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<html>
	<head></head>
	<body>
		hallo <?php echo $name ?>
        <br>
        <br>
        Je account is geblokkeerd en wordt binnenkort verwijderd.

		<br>
        Wil je dit toch niet dan kun je zoalang je account nog niet verwijderd is deze actie ongedaan maken.
        <?php
        $link_remove_account = Url::to(['/users/unblock', 'id' => $user_id, 'email' => $email]);
        echo Html::a('Account deblokkeren', $link_remove_account);?>
        <br>
        <br>
 	</body>
</html>
