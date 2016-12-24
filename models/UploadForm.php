<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image; 
use Imagine\Image\Box;


class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10,  'maxSize'=> 10000000],
        ];
    }
    
    public function upload($event_id)
    {
        if ($this->validate()) { 
            foreach ($this->imageFiles as $file) {     
                $filename = 'uploads/' . $event_id .  '.' . $file->extension;
//                if (file_exists ($filename)) {
//                    $filename = 'uploads/' . time() . '-' . mt_rand(1000, 9999) . '.' . $file->extension;
//                }
                $file->saveAs($filename);
                $exif = @exif_read_data();
                $rotation = 0;        
                if (isset($exif['Orientation'])) {
                    $ort = $exif['Orientation'];
                }
                switch($ort)
                {
                    case 3: // 180 rotate left
                        $rotation = 180;
                        break;
                    case 6: // 90 rotate right
                        $rotation = 90;
                        break;
                    case 8:    // 90 rotate left
                        $rotation =  -90;
                        break;
                }
                $temp = Image::getImagine();//('uploads/' . $file->baseName . '-' . time() .  '.' . $file->extension);
                $temp->open('uploads/' . $file->baseName . '-' . time() .  '.' . $file->extension)
                    ->rotate($rotation)
                    ->save('uploads/small/' . $file->baseName . '-' . time() .  '.' . $file->extension, ['quality' => 30]);
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function resize()
    {
        foreach ($this->imageFiles as $file) {                              
            $temp = Image::getImagine();//('uploads/' . $file->baseName . '-' . time() .  '.' . $file->extension);
            $temp->open('uploads/' . $file->baseName . '-' . time() .  '.' . $file->extension)
                ->save('uploads/small/' . $file->baseName . '-' . time() .  '.' . $file->extension, ['quality' => 10]);
        }
    }
        
    public function rotate()
    {
        foreach ($this->imageFiles as $file) {                              
            $exif = @exif_read_data('uploads/small/' . $file->baseName . '-' . time() .  '.' . $file->extension);
            $rotation = 0;                
            $ort = $exif['Orientation'];
            switch($ort)
            {
                case 3: // 180 rotate left
                    $rotation = 180;
                    break;
                case 6: // 90 rotate right
                    $rotation = 90;
                    break;
                case 8:    // 90 rotate left
                    $rotation =  -90;
                    break;
            }
//           
            $temp = Image::getImagine();//('uploads/' . $file->baseName . '-' . time() .  '.' . $file->extension);
            $temp->open('uploads/small/' . $file->baseName . '-' . time() .  '.' . $file->extension)
                ->rotate($rotation)
                ->save('uploads/small/' . $file->baseName . '-' . time() .  '.' . $file->extension); //, ['quality' => 10]);
        }
    }
}