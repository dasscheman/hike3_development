<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202415_hints_table extends Migration
{
    public function up()
    {


/*****************************************************************************/
/* Nood enveloppen 
/* In deze tabel staan de de hints gedefinieerd. 
/* 
/*****************************************************************************/

	$this->createTable(
	    'tbl_nood_envelop',
	    array(
			'nood_envelop_ID'   	=> 'int(11) NOT NULL AUTO_INCREMENT',
			'nood_envelop_name'		=> 'varchar(255) NOT NULL',
			'event_ID' 	    		=> 'int(11) NOT NULL',
			'route_ID' 				=> 'int(11) NOT NULL',
			'nood_envelop_volgorde'	=> 'int(11) DEFAULT NULL',
			'coordinaat' 			=> 'varchar(255) NOT NULL',
			'opmerkingen' 			=> 'varchar(255) NOT NULL',
			'score' 				=> 'int(11) NOT NULL',
			'create_time' 			=> 'datetime DEFAULT NULL',
			'create_user_ID' 		=> 'int(11) DEFAULT NULL',
			'update_time'			=> 'datetime DEFAULT NULL',
			'update_user_ID' 		=> 'int(11) DEFAULT NULL',
			'PRIMARY KEY (`nood_envelop_ID`)',
			'UNIQUE KEY `envelop_id` (`nood_envelop_name`,`event_ID`)'),
	    'ENGINE=InnoDB');

/*********************** table friens_list ****************************/
  
	$this->addForeignKey("fk_nood_envelop_event_id", 
			     "tbl_nood_envelop", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");						

	$this->addForeignKey("fk_nood_envelop_route", 
			     "tbl_nood_envelop", 
			     "route_ID",
			     "tbl_route", 
			     "route_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_nood_envelop_create_user", 
			     "tbl_nood_envelop", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	
	$this->addForeignKey("fk_nood_envelop_update_user", 
			     "tbl_nood_envelop", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");

/*****************************************************************************/
/* open nood enveloppen
/* In deze tabel staan de enveloppen die door een groepje zijn geopend. 
/* 
/*****************************************************************************/

	$this->createTable(
	    'tbl_open_nood_envelop',
	    array(
			'open_nood_envelop_ID'	=> 'int(11) NOT NULL AUTO_INCREMENT',
			'nood_envelop_ID'   	=> 'int(11) NOT NULL',
			'event_ID'   	    	=> 'int(11) NOT NULL',
			'group_ID'   			=> 'int(11) NOT NULL',
			'opened'   				=> 'tinyint(1) DEFAULT NULL',
			'create_time'   		=> 'datetime DEFAULT NULL',
			'create_user_ID'   		=> 'int(11) DEFAULT NULL',
			'update_time'   		=> 'datetime DEFAULT NULL',
			'update_user_ID'   		=> 'int(11) DEFAULT NULL',
			'PRIMARY KEY (`open_nood_envelop_ID`)',
			'UNIQUE KEY `nood_envelop_ID` (`nood_envelop_ID`,`group_ID`)',),
	    'ENGINE=InnoDB');


	$this->addForeignKey("fk_onood_envelop_id", 
			     "tbl_open_nood_envelop", 
			     "nood_envelop_ID",
			     "tbl_nood_envelop", 
			     "nood_envelop_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_open_nood_envelop_event_id", 
			     "tbl_open_nood_envelop", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_open_nood_envelop_group_id", 
			     "tbl_open_nood_envelop", 
			     "group_ID",
			     "tbl_groups", 
			     "group_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_onood_envelop_create_user", 
			     "tbl_open_nood_envelop", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	
	$this->addForeignKey("fk_onood_envelop_update_user", 
			     "tbl_open_nood_envelop", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");

    }

    public function down()
    {
        $this->truncateTable('tbl_nood_envelop');
        $this->dropTable('tbl_nood_envelop');
        
        $this->truncateTable('tbl_open_nood_envelop');
        $this->dropTable('tbl_open_nood_envelop');
    }
}
