<?php

//use Yii;
use yii\db\Migration;
use app\models\EventNames;


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
        $events = Yii::$app->db->createCommand("SELECT * FROM tbl_event_names WHERE active_day='0000-00-00'")->queryAll();

        foreach ($events as $event) {
            $event->active_day = NULL;
            $event->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

    }
}
