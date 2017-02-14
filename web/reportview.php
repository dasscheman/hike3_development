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
		$image = './web/uploads/event_images/qrreport/' . $image;
	}
	$event_id = $model->event_ID;
	$qr_code = $model->qr_code;
	$qr_name = $model->qr_name;
	$score = $model->score;
    // localhost: $link = "hike/index.php?r=qrCheck/create%26event_id=".$event_id."%26qr_code=".$qr_code;
	$link = "index.php?r=qrCheck/create&event_id=".$event_id."&qr_code=".$qr_code;
	$num = 'CMD01-'.date('ymd');
	print('<page format="100x200" orientation="L" backcolor="#61c419" style="font: arial;">
		<div class="rotate">
			Na de hike zullen wij deze formulieren weer verwijderen om vervuiling tegen te gaan.
		</div>
		<table style="width: 99%;border: none;" cellspacing="4mm" cellpadding="0">
			<tr style="height: 25%">
				<td colspan="3" style="width: 100%">
					<div class="zone" style="height: 26mm;position: relative;font-size: 5mm;">
						<div style="position: absolute; right: 3mm; top: 3mm; text-align: right; font-size: 4mm; ">
							<b><?php echo $organisatie; ?></b>
							<b><?php echo $site; ?></b><br>
						</div>
						<div style="position: absolute; right: 3mm; bottom: 3mm; text-align: right; font-size: 4mm; ">
							<b>Score:</b><?php echo $score; ?><br>
							<b>QR code naam:</b> <?php echo $qr_name ?><br>
							<b>QR code:</b><?php echo $qr_code ?><br>
						</div>
						<h1><?php echo $event_name ?></h1>
						&nbsp;&nbsp;&nbsp;&nbsp;<b>Datum <?php echo $start_date; ?> tot <?php echo $end_date?></b><br>
						<!--<img src="./images/bisonkop.jpg" height="60" width="60" alt="logo" style="margin-top: 3mm; margin-left: 20mm">-->
					</div>
				</td>
			</tr>
			<tr style="height: 75%">
				<td style="width: 30%">
					<div class="zone" style="height: 48mm;vertical-align: middle;text-align: center;">
						<qrcode value="http://www.hike-app.nl/<?php echo $link ?>" ec="Q" style="width: 37mm; border: none;" ></qrcode>
						<!--<img src="http://www.mobile-barcodes.com/qr-code-generator/generator.php?str=http://www.hike-app.nl/<?php //echo $link ?>&barcode=url" alt="QR Code" style="border:none; width: 48mm" /> -->

					</div>
				</td>
				<td style="width: 40%">
					<div class="zone" style="height: 48mm;vertical-align: middle; text-align: justify">

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
				</td>
				<td style="width: 30%">
					<div class="zone" style="height: 48mm;vertical-align: middle; text-align: center">
						<img src="<?php echo $image; ?>" height=90% width= 90% alt="logo" style="margin-top: 2mm; margin-left: 20mm">
					</div>
				</td>
			</tr>
		</table>
	</page>')
?>
