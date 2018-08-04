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
    $code = $model->code;
    $name = $model->time_trail_item_name;
    $score = $model->score;?>

<div style="border: none; background-color: #21C5CE; ">
	<div class="rounded title">
		<h1> <?php echo $event_name; ?> </h1>
		<h2> Datum <?php echo $start_date; ?> tot <?php echo $end_date?> </h2>

		<b>Score:</b><?php echo $score; ?><br>
		<b>naam:</b> <?php echo $name ?><br>
		<b>Organisatie:</b><?php echo $organisatie ?><br>

	</div>

	<div class="rounded left">

        <?php //echo Html::img(Url::to(['time-trail-item/qrcode', 'code' => $model->code]));?>
		<?php $img = Url::to(Yii::$app->params['timetrail_code_path'] . $model->code . '.jpg') ?>
		<?php echo Html::img($img, ['class' => 'image']); ?>
	</div>

	<div class="rounded tekst centre">
		<b>Time trail</b><br>
		Dit is een time trail. Als je deze scant dan krijg je een aanwijzing waar het volgende
        punt is. Op dat punt vind je weer een code die je moet scannen.
        Echter je krijgt maar beperkte tijd om dit te volbrengen.
        Ben te laat, dan moet krijg je geen punten. Je moet de volgende code wel
        scannen voor een eventueel volgende punt.
		<br>
		<br>
		<i>www.hike-app.nl</i>
		<br><br>
		<div class="centretext"><?php echo $code ?></div>
	</div>

	<div class="rounded tekst right">
		<?php echo Html::img($image, ['class' => 'image']);?>
	</div>
</div>
