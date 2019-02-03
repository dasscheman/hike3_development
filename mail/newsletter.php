<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<html>
	<head></head>
	<body>
		<?php echo $body ?>
        <br>
        <br>
				<br>
        <?php
        $link_new_newsletter = Url::to(['/newsletter/subscribe', 'user_id' => $user_id, 'email' => $email], true);
        echo Html::a('Aanmelden voor nieuwsbrieven', $link_new_newsletter);?>
        <br>
        <br>
        <?php
        $link_newsletter = Url::to(['/newsletter/unsubscribe', 'user_id' => $user_id, 'email' => $email], true);
        echo Html::a('Uitschrijven voor nieuwsbrieven', $link_newsletter);?>
        <br>
        <br>
        <?php
        $link_remove_account = Url::to(['/users/remove', 'id' => $user_id, 'email' => $email], true);
        echo Html::a('Account verwijderen', $link_remove_account);?>
        <br>
        <br>
 	</body>
</html>
