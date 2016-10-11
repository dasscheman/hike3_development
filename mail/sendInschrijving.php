<html>
	<head></head>
	<body>
		Hallo <?php echo Users::model()->getUserName($mailUsersId); ?>,<br>

		Je ontvangt deze mail omdat <?php echo Users::model()->getUserName(Yii::app()->user->id) ?> je heeft ingeschreven
		op www.hike.nl voor de hike <?php echo EventNames::model()->getEventName($mailEventId) ?>.
		
		Je bent ingeschreven als <?php echo DeelnemersEvent::model()->getRolText($mailRol);
		if ($mailRol == DeelnemersEvent::ROL_deelnemer){
			echo ", voor de groep " . Groups::model()->getGroupName($mailGroupId) . ".";
		} else {
			".";
		}?>

		Als je vragen hebt kun je mailen naar de maker van deze hike <?php echo Users::model()->getUserEmail(Yii::app()->user->id)?>. 
        <br>
        <br>
		Met vriendelijke groet,<br>
		<br>
		www.hike-app.nl<br>
 	</body>
</html>