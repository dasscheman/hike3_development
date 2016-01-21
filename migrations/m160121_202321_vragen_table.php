<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202321_vragen_table extends Migration
{
    public function up()
    {

   
/*****************************************************************************/
/* Hier wordt per Route ID een open vraag gemaakt. 
/* Elke vraag moet handmatig gecontroleerd worden en goed gekeurd worden
/* Ook kan het goede antwoord hier opgeslagen worden. Dit kan dan getoond worden 
/* bij de controle. Dit moet echter wel handmatig gecontroleerd worden. 
/*****************************************************************************/                          
	$this->createTable(
	    'tbl_open_vragen', 
        array(
	    	'open_vragen_ID'    => 'int(11) NOT NULL AUTO_INCREMENT', 
			'open_vragen_name'  => 'string NOT NULL',
			'event_ID'          => 'int(11) NOT NULL',
			'route_ID' 	    	=> 'int(11) NOT NULL',
			'vraag_volgorde'    => 'int(11) DEFAULT NULL',
			'omschrijving'      => 'text NOT NULL',  
			'vraag'             => 'string NOT NULL',
			'goede_antwoord'    => 'string NOT NULL',
			'score'    	    	=> 'int(11) NOT NULL',
			'create_time'       => 'datetime DEFAULT NULL',
			'create_user_ID'    => 'int(11) DEFAULT NULL',
			'update_time'       => 'datetime DEFAULT NULL',
			'update_user_ID'    => 'int(11) DEFAULT NULL',
			'PRIMARY KEY (`open_vragen_ID`)',
			'UNIQUE KEY(`open_vragen_name`)',), 
	    'ENGINE=InnoDB'); 


/*********************** table open_vragen **********************************/
         
	$this->addForeignKey("fk_open_vragen_event_id", 
			     "tbl_open_vragen", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");
	    
	$this->addForeignKey("fk_open_vragen_route", 
			     "tbl_open_vragen", 
			     "route_ID",
			     "tbl_route", 
			     "route_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_open_vragen_create_user", 
			     "tbl_open_vragen", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	  
	$this->addForeignKey("fk_open_vragen_update_user", 
			     "tbl_open_vragen", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");  

 
/*****************************************************************************/
/* Hier worden de antwoorden van de groepjes opgeslagen. 
/* Elk antwoord moet gecontroleerd worden en goegekeurd worden
/*****************************************************************************/  
         	
	$this->createTable(
	    'tbl_open_vragen_antwoorden', 
	    array(
			'open_vragen_antwoorden_ID' => 'int(11) NOT NULL AUTO_INCREMENT', 
			'open_vragen_ID'            => 'int(11) NOT NULL', 
			'event_ID'          	    => 'int(11) NOT NULL',
			'group_ID'  		    	=> 'int(11) NOT NULL',
			'antwoord_spelers'          => 'string NOT NULL',
			'checked'  		    		=> 'boolean DEFAULT NULL',
			'correct'  		    		=> 'boolean DEFAULT NULL',
			'create_time'       	    => 'datetime DEFAULT NULL',
			'create_user_ID'            => 'int(11) DEFAULT NULL',
			'update_time'               => 'datetime DEFAULT NULL',
			'update_user_ID'       	    => 'int(11) DEFAULT NULL',
			'PRIMARY KEY (`open_vragen_antwoorden_ID`)',
			'UNIQUE KEY (`open_vragen_ID`, `group_ID`)',), 
	    'ENGINE=InnoDB'); 

/*********************** table aopen_vragen_antwoorden ***********************/
 
	$this->addForeignKey("fk_open_vragen_antwoorden_vragen_id", 
			     "tbl_open_vragen_antwoorden", 
			     "open_vragen_ID",
			     "tbl_open_vragen", 
			     "open_vragen_ID", 
			     "RESTRICT", 
			     "CASCADE");
	         
	$this->addForeignKey("fk_open_vragen_antwoorden_event_id", 
			     "tbl_open_vragen_antwoorden", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_open_vragen_antwoorden_group_id", 
			     "tbl_open_vragen_antwoorden", 
			     "group_ID",
			     "tbl_groups", 
			     "group_ID", 
			     "RESTRICT", 
			     "CASCADE"); 

	$this->addForeignKey("fk_open_vragen_antwoorden_create_user", 
			     "tbl_open_vragen_antwoorden", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	
	$this->addForeignKey("fk_open_vragen_antwoorden_update_user", 
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
