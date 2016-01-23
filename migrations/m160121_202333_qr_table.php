<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202333_qr_table extends Migration
{
    public function up()
    {
	$this->createTable('tbl_qr', [
            'qr_ID'             => $this->primaryKey(),
            'qr_name'		=> $this->string(255)->notNull(),
            'qr_code'		=> $this->string(255)->notNull(),
            'event_ID'		=> $this->integer(11)->notNull(),
            'route_ID' 		=> $this->integer(11)->notNull(),
            'qr_volgorde'	=> $this->integer(11),
            'score'             => $this->integer(11)->notNull(),
            'create_time'       => $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ],
        'ENGINE=InnoDB');

        $this->createIndex('qr_code', 'tbl_qr', ['qr_code', 'event_ID'], true);

	$this->addForeignKey(
            "fk_qr_event_id", 
            "tbl_qr", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_qr_route", 
            "tbl_qr", 
            "route_ID",
            "tbl_route", 
            "route_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_qr_create_user", 
            "tbl_qr", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_qr_update_user", 
            "tbl_qr", 
            "update_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->createTable('tbl_qr_check', [
            'qr_check_ID'	=> $this->primaryKey(),
            'qr_ID'             => $this->integer(11)->notNull(),
            'event_ID'          => $this->integer(11)->notNull(),
            'group_ID'          => $this->integer(11)->notNull(),
            'create_time'       => $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ],
        'ENGINE=InnoDB');

        $this->createIndex('qr_ID', 'tbl_qr_check', ['qr_ID', 'group_ID'], true);
        
	$this->addForeignKey(
            "fk_qr_check_qr_id", 
            "tbl_qr_check", 
            "qr_ID",
            "tbl_qr", 
            "qr_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_qr_check_qr_event_id", 
            "tbl_qr_check", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_qr_check_qr_group_id", 
            "tbl_qr_check", 
            "group_ID",
            "tbl_groups", 
            "group_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_qr_check_create_user", 
            "tbl_qr_check", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_qr_check_update_user", 
            "tbl_qr_check", 
            "update_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");
    }
    

    public function down()
    {
        $this->truncateTable('tbl_qr_check');
        $this->dropTable('tbl_qr_check');
        
        $this->truncateTable('tbl_qr');
        $this->dropTable('tbl_qr');
    }
}
