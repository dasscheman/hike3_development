<?php
	/**
	 * HTML2PDF Librairy - example
	 *
	 * HTML => PDF convertor
	 * distributed under the LGPL License
	 *
	 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
	 *
	 * isset($_GET['vuehtml']) is not mandatory
	 * it allow to display the result in the HTML format
	 */
	// get the HTML

	//ob_start();

	use yii\helpers\Html;
	use dosamigos\qrcode\QrCode;
	use yii\helpers\Url;
	$modelEvent = $model->event;
	$event_name = $modelEvent->event_name;
	$start_date = $modelEvent->start_date;
	$end_date = $modelEvent->end_date;
	$organisatie = $modelEvent->organisatie;
	$site = $modelEvent->website; //'www.debison.nl';
	$image = $modelEvent->image;
	if (!isset($image) || $image == '') {
		$image = '@web/uploads/event_images/aangifte.jpg';
	} else {
		$image = '@web/uploads/event_images/' . $image;
	}

	$event_id = $model->event_ID;
	$qr_code = $model->qr_code;
	$qr_name = $model->qr_name;
	$score = $model->score;?>

<div style="border: none; background-color: #61c419; ">
	<div class="rounded title">
		<h1> Titel </h1>
		<h2> Datum <?php echo $start_date; ?> tot <?php echo $end_date?> </h2>

		<b>Score:</b><?php echo $score; ?><br>
		<b>QR code naam:</b> <?php echo $qr_name ?><br>
		<b>QR code:</b><?php echo $qr_code ?><br>

	</div>

	<div class="rounded left">
		<?php echo Html::img('@web/qr/' . $model->qr_code . '.jpg', ['class' => 'image']);?>
	</div>

	<div class="rounded tekst centre">
		<b>Stille Post</b><br>
		Dit is een stille post. Je kunt deze scannen met een QR code scanner op je smartphone.
		Als je de QR code gescand hebt, dan moet je de link volgen die in de code staat.
		Je komt dan op de site van www.hike-app.nl, er wordt om je inlog gevraagd.
		Als je inlogt krijgt je groepje punten voor het vinden van deze stille post.
		Indien je geen bereik hebt kun je met de meeste QR code scanners de code ook bewaren.
		Je kunt dan de link in de code volgen als je weer bereik hebt.
		<br>
		<br>
		<i>www.hike-app.nl</i>
	</div>
	<div class="rounded tekst right">
		<?php echo Html::img($image, ['class' => 'image']);?>
	</div>
