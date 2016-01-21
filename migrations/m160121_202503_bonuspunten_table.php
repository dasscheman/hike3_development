<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202503_bonuspunten_table extends Migration
{
    public function up()
    {
          

/*****************************************************************************/
/* Bonuspunten Hier kunnen extra (straf)punten gegeven worden aan groepjes
/* Vul negatieve waarden in voor straf punten.
/* 
/*****************************************************************************/  	
							
	$this->createTable(
	    'tbl_bonuspunten', 
	    array(
			'bouspunten_ID' => 'int(11) NOT NULL AUTO_INCREMENT', 
			'event_ID'      => 'int(11) NOT NULL',
			'date'          => 'date DEFAULT NULL',									
			'post_ID'       => 'int(11) DEFAULT NULL',
			'group_ID'      => 'int(11) NOT NULL',
			'omschrijving'  => 'string NOT NULL',
			'score'         => 'int(11) DEFAULT NULL', 
			'create_time'   => 'datetime DEFAULT NULL',
			'create_user_ID'=> 'int(11) DEFAULT NULL',
			'update_time'   => 'datetime DEFAULT NULL',
			'update_user_ID'=> 'int(11) DEFAULT NULL',
			'PRIMARY KEY (`bouspunten_ID`)'), 
	    'ENGINE=InnoDB'); 
	
/*********************** table bonuspunten ****************************/
  
	$this->addForeignKey("fk_bonuspunten_event_id", 
			     "tbl_bonuspunten", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");
    
	$this->addForeignKey("fk_bonuspunten_post_id", 
			     "tbl_bonuspunten", 
			     "post_ID",
			     "tbl_posten", 
			     "post_ID", 
			     "RESTRICT", 
			     "CASCADE");
    
	$this->addForeignKey("fk_bonuspunten_group_id", 
			     "tbl_bonuspunten", 
			     "group_ID",
			     "tbl_groups", 
			     "group_ID", 
			     "RESTRICT", 
			     "CASCADE");
    
	$this->addForeignKey("fk_bonuspunten_create_user", 
			     "tbl_bonuspunten", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
    
	$this->addForeignKey("fk_bonuspunten_update_user", 
			     "tbl_bonuspunten", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE"); 

    }

    public function down()
    {
        $this->truncateTable('tbl_bonuspunten');
        $this->dropTable('tbl_bonuspunten');
    }
}
