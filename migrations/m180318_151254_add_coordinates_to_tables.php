<?php

use yii\db\Migration;

class m180318_151254_add_coordinates_to_tables extends Migration
{
    public function up()
    {
        /*****************************************************************************/
        /* Add coordinates to posten, qr, vragen and hints
        /*****************************************************************************/
        $this->addColumn('tbl_posten', 'latitude', $this->decimal(10, 8));
        $this->addColumn('tbl_posten', 'longitude', $this->decimal(11, 8));

        $this->addColumn('tbl_qr', 'latitude', $this->decimal(10, 8));
        $this->addColumn('tbl_qr', 'longitude', $this->decimal(11, 8));

        $this->addColumn('tbl_nood_envelop', 'latitude', $this->decimal(10, 8));
        $this->addColumn('tbl_nood_envelop', 'longitude', $this->decimal(11, 8));

        $this->addColumn('tbl_open_vragen', 'latitude', $this->decimal(10, 8));
        $this->addColumn('tbl_open_vragen', 'longitude', $this->decimal(11, 8));

        $this->addColumn('tbl_time_trail_item', 'latitude', $this->decimal(10, 8));
        $this->addColumn('tbl_time_trail_item', 'longitude', $this->decimal(11, 8));
    }

    public function down()
    {
        $this->dropColumn('tbl_posten', 'latitude');
        $this->dropColumn('tbl_posten', 'longitude');
        $this->dropColumn('tbl_qr', 'latitude');
        $this->dropColumn('tbl_qr', 'longitude');
        $this->dropColumn('tbl_nood_envelop', 'latitude');
        $this->dropColumn('tbl_nood_envelop', 'longitude');
        $this->dropColumn('tbl_open_vragen', 'latitude');
        $this->dropColumn('tbl_open_vragen', 'longitude');
        $this->dropColumn('tbl_time_trail_item', 'latitude');
        $this->dropColumn('tbl_time_trail_item', 'longitude');

    }
}
