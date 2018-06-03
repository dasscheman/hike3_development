<?php

use yii\db\Migration;

class m180524_212853_add_allow_track_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'allow_track', $this->boolean()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('user', 'allow_track');
    }
}
