<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_200634_route_table extends Migration
{
    public function up()
    {
        /*****************************************************************************/
        /* Route
        /* In deze tabel staat de route voor een hike. Voor elke dag van een hike kunnen
        /* route onderdelen aan gemaakt worden. De datum refereerd niet perse naar de
        /* begin en eind datum gedefinieerd in de eventname tabel. Er kunnen namelijk
        /* ook route onderdelen voor en na de hike aangemaakt worden. Dit is vooral om
        /* de mogelijkheid om introductie vragen, posten en stille posten te ondersteunen. 
        /*****************************************************************************/

	$this->createTable('tbl_route', [
            'route_ID'          => $this->primaryKey(),
            'route_name'	=> $this->string(255)->notNull(),
            'event_ID'          => $this->integer()->notNull(),
            'day_date'          => $this->date()->notNull(),
            'route_volgorde'    => $this->integer(11),
            'create_time'	=> $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'	=> $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
	],
        'ENGINE=InnoDB');
        
        $this->createIndex(
            'event_ID', 
            'tbl_route', 
            ['event_ID', 'day_date', 'route_name'], 
            true);

	$this->addForeignKey(
            "fk_route_event_id", 
            "tbl_route", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_route_create_user_name", 
            "tbl_route", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");
	
	$this->addForeignKey(
            "fk_route_update_user_name", 
            "tbl_route", 
            "update_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");
    }

    public function down()
    {
        $this->truncateTable('tbl_route');
        $this->dropTable('tbl_route');
    }
}
