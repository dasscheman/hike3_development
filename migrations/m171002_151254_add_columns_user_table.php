<?php

use yii\db\Migration;

class m171002_151254_add_columns_user_table extends Migration
{
    public function up()
    {
        /*****************************************************************************/
        /* Geboorte datum toe gevoegd.
        /*****************************************************************************/
        $this->addColumn('user', 'create_time', $this->dateTime());
        $this->addColumn('user', 'create_user_ID', $this->integer(11));
        $this->addColumn('user', 'update_time', $this->dateTime());
        $this->addColumn('user', 'update_user_ID', $this->integer(11));
        $this->addColumn('user', 'selected_event_ID', $this->integer(11));
        $this->addColumn('user', 'voornaam', $this->string());
        $this->addColumn('user', 'tussenvoegsel', $this->string());
        $this->addColumn('user', 'achternaam', $this->string());
        $this->addColumn('user', 'organisatie', $this->string());
        $this->addColumn('user', 'birthdate', $this->date());
    }

    public function down()
    {
        $this->dropColumn('user', 'voornaam');
        $this->dropColumn('user', 'tussenvoegsel');
        $this->dropColumn('user', 'achternaam');
        $this->dropColumn('user', 'birthdate');
        $this->dropColumn('user', 'organisatie');
        $this->dropColumn('user', 'selected_event_ID');
        $this->dropColumn('user', 'create_time');
        $this->dropColumn('user', 'create_user_ID');
        $this->dropColumn('user', 'update_time');
        $this->dropColumn('user', 'update_user_ID');

    }
}
