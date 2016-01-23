<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202321_vragen_table extends Migration
{
    public function up()
    {
        /*
         * Hier wordt per Route ID een open vraag gemaakt. Elke vraag moet 
         * handmatig gecontroleerd worden en goed gekeurd worden
         * Ook kan het goede antwoord hier opgeslagen worden. Dit kan dan 
         * getoond worden bij de controle. Dit moet echter wel handmatig 
         * gecontroleerd worden. 
         */
        
        $this->createTable('tbl_open_vragen', [ 
            'open_vragen_ID'    => $this->primaryKey(),
            'open_vragen_name'  => $this->string()->notNull(),
            'event_ID'          => $this->integer(11)->notNull(),
            'route_ID' 	    	=> $this->integer(11)->notNull(),
            'vraag_volgorde'    => $this->integer(11),
            'omschrijving'      => $this->text()->notNull(),
            'vraag'             => $this->string()->notNull(),
            'goede_antwoord'    => $this->string()->notNull(),
            'score'             => $this->integer(11)->notNull(),
            'create_time'       => $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ],
        'ENGINE=InnoDB'); 

        $this->createIndex(
            'open_vragen_name', 
            'tbl_open_vragen', 
            ['open_vragen_name', 'event_ID', 'route_ID'], 
            true);
         
	$this->addForeignKey(
            "fk_open_vragen_event_id", 
            "tbl_open_vragen", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");
	    
	$this->addForeignKey(
            "fk_open_vragen_route", 
            "tbl_open_vragen", 
            "route_ID",
            "tbl_route", 
            "route_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_open_vragen_create_user", 
            "tbl_open_vragen", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");
	  
	$this->addForeignKey(
            "fk_open_vragen_update_user", 
            "tbl_open_vragen", 
            "update_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");  

 
        /**
         * Hier worden de antwoorden van de groepjes opgeslagen. 
         * Elk antwoord moet gecontroleerd worden en goegekeurd worden
        **/  
         	
	$this->createTable('tbl_open_vragen_antwoorden', [
            'open_vragen_antwoorden_ID' => $this->primaryKey(),
            'open_vragen_ID'            => $this->integer(11)->notNull(),
            'event_ID'          	=> $this->integer(11)->notNull(),
            'group_ID'                  => $this->integer(11)->notNull(),
            'antwoord_spelers'          => $this->string()->notNull(),
            'checked'  		    	=> $this->boolean(),
            'correct'  		    	=> $this->boolean(),
            'create_time'               => $this->dateTime(),
            'create_user_ID'            => $this->integer(11),
            'update_time'               => $this->dateTime(),
            'update_user_ID'            => $this->integer(11),
	],
        'ENGINE=InnoDB'); 

        $this->createIndex(
            'open_vragen_ID',
            'tbl_open_vragen_antwoorden', 
            ['open_vragen_ID', 'group_ID'], 
            true);

	$this->addForeignKey(
            "fk_open_vragen_antwoorden_vragen_id", 
            "tbl_open_vragen_antwoorden", 
            "open_vragen_ID",
            "tbl_open_vragen", 
            "open_vragen_ID", 
            "RESTRICT", 
            "CASCADE");
	         
	$this->addForeignKey(
            "fk_open_vragen_antwoorden_event_id", 
            "tbl_open_vragen_antwoorden", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_open_vragen_antwoorden_group_id", 
            "tbl_open_vragen_antwoorden", 
            "group_ID",
            "tbl_groups", 
            "group_ID", 
            "RESTRICT", 
            "CASCADE"); 

	$this->addForeignKey(
            "fk_open_vragen_antwoorden_create_user", 
            "tbl_open_vragen_antwoorden", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_open_vragen_antwoorden_update_user", 
            "tbl_open_vragen_antwoorden", 
            "update_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");

    }

    public function down()
    {
        $this->truncateTable('tbl_open_vragen_antwoorden');
        $this->dropTable('tbl_open_vragen_antwoorden');

        $this->truncateTable('tbl_open_vragen');
        $this->dropTable('tbl_open_vragen');
    }
}
