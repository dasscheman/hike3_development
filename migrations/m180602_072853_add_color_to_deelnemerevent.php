<?php

use yii\db\Migration;

class m180602_072853_add_color_to_deelnemerevent extends Migration
{
    public function up()
    {
        $this->addColumn('tbl_deelnemers_event', 'color', $this->string());
    }

    public function down()
    {
        $this->dropColumn('tbl_deelnemers_event', 'color');
    }
}
