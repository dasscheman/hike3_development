<?php

//use Yii;
use yii\db\Migration;
use app\models\EventNames;
use app\models\Route;
use app\models\Posten;
use app\models\PostPassage;

/**
 * Class m171104_143306_update_rbac_data
 */
class m170930_133206_fix_data_issues_before_migrate extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $events = EventNames::find()
            ->where(['active_day' => '0000-00-00'])
            ->all();

        foreach ($events as $event) {
            $event->active_day = NULL;
            $event->save();
        }

        $routes = Route::find()
            ->where(['day_date' => '0000-00-00'])
            ->all();

        foreach ($routes as $route) {
            $route->day_date = NULL;
            $route->save(FALSE);
        }

        $posten = Posten::find()
            ->where(['date' => '0000-00-00'])
            ->all();

        foreach ($posten as $post) {
            $post->date = NULL;
            $post->save(FALSE);
        }

        $postPassages = PostPassage::find()
            ->where(['binnenkomst' => '0000-00-00'])
            ->all();

        foreach ($postPassages as $postPassage) {
            $postPassage->binnenkomst = NULL;
            $postPassage->save(FALSE);
        }

        $postPassages = PostPassage::find()
            ->where(['vertrek' => '0000-00-00'])
            ->all();

        foreach ($postPassages as $postPassage) {
            $postPassage->vertrek = NULL;
            $postPassage->save(FALSE);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

    }
}
