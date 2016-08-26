<?php

use yii\db\Schema;
use yii\db\Migration;

class m160130_212853_add_active_event_to_users extends Migration
{
    public function up()
    {
        $this->addColumn('tbl_users', 'selected_event_ID', $this->integer(11));
        $this->addColumn('tbl_users', 'authKey',  $this->string());
        $this->addColumn('tbl_users', 'accessToken',  $this->string());
    }

    public function down()
    {
        $this->dropColumn('tbl_users', 'selected_event_ID');
        $this->dropColumn('tbl_users', 'authKey');
        $this->dropColumn('tbl_users', 'accessToken');
    }
}
