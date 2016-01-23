<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_175529_groups_table extends Migration
{
    public function up()
    {
        /* 
         * table groups 
         * In de tabel tbl_groups is gedefinieerd welke groepen er voor een 
         * evenement zijn
         * 
         */
                            
	$this->createTable('tbl_groups', [
            'group_ID'          => $this->primaryKey(),
            'group_name'        => $this->string(255)->notNull(),
            'event_ID'          => $this->integer(11)->notNull(),
            'create_time'       => $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ], 
        'ENGINE=InnoDB'); 

        // Create index and make it unique.
            $this->createIndex(
                'event_ID', 
                'tbl_groups', 
                ['event_ID', 'group_name'], 
                true);
        
        /* 
         * add foreignkays:
         * event_name en user_name in tbl_groups refereerd aan
         * event_name in tbl_events en user_name in tbl_users 
         * 
         */
   
	$this->addForeignKey("fk_groups_event_ID", 
			     "tbl_groups", 
			     "event_ID",
			     "tbl_event_names", 
			     "event_ID", 
			     "RESTRICT", 
			     "CASCADE");   
			     
	$this->addForeignKey("fk_groups_create_user", 
			     "tbl_groups", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
       
	$this->addForeignKey("fk_groups_update_user", 
			     "tbl_groups", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");      
    
    }

    public function down()
    {
        $this->truncateTable('tbl_groups');
        $this->dropTable('tbl_groups');
    }
}