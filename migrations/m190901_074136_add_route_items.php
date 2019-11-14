<?php

use yii\db\Migration;

/**
 * Class m190901_074136_add_route_items
 */
class m190901_074136_add_route_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /*****************************************************************************/
        $this->addColumn('tbl_route', 'start_datetime', $this->dateTime());
        $this->addColumn('tbl_route', 'end_datetime', $this->dateTime());
        $this->addColumn('tbl_posten', 'start_datetime', $this->dateTime());
        $this->addColumn('tbl_posten', 'end_datetime', $this->dateTime());
        $this->addColumn('tbl_time_trail', 'start_datetime', $this->dateTime());
        $this->addColumn('tbl_time_trail', 'end_datetime', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tbl_route', 'start_datetime');
        $this->dropColumn('tbl_route', 'end_datetime');
        $this->dropColumn('tbl_posten', 'start_datetime');
        $this->dropColumn('tbl_posten', 'end_datetime');
        $this->dropColumn('tbl_time_trail', 'start_datetime');
        $this->dropColumn('tbl_time_trail', 'end_datetime');
    }
}
