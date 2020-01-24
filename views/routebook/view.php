<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use prawee\widgets\ButtonAjax;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

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
              Overzicht met de route onderdelen. Qr's en vragen die bij een
              route onderdeel horen, kunnen alleen na de starttijd en voor de
              eindtijd gescand worden.<br>
              Als een onderdeel geen starttijd heeft, dan is hij direct geldig.
              Als een onderdeel geen eindtijd heeft, dan blijt deze geldig.
              <?php
              if(!empty($timeTableData)) { ?>
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
              <?php } else {
                  echo Html::tag('br');
                  echo Html::tag('br');
                  echo Html::tag('i', Html::encode( Yii::t('app', 'geen gegevens voor de timetable')));
              }
              ?>
          </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <!-- <div class="well"> -->
            <div class="w3-padding-64 w3-content w3-text-grey" id="timetable">
                <?php
                echo Html::tag('h2', Html::encode(Yii::t('app', 'Routeboek')));
                echo Html::tag('p', Html::encode(Yii::t('app', 'Routeboek onderdelen van routes die (bijna)
                    actief zijn worden hieronder getoond.')));
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
                'emptyText' =>  Html::tag('i', Html::encode( Yii::t(
                    'app',
                    'geen gegevens voor het routeboek, omdat er geen routes
                     actief zijn of omdat de actieve routes geen routeboek hebben.'))),
            ]); ?>
        </div>
    <!-- </div> -->
</div>
