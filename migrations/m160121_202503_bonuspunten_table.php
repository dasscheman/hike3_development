<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202503_bonuspunten_table extends Migration
{
    public function up()
    {
        /* 
         * Bonuspunten Hier kunnen extra (straf)punten gegeven worden aan 
         * groepjes. Vul negatieve waarden in voor straf punten.
         * 
         */	
        
	$this->createTable('tbl_bonuspunten', [
            'bouspunten_ID'     => $this->primaryKey(),
            'event_ID'          => $this->integer(11)->notNull(),
            'date'              => $this->date(),
            'post_ID'           => $this->integer(11),
            'group_ID'          => $this->integer(11)->notNull(),
            'omschrijving'      => $this->string(255)->notNull(),
            'score'             => $this->integer(11),
            'create_time'       => $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ],
        'ENGINE=InnoDB'); 
        
	$this->addForeignKey(
            "fk_bonuspunten_event_id", 
            "tbl_bonuspunten", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");
    
	$this->addForeignKey(
            "fk_bonuspunten_post_id", 
            "tbl_bonuspunten", 
            "post_ID",
            "tbl_posten", 
            "post_ID", 
            "RESTRICT", 
            "CASCADE");
    
	$this->addForeignKey(
            "fk_bonuspunten_group_id", 
            "tbl_bonuspunten", 
            "group_ID",
            "tbl_groups", 
            "group_ID", 
            "RESTRICT", 
            "CASCADE");
    
	$this->addForeignKey(
            "fk_bonuspunten_create_user", 
            "tbl_bonuspunten", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");
    
	$this->addForeignKey(
            "fk_bonuspunten_update_user", 
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
