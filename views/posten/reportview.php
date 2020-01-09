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
    $incheck_code = $model->incheck_code;
    $uitcheck_code = $model->uitcheck_code;
    $post_name = $model->post_name;
    $start = $model->start_datetime;
    $end = $model->end_datetime;
    $score = $model->score;?>

<div style="border: none; background-color: #21C5CE; ">
	<div class="rounded title">
		<h1> <?php echo $event_name; ?>  (score:<?php echo $score; ?>)</h1>
		<h2> Datum <?php echo $start_date; ?> tot <?php echo $end_date?> </h2>

		<b>Post naam:</b> <?php echo $post_name ?><br>
    <?php
    if(isset($start)) { ?>
      <b> Open vanaf:</b><?php echo $start;
    }
    if(isset($end)) { ?>
        <b>Sluit om:</b><?php echo $end;
    }

    if(isset($end) || isset($start)) { ?>
        <br>
    <?php } ?>
		<b>Organisatie:</b><?php echo $organisatie ?><br>

	</div>

	<div class="rounded left">
		<?php $img = Url::to(Yii::$app->params['post_code_path'] . $model->incheck_code . '.jpg') ?>
		<?php echo Html::img($img, ['class' => 'image']); ?>
	</div>

	<div class="rounded tekst centre">
		<b>Inchecken Post</b><br>
    <br>
    <br>
	  Je kunt alleen inchecken als je binnen de tijd bent.
    <br>
		<br>
    <br>
    <br>
    <i>www.hike-app.nl</i>
		<br><br>
		<div class="centretext"><?php echo $incheck_code ?></div>
	</div>

	<div class="rounded tekst right">
		<?php echo Html::img($image, ['class' => 'image']);?>
	</div>
</div>

<div style="border: none; background-color: #21C5CE; ">
	<div class="rounded title">
		<h1> <?php echo $event_name; ?> </h1>
		<h2> Datum <?php echo $start_date; ?> tot <?php echo $end_date?> </h2>

		<b>Post naam:</b> <?php echo $post_name ?><br>
    <?php
    if(isset($start)) { ?>
      <b> Open vanaf:</b><?php echo $start;
    }
    if(isset($end)) { ?>
        <b>Sluit om:</b><?php echo $end;
    }

    if(isset($end) || isset($start)) { ?>
        <br>
    <?php } ?>

		<b>Organisatie:</b><?php echo $organisatie ?><br>

	</div>

	<div class="rounded left">
		<?php $img = Url::to(Yii::$app->params['post_code_path'] . $model->uitcheck_code . '.jpg') ?>
		<?php echo Html::img($img, ['class' => 'image']); ?>
	</div>

	<div class="rounded tekst centre">
		<b>Uitchecken post</b><br>
    <br>
    <br>
    Je kunt alleen uitchecken als je eerst ingecheckt bent.
    <br>
		<br>
    <br>
    <br>
		<i>www.hike-app.nl</i>
		<br><br>
		<div class="centretext"><?php echo $uitcheck_code ?></div>
	</div>

	<div class="rounded tekst right">
		<?php echo Html::img($image, ['class' => 'image']);?>
	</div>
</div>
