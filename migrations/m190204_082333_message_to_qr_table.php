<?php

use yii\db\Schema;
use yii\db\Migration;

class m190204_082333_message_to_qr_table extends Migration
{
    public function up()
    {
        $this->addColumn('tbl_qr', 'message', $this->string(1050));
    }

    public function down()
    {
        $this->dropColumn('tbl_qr', 'message');
    }
}
