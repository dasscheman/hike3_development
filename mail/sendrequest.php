<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\DeelnemersEvent;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<html>
	<head></head>
	<body>
		Hallo <?php echo $mailUsersNameReceiver; ?>,<br>

		Je ontvangt deze mail omdat <?php echo $mailUsersNameSender ?> (<?php echo $mailUsersEmailSender ?>) je een vriendschapsverzoek gedaan op hike-app.nl.

		Op je profiel pagina kun je dit verzoek accepteren of weigeren.

        <br>
        <br>
		Met vriendelijke groet,<br>
		<br>
		hike-app.nl<br>
 	</body>
</html>
