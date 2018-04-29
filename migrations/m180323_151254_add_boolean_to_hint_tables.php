<?php

use yii\db\Migration;

class m180323_151254_add_boolean_to_hint_tables extends Migration
{
    public function up()
    {
        /*****************************************************************************/
        /* Add boolean hints
        /*****************************************************************************/
        $this->addColumn('tbl_nood_envelop', 'show_coordinates', $this->boolean());
        $this->alterColumn('tbl_nood_envelop', 'coordinaat', $this->string(255));
    }

    public function down()
    {
        $this->dropColumn('tbl_nood_envelop', 'show_coordinates');
        $this->alterColumn('tbl_nood_envelop', 'coordinaat', $this->string(255)->notNull());
    }
}
