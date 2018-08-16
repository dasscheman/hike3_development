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
    use yii\helpers\Html;
    use yii\helpers\Url;

    $modelEvent = $model->event;
    $event_name = $modelEvent->event_name;
    $start_date = $modelEvent->start_date;
    $end_date = $modelEvent->end_date;
    $organisatie = $modelEvent->organisatie;
    if (!empty($modelEvent->website)) {
        $organisatie .= ' (' . $modelEvent->website . ')';
    }
    $site = $modelEvent->website;
    if ($modelEvent->image !== null && file_exists(Yii::$app->params['event_images_path'] . $modelEvent->image)) {
        $image = Url::to(Yii::$app->params['event_images_path'] . $modelEvent->image);
    } else {
        $image = Url::to(Yii::$app->params['kiwilogo']);
    }

    $event_id = $model->event_ID;
    $qr_code = $model->qr_code;
    $qr_name = $model->qr_name;
    $score = $model->score;?>

<div style="border: none; background-color: #21C5CE; ">
	<div class="rounded title">
		<h1> <?php echo $event_name; ?> </h1>
		<h2> Datum <?php echo $start_date; ?> tot <?php echo $end_date?> </h2>

		<b>Score:</b><?php echo $score; ?><br>
		<b>QR code naam:</b> <?php echo $qr_name ?><br>
		<b>Organisatie:</b><?php echo $organisatie ?><br>

	</div>

	<div class="rounded left">
		<?php $img = Url::to(Yii::$app->params['qr_code_path'] . $model->qr_code . '.jpg') ?>
		<?php echo Html::img($img, ['class' => 'image']); ?>
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
		<br><br>
		<div class="centretext"><?php echo $qr_code ?></div>
	</div>

	<div class="rounded tekst right">
		<?php echo Html::img($image, ['class' => 'image']);?>
	</div>
</div>
