<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class ExportImport extends Model
{
    /**
     * @var UploadedFile
     */
    public $importFile;
    private $sheetRoute;
    private $sheetManual;
    private $sheetQuestions;
    private $sheetSilentstations;
    private $sheetHints;
    private $objPHPExcel;
    private $headerSheets = [
        'Manual' => [],
        'Route' => [
            'model',
            'cells' => [
                'A' => 'route_ID',
                'B' => 'route_name',
                'C' => 'event_ID',
                'D' => 'day_date',
                'E' => 'route_volgorde',
            ],
        ],
        'Questions' => [
            'model',
            'cells' => [
                'A' => 'open_vragen_ID',
                'B' => 'open_vragen_name',
                'C' => 'event_ID',
                'D' => 'route_ID',
                'E' => 'vraag_volgorde',
                'F' => 'omschrijving',
                'G' => 'vraag',
                'H' => 'goede_antwoord',
                'I' => 'score',
            ],
        ],
        'Silentstations' => [
            'model',
            'cells' => [
                'A' => 'qr_ID',
                'B' => 'qr_name',
                'C' => 'qr_code',
                'D' => 'event_ID',
                'E' => 'route_ID',
                'F' => 'qr_volgorde',
                'G' => 'score',
            ],
        ],
        'Hints' => [
            'model',
            'cells' => [
                'A' => 'nood_envelop_ID',
                'B' => 'nood_envelop_name',
                'C' => 'event_ID',
                'D' => 'route_ID',
                'E' => 'nood_envelop_volgorde',
                'F' => 'coordinaat',
                'G' => 'opmerkingen',
                'I' => 'score',
            ],
        ],
        'TimeTrail' => [
            'model',
            'cells' => [
                'A' => 'nood_envelop_ID',
                'B' => 'nood_envelop_name',
                'C' => 'event_ID',
                'D' => 'route_ID',
                'E' => 'nood_envelop_volgorde',
                'F' => 'coordinaat',
                'G' => 'opmerkingen',
                'I' => 'score',
            ],
        ]
    ];

    public function rules()
    {
        return [
            [['importFile'], 'file', 'extensions' => 'ods', 'skipOnEmpty' => false, 'checkExtensionByMimeType' => FALSE],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $path = Yii::$app->params['event_import_url'] . 'event' . Yii::$app->user->identity->selected_event_ID . '.ods';
            $this->importFile->saveAs($path);
            try{
                $inputFileType = \PHPExcel_IOFactory::identify($path);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $this->objPHPExcel = $objReader->load($path);
            } catch (Exception $e) {
                die('Error');
            }
            return true;
        } else {
            return false;
        }
    }

    public function importExcel()
    {
        $this->loadRouteSheets();
        $this->checkRouteSheets();
        $this->checkHeaders();
        $this->loadModels();

        $count = 2;
        // foreach () {
        //
        // }

        $row = $this->sheetRoute->getRowIterator(1)->current();

        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        foreach ($cellIterator as $id => $cell) {
            d($id);
            d($cell->getValue());
        }

        $highestRowRoute = $sheetRoute->getHighestRow();
        $highestColumnRoute = $sheetRoute->getHighestColumn();

// $sheetRoute->getCellByColumnAndRow( [$pColumn, $pRow])

        d($highestRowRoute);
        dd($highestColumnRoute);
        throw new HttpException(418, 'Dat mag dus WEL');
        // for ($row = 1; $cell = $list->getCellByColumnAndRow($desired_column, $row) != ""; $row++)
        // {
        //     if ($user_input == $cell->getValue());
        //     {
        //         echo $row;
        //     }
        // }
        // $objPHPExcel->getSheet(1) as $sheet) {
        //     switch ($sheet->globalsetTitle()) {


        throw new HttpException(418, 'Dat mag dus niet');
        return Yii::$app->response->redirect(['/site/overview-organisation']);
    dd('asldkjf');

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for($row=1; $row <= $highestRow; $row++)
        {
            $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);

            if($row==1)
            {
                continue;
            }

            $siswa = new Siswa();
            $siswa->nis = $rowData[0][0];
            $siswa->nama_siswa  = $rowData[0][1];
            $siswa->jenis_kelamin  = $rowData[0][2];
            $siswa->ttl  = $rowData[0][3];
            $siswa->alamat  = $rowData[0][4];
            $siswa->telp  = $rowData[0][5];
            $siswa->agama  = $rowData[0][6];
            $siswa->nama_ortu  = $rowData[0][7];
            $siswa->telp_ortu  = $rowData[0][8];
            $siswa->pekerjaan_ortu = $rowData[0][9];
            $siswa->tahun_masuk = $rowData[0][10];
            $siswa->kelas = $rowData[0][11];
            $siswa->save();

            print_r($siswa->getErrors());
        }
        die('okay');
    }

    function loadRouteSheets() {
        $this->sheetManual = $this->objPHPExcel->getSheet(0);
        $this->sheetRoute = $this->objPHPExcel->getSheet(1);
        $this->sheetQuestions = $this->objPHPExcel->getSheet(2);
        $this->sheetSilentstations = $this->objPHPExcel->getSheet(3);
        $this->sheetHints = $this->objPHPExcel->getSheet(4);
    }

    function checkRouteSheets() {
        foreach($this->headerSheets['cells'] as $sheets => $headers) {
            $tempProperty = 'sheet' . $sheets;
            if (!property_exists($this, $tempProperty)) {
                throw new NotFoundHttpException('Property ' . $tempProperty . ' does not exist.');
            }

            if ($this->{$tempProperty}->getTitle() !== $sheets) {
                throw new HttpException(418, Yii::t(
                    'app',
                    '{sheet} sheets are not as expected, check the original manual sheet for instructions.',
                    ['sheet' => $sheets]));
            }
        }
    }

    function checkHeaders() {
        foreach($this->headerSheets['cells'] as $sheets => $headers) {
            $tempProperty = 'sheet' . $sheets;
            if (!property_exists($this, $tempProperty)) {
                throw new NotFoundHttpException('Property ' . $tempProperty . ' does not exist.');
            }

            foreach($headers as $column => $header) {
                if ($header !== $this->{$tempProperty}->getCell($column . '1')->getValue()) {
                    throw new HttpException(418, Yii::t(
                        'app',
                        'Header {header}  at {sheet} sheet is not as expected, check the original manual sheet for instructions.',
                        [
                            'sheet' => $sheets,
                            'header' => $header
                        ]));
                };
            }
        }
    }

    function loadModels() {


    }
}
