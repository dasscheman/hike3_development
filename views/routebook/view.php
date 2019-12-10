<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use prawee\widgets\ButtonAjax;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

$this->registerJsVar('timeTableData', $timeTableData, \yii\web\View::POS_HEAD );

?>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="well">
          <div class="w3-padding-64 w3-content w3-text-grey" id="timetable">
              <h2 class="w3-text-light-grey">Timetable</h2>
              Je ziet hier de route onderdelen en
              posten die actief zijn of dat zeer binnenkort worden.
              Elk route onderdeel en post heeft een begintijd en een eindtijd. Dat is het
              tijdsframe dat je de route kan lopen of de post kan bezoeken.
              De begintijd van elke onderdeel zegt iets over de chronologie.
              Begint route A eerder dan route B, dan zal je A eerst moeten doen en dan pas B.
              Hebben twee of meer routes dezelfde begintijd, dan kun je daar een
              (strategische) keuze maken. <br>
              <table>
                  <tr>
                      <th>Naam</th>
                      <th>Soort</th>
                      <th>Starttijd</th>
                      <th>Eindtijd</th>
                  </tr>
                  <?php foreach($timeTableData as $key => $data) { ?>
                      <tr>
                        <td><?php echo $data['name'] ?></td>
                        <td><?php echo $data['type'] ?></td>
                        <td><?php echo $data['start'] ?></td>
                        <td><?php echo $data['end'] ?></td>
                      </tr>

                  <?php } ?>
              </table>
              <!-- <hr style="width:200px" class="w3-opacity">
              <div class="timetable" id='timetable'></div> -->
          </div>
          <!-- <script>
              var timetable = new Timetable();
              createTimeTable(timeTableData)
          </script> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <div class="w3-padding-64 w3-content w3-text-grey" id="timetable">
                <?php
                echo Html::tag('h2', Html::encode(Yii::t('app', 'Routeboek')));
                $dataProvider = new yii\data\ArrayDataProvider([
                    'allModels' => $model,
                ]);
                ?>
            </div>
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
            ]); ?>
        </div>
    </div>
</div>
