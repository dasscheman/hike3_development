<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_181534_deelnemers_event_table extends Migration
{
    public function up()
    {

        /*********************** table deelnemers_event ******************************/                         
        /*****************************************************************************/
        /* In de tabel tbl_deelnemers_event is een lijst van events met deelnemers.   
        /* Een deelnemer kan maar 1 keer per event voorkomen. En een deelnemer heeft 
        /* een rol. Deze rol bepaald wat een deelnemer kan doen als hij ingelogd is.
        /* Als de rol een loper is, dan kan er ook aangegeven worden in welke groep 
        /* deze persoon zit. 
        /*****************************************************************************/                          
                            
	$this->createTable('tbl_deelnemers_event', [
            'deelnemers_ID'     => $this->primaryKey(),
            'event_ID'          => $this->integer(11)->notNull(),
            'user_ID'           => $this->integer(11)->notNull(),
            'rol'               => $this->integer(11),
            'group_ID'          => $this->integer(11),
            'create_time'       => $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ],                     
        'ENGINE=InnoDB');                

        $this->createIndex('event_ID', 'tbl_deelnemers_event', ['event_ID', 'user_ID'], true);
        
        /*****************************************************************************/
        /* add foreignkeys
        /*****************************************************************************/
                        
	$this->addForeignKey("fk_deelnemers_event_event_ID", 
			     "tbl_deelnemers_event", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");             
      
	$this->addForeignKey("fk_deelnemers_user_id", 
			     "tbl_deelnemers_event", 
			     "user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
			      
	$this->addForeignKey("fk_deelnemers_group_ID", 
			     "tbl_deelnemers_event", 
			     "group_ID",
			     "tbl_groups", 
			     "group_ID", 
			     "RESTRICT", 
			     "CASCADE");    
			 
	$this->addForeignKey("fk_deelnemers_create_user", 
			     "tbl_deelnemers_event", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
       
	$this->addForeignKey("fk_deelnemers_update_user", 
			     "tbl_deelnemers_event", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");  
    }

    public function down()
    {
        $this->truncateTable('tbl_deelnemers_event');
        $this->dropTable('tbl_deelnemers_event');
    }
}
