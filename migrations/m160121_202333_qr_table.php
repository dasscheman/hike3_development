<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202333_qr_table extends Migration
{
    public function up()
    {

/*****************************************************************************/
/* Vriendenlijst
/* In deze tabel staan de vriendschappen gedefinieerd. 
/* 
/*****************************************************************************/

	$this->createTable(
	    'tbl_qr',
	    array(
			'qr_ID'			=> 'int(11) NOT NULL AUTO_INCREMENT',
			'qr_name'		=> 'varchar(255) NOT NULL',
			'qr_code'		=> 'varchar(255) NOT NULL',
			'event_ID'		=> 'int(11) NOT NULL',
			'route_ID' 		=> 'int(11) NOT NULL',
			'qr_volgorde'	=> 'int(11) DEFAULT NULL',
			'score'			=> 'int(11) NOT NULL',
			'create_time'	=> 'datetime DEFAULT NULL',
			'create_user_ID'=> 'int(11) DEFAULT NULL',
			'update_time'	=> 'datetime DEFAULT NULL',
			'update_user_ID'=> 'int(11) DEFAULT NULL',
			'PRIMARY KEY (`qr_ID`)',
			'UNIQUE KEY `qr_code` (`qr_code`,`event_ID`)'),
    	'ENGINE=InnoDB');


	$this->addForeignKey("fk_qr_event_id", 
			     "tbl_qr", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_qr_route", 
			     "tbl_qr", 
			     "route_ID",
			     "tbl_route", 
			     "route_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_qr_create_user", 
			     "tbl_qr", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	
	$this->addForeignKey("fk_qr_update_user", 
			     "tbl_qr", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");

/*****************************************************************************/
/* Vriendenlijst
/* In deze tabel staan de vriendschappen gedefinieerd. 
/* 
/*****************************************************************************/

	$this->createTable(
	    'tbl_qr_check',
	    array(
			'qr_check_ID'	=> 'int(11) NOT NULL AUTO_INCREMENT',
			'qr_ID'			=> 'int(11) NOT NULL',
			'event_ID'		=> 'int(11) NOT NULL',
			'group_ID'		=> 'int(11) NOT NULL',
			'create_time'	=> 'datetime DEFAULT NULL',
			'create_user_ID'=> 'int(11) DEFAULT NULL',
			'update_time'	=> 'datetime DEFAULT NULL',
			'update_user_ID'=> 'int(11) DEFAULT NULL',
			'PRIMARY KEY (`qr_check_ID`)',
			'UNIQUE KEY `qr_ID` (`qr_ID`,`group_ID`)'),
	    'ENGINE=InnoDB');


	$this->addForeignKey("fk_qr_check_qr_id", 
			     "tbl_qr_check", 
			     "qr_ID",
			     "tbl_qr", 
			     "qr_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_qr_check_qr_event_id", 
			     "tbl_qr_check", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_qr_check_qr_group_id", 
			     "tbl_qr_check", 
			     "group_ID",
			     "tbl_groups", 
			     "group_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_qr_check_create_user", 
			     "tbl_qr_check", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	
	$this->addForeignKey("fk_qr_check_update_user", 
			     "tbl_qr_check", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
    }
    

    public function down()
    {
        $this->truncateTable('tbl_qr');
        $this->dropTable('tbl_qr');
        
        $this->truncateTable('tbl_qr_check');
        $this->dropTable('tbl_qr_check');
    }
}
