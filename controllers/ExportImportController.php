<?php

namespace app\controllers;

use Yii;
use app\models\Route;
use app\models\OpenVragen;
use app\models\Qr;
use app\models\NoodEnvelop;
use yii\web\Controller;
// use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\ExportImport;
use yii\web\UploadedFile;

/**
 * BonuspuntenController implements the CRUD actions for Bonuspunten model.
 */
class ExportImportController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                // We will override the default rule config with the new AccessRule class
                'only' => ['export-route', 'import-route'],
                'rules' => [
                    [
                        'actions' => ['export-route', 'import-route'],
                        'allow' => TRUE,
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        }
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Create an export of the hike.
     * @return mixed
     */
    public function actionExportRoute()
    {
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
                    ],
              ],
                'Route' => [
                    'class' => 'codemix\excelexport\ActiveExcelSheet',
                    'query' => Route::find()
                        ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                        ->orderBy([
                            'day_date'=>SORT_ASC,
                            'route_volgorde' => SORT_ASC,
                      ]),
                      'titles' => [
                          'route_ID',
                          'route_name',
                          'event_ID',
                          'day_date',    // Related attribute
                          'route_volgorde',
                      ],
              ],
              'Questions' => [
                  'class' => 'codemix\excelexport\ActiveExcelSheet',
                  'query' => OpenVragen::find()
                      ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                      ->orderBy([
                        'vraag_volgorde' => SORT_DESC,
                        // 'day_date'=>SORT_ASC
                    ]),
                    'titles' => [
                        'open_vragen_ID',
                        'open_vragen_name',
                        'event_ID',
                        'route_ID',
                        'vraag_volgorde',
                        'omschrijving',
                        'vraag',
                        'goede_antwoord',
                        'score',
                    ],

                ],
                'Silentstations' => [
                    'class' => 'codemix\excelexport\ActiveExcelSheet',
                    'query' => Qr::find()
                        ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                        ->orderBy([
                          'qr_volgorde' => SORT_DESC,
                        //   'day_date'=>SORT_ASC
                      ]),
                      'titles' => [
                          'qr_ID',
                          'qr_name',
                          'qr_code',
                          'event_ID',
                          'route_ID',
                          'qr_volgorde',
                          'score',
                      ],

                  ],
                  'Hints' => [
                      'class' => 'codemix\excelexport\ActiveExcelSheet',
                      'query' => NoodEnvelop::find()
                          ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
                          ->orderBy([
                            'nood_envelop_volgorde' => SORT_DESC,
                            // 'day_date'=>SORT_ASC
                        ]),
                        'titles' => [
                            'nood_envelop_ID',
                            'nood_envelop_name',
                            'event_ID',
                            'route_ID',
                            'nood_envelop_volgorde',
                            'coordinaat',
                            'opmerkingen',
                            'score',
                        ],
                    // 'formats' => [
                    //     // Either column name or 0-based column index can be used
                    //     'C' => '#,##0.00',
                    //     3 => 'dd/mm/yyyy hh:mm:ss',
                    // ],
                    //
                    // 'formatters' => [
                    //     // Dates and datetimes must be converted to Excel format
                    //     4 => function ($value, $row, $data) {
                    //         return \PHPExcel_Shared_Date::PHPToExcel(strtotime($value));
                    //     },
                    // ],
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
    public function actionImportRoute()
    {
        $model = new ExportImport();

        if ($model->load(Yii::$app->request->post())) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');

// dd($model); //->checkExtensionByMimeType);
             if ($model->upload()) {
                 // file is uploaded successfully
                 $model->importExcel();
                 return;
             }
        }
    // d($model);

        dd($model->errors);
        return $this->redirect(['site/overview-organisation']);
    }

}
