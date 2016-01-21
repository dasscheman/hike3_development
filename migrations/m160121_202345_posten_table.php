<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202345_posten_table extends Migration
{
    public function up()
    {

/*****************************************************************************/
/* Hier kunnen een aantal posten ingevoerd worden. Dit luisterd niet heel nauw  
/* Deelnemers krijgen niet te zien welke posten er zijn, pas als ze hem 
/* gepasseerd zijn. Maak bij voorkeur te veel posten aan. De posten die je 
/* uiteindelijk niet gebruikt hebben geen invloed. Tijdens het spel posten 
/* bijmaken is lastiger.  
/*********** TIP TIP TIP TIP**************************************************/
/* Definieer startpunt en eindpunt van elke dag ook als een post. 
/*****************************************************************************/  						

	$this->createTable(
	    'tbl_posten', 
	    array( 
			'post_ID'   	=> 'int(11) NOT NULL AUTO_INCREMENT', 	
			'post_name'     => 'string NOT NULL',	
			'event_ID'      => 'int(11) NOT NULL',	
			'date'         	=> 'date DEFAULT NULL',
			'post_volgorde' => 'int(11) DEFAULT NULL',  
			'score'         => 'int(11) DEFAULT NULL', 
			'create_time'   => 'datetime DEFAULT NULL',
			'create_user_ID'=> 'int(11) DEFAULT NULL',
			'update_time'   => 'datetime DEFAULT NULL',
			'update_user_ID'=> 'int(11) DEFAULT NULL',
			'PRIMARY KEY (`post_ID`)',
			'UNIQUE KEY(`post_name`, `event_ID`, `date`)',), 
	    'ENGINE=InnoDB'); 
                      
 
/*********************** table posten  **********************************/
 
	$this->addForeignKey("fk_posten_event_name", 
			     "tbl_posten", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");
			     
	$this->addForeignKey("fk_posten_create_user", 
			     "tbl_posten", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	
	$this->addForeignKey("fk_posten_update_user", 
			     "tbl_posten", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE"); 

/*****************************************************************************/
/* Hier kan je aangeven welke groepen langs welke posten komen en hoelaat
/*****************************************************************************/  	
							
	$this->createTable(
	    'tbl_post_passage', 
	    array(
			'posten_passage_ID' => 'int(11) NOT NULL AUTO_INCREMENT', 	
			'post_ID'           => 'int(11) NOT NULL', 		
			'event_ID'          => 'int(11) NOT NULL',	
			'group_ID'  	    => 'int(11) NOT NULL',
			'gepasseerd'  	    => 'tinyint(11) NOT NULL',
			'binnenkomst'       => 'datetime DEFAULT NULL',
			'vertrek'           => 'datetime DEFAULT NULL',
			'create_time'       => 'datetime DEFAULT NULL',
			'create_user_ID'    => 'int(11) DEFAULT NULL',
			'update_time'       => 'datetime DEFAULT NULL',
			'update_user_ID'    => 'int(11) DEFAULT NULL',
			'PRIMARY KEY (`posten_passage_ID`)',
			'UNIQUE KEY (`post_ID`, `event_ID`, `group_ID`)',), 
	    'ENGINE=InnoDB'); 

/*********************** table post_passages ****************************/
 
	$this->addForeignKey("fk_post_passage_post_id", 
			     "tbl_post_passage", 
			     "post_ID",
			     "tbl_posten", 
			     "post_ID", 
			     "RESTRICT", 
			     "CASCADE");
    
	$this->addForeignKey("fk_post_passage_event_id", 
			     "tbl_post_passage", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");
    
	$this->addForeignKey("fk_post_passage_group_name", 
			     "tbl_post_passage", 
			     "group_ID",
			     "tbl_groups", 
			     "group_ID", 
			     "RESTRICT", 
			     "CASCADE");

	$this->addForeignKey("fk_post_passage_create_user", 
			     "tbl_post_passage", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
    
	$this->addForeignKey("fk_post_passage_update_user", 
			     "tbl_post_passage", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE"); 
   
    }

    public function down()
    {
        $this->truncateTable('tbl_posten');
        $this->dropTable('tbl_posten');
        
        $this->truncateTable('tbl_post_passage');
        $this->dropTable('tbl_post_passage');
    }
}
