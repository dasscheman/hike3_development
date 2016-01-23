<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202345_posten_table extends Migration
{
    public function up()
    {
        /**
         * Hier kunnen een aantal posten ingevoerd worden. Dit luistert niet 
         * heel nauw. Deelnemers krijgen niet te zien welke posten er zijn, pas 
         * als ze hem gepasseerd zijn. Maak bij voorkeur te veel posten aan. 
         * De posten die je uiteindelijk niet gebruikt hebben geen invloed. 
         * Tijdens het spel posten bijmaken is lastiger.
         */
	$this->createTable('tbl_posten', [
            'post_ID'   	=> $this->primaryKey(),
            'post_name'         => $this->string()->notNull(),
            'event_ID'          => $this->integer(11)->notNull(),
            'date'         	=> $this->date(),
            'post_volgorde'     => $this->integer(11),
            'score'             => $this->integer(11),
            'create_time'       => $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ],
        'ENGINE=InnoDB');
        
        $this->createIndex(
            'post_name', 
            'tbl_posten',
            ['post_name', 'event_ID', 'date'], 
            true);    
        
	$this->addForeignKey(
            "fk_posten_event_name", 
            "tbl_posten", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_posten_create_user", 
            "tbl_posten", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");
	
	$this->addForeignKey(
            "fk_posten_update_user", 
            "tbl_posten", 
            "update_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE"); 

        /**
         * Hier kan je aangeven welke groepen langs welke posten komen en 
         * hoelaat
         */						
	$this->createTable('tbl_post_passage', [
            'posten_passage_ID' => $this->primaryKey(),
            'post_ID'           => $this->integer(11)->notNull(),
            'event_ID'          => $this->integer(11)->notNull(),
            'group_ID'          => $this->integer(11)->notNull(),
            'gepasseerd'        => $this->boolean()->notNull(),
            'binnenkomst'       => $this->dateTime(),
            'vertrek'           => $this->dateTime(),
            'create_time'       => $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ],
        'ENGINE=InnoDB'); 

        $this->createIndex(
            'post_ID', 
            'tbl_post_passage', 
            ['post_ID', 'event_ID', 'group_ID'], 
            true);
    
	$this->addForeignKey(
            "fk_post_passage_post_id", 
            "tbl_post_passage", 
            "post_ID",
            "tbl_posten", 
            "post_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_post_passage_event_id", 
            "tbl_post_passage", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");
    
	$this->addForeignKey(
            "fk_post_passage_group_name", 
            "tbl_post_passage", 
            "group_ID",
            "tbl_groups", 
            "group_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_post_passage_create_user", 
            "tbl_post_passage", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");
    
	$this->addForeignKey(
            "fk_post_passage_update_user", 
            "tbl_post_passage", 
            "update_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE"); 
    }

    public function down()
    {        
        $this->truncateTable('tbl_post_passage');
        $this->dropTable('tbl_post_passage');
        
        $this->truncateTable('tbl_posten');
        $this->dropTable('tbl_posten');
    }
}
