<?php

namespace app\controllers;

use Yii;
use app\models\Route;
use app\models\OpenVragen;
use app\models\Posten;
use app\models\Qr;
use app\models\NoodEnvelop;
use app\models\TimeTrailItem;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\ExportImport;
use yii\web\UploadedFile;

/**
 * BonuspuntenController implements the CRUD actions for Bonuspunten model.
 */
class ExportImportController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['export-route', 'import-route'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => FALSE, // deny all users
                        'roles' => ['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Create an export of the hike.
     * @return mixed
     */
    public function actionExportRoute() {
        $file = \Yii::createObject([
                'class' => 'codemix\excelexport\ExcelFile',
                'writerClass' => '\PHPExcel_Writer_Excel5', // Override default of `\PHPExcel_Writer_Excel2007`
                'sheets' => [
                    'Manual' => [
                        'data' => [
                            [Yii::t('app', 'Sheet'), Yii::t('app', 'Field'), Yii::t('app', 'Remarks')],
                            ['Route', 'route_ID', Yii::t('app', 'This is the unique identifier for route items.
Do not change existing route items. For new route items start numbering from 1
and increase each route item with 1 and add N before the number.s')],
                            ['Questions', ''],
                            ['Silentstations', ''],
                            ['Hints', ''],
                            ['TimeTrail', ''],
                        ],
                    ],
                    'Route' => [
                        'class' => 'codemix\excelexport\ActiveExcelSheet',
                        'query' => Route::find()
                            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                            ->orderBy([
//                            'day_date'=>SORT_ASC,
                                'route_volgorde' => SORT_ASC,
                            ]),
                    ],
                    'Posten' => [
                        'class' => 'codemix\excelexport\ActiveExcelSheet',
                        'query' => Posten::find()
                            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                            ->orderBy([
                                'date'=>SORT_ASC,
                                'post_volgorde' => SORT_DESC
                            ]),
                    ],
                    'Questions' => [
                        'class' => 'codemix\excelexport\ActiveExcelSheet',
                        'query' => OpenVragen::find()
                            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                            ->orderBy([
                                'vraag_volgorde' => SORT_DESC,
                                // 'day_date'=>SORT_ASC
                            ]),
                    ],
                    'Silentstations' => [
                        'class' => 'codemix\excelexport\ActiveExcelSheet',
                        'query' => Qr::find()
                            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                            ->orderBy([
                                'qr_volgorde' => SORT_DESC,
                                //   'day_date'=>SORT_ASC
                            ]),
                    ],
                    'Hints' => [
                        'class' => 'codemix\excelexport\ActiveExcelSheet',
                        'query' => NoodEnvelop::find()
                            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                            ->orderBy([
                                'nood_envelop_volgorde' => SORT_DESC,
                                // 'day_date'=>SORT_ASC
                            ]),
                    ],
                    'TimeTrail' => [
                        'class' => 'codemix\excelexport\ActiveExcelSheet',
                        'query' => TimeTrailItem::find()
                            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                            ->orderBy([
                                'time_trail_ID' => SORT_DESC,
                                'volgorde' => SORT_DESC,
                                // 'day_date'=>SORT_ASC
                            ]),
                    ],
                ],
        ]);
        $file->send('demo.ods');
        return $this->redirect(['/site/overview-organisation']);
    }

    /**
     * Create an export of the hike.
     * @return mixed
     */
    public function actionImportRoute() {
        $model = new ExportImport();

        if ($model->load(Yii::$app->request->post())) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->upload()) {
                // file is uploaded successfully
                $model->importExcel();
                return;
            }
        }

        return $this->redirect(['site/overview-organisation']);
    }

}
