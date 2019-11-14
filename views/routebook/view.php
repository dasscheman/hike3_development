<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use prawee\widgets\ButtonAjax;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

$this->registerJsVar('timeTableData', $timeTableData, \yii\web\View::POS_HEAD );
echo Html::tag('h2', Html::encode(Yii::t('app', 'Schema')));
?>

<div class="row">
    <div class="col-sm-12">
        <div class="well">
          <div class="w3-padding-64 w3-content w3-text-grey" id="timetable">
              <h2 class="w3-text-light-grey">Timetable</h2>
              Dit is je schema voor de komende 24 uur. Je ziet hier de route onderdelen en
              posten die actief zijn of dat zeer binnenkort worden.
              Elk route onderdeel en post heeft een begintijd en een eindtijd. Dat is het
              tijdsframe dat je de route kan lopen of de post kan bezoeken.
              De begintijd van elke onderdeel zegt iets over de chronologie.
              Begint route A eerder dan route B, dan zal je A eerst moeten doen en dan pas B.
              Hebben twee of meer routes dezelfde begintijd, dan kun je daar een
              (strategische) keuze maken. <br>
              <b>Belangrijk</b> niet zelden zal je bij het afronden van route A informatie krijgen hoe en
              waar route B begint. Routes hoeven dus niet perse op elkaar aan te sluiten.
              <hr style="width:200px" class="w3-opacity">
              <div class="timetable" id='timetable'></div>
          </div>
          <script>

              console.log(timeTableData)

                var timetable = new Timetable();
                createTimeTable(timeTableData)
                console.log(timetable);
              // var timetable = new Timetable();
              // timetable.setScope(timeTableData.start_eind[0], timeTableData.start_eind[1]); // optional, only whole hours between 0$
              // timetable.addLocations(timeTableData.lokaties);
              // for (var key in timeTableData.events) {
              //   var starttijd = timeTableData.events[key][1].split(/-| |:/);
              //   var eindtijd = timeTableData.events[key][2].split(/-| |:/);
              //   timetable.addEvent(
              //     timeTableData.events[key][0], //omschrijving
              //     key, // event/row
              //     new Date(starttijd[0],starttijd[1],starttijd[2],starttijd[3],starttijd[4]), //starttijd
              //     new Date(eindtijd[0],eindtijd[1],eindtijd[2], eindtijd[3], eindtijd[4]) //eindtijd
              //   );
              // }
              // var timetableRender = new Timetable.Renderer(timetable);
              // timetableRender.draw('.timetable');
      // ??      })();
          </script>
        </div>
    </div>
</div>
<?php
echo Html::tag('h2', Html::encode(Yii::t('app', 'Routeboek')));



$dataProvider = new yii\data\ArrayDataProvider([
    'allModels' => $model,
]);
?>
<div id="image-popup-modal" class="image-popup-modal">
    <span  id="image-popup-close" class="image-popup-close">&times;</span>
    <img class="image-popup-modal-content" id="image-popup-image">
    <div id="image-popup-caption"></div>
</div>
<?php
echo ListView::widget([
    'summary' => false,
    'pager' => false,
    'dataProvider' => $dataProvider,
    'itemView' => '/routebook/_list',
    'viewParams' => [
        'vragen' => $vragen,
        'openVragen' => $openVragen,
        'beantwoordeVragen' => $beantwoordeVragen,
        'hints' => $hints,
        'closedHints' => $closedHints,
        'openHints' => $openHints,
        'qr' => $qr,
        'qrCheck' => $qrCheck,
        'group_id' => $group_id
    ],
    'emptyText' => '',
]);
