<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_202415_hints_table extends Migration
{
    public function up()
    {
        /**********************************************************************/
        /* Nood enveloppen 
        /* In deze tabel staan de de hints gedefinieerd.
        /**********************************************************************/

	$this->createTable('tbl_nood_envelop', [
            'nood_envelop_ID'       => $this->primaryKey(),
            'nood_envelop_name'     => $this->string(255)->notNull(),
            'event_ID'              => $this->integer(11)->notNull(),
            'route_ID'              => $this->integer(11)->notNull(),
            'nood_envelop_volgorde' => $this->integer(11),
            'coordinaat'            => $this->string(255)->notNull(),
            'opmerkingen'           => $this->string(1050)->notNull(),
            'score'                 => $this->integer(11)->notNull(),
            'create_time'           => $this->dateTime(),
            'create_user_ID'        => $this->integer(11),
            'update_time'           => $this->dateTime(),
            'update_user_ID'        => $this->integer(11),
        ],
        'ENGINE=InnoDB');

        $this->createIndex(
            'nood_envelop_name',
            'tbl_nood_envelop',
            ['nood_envelop_name', 'event_ID', 'route_ID'],
            true);

        /*********************** table friens_list ****************************/
  
	$this->addForeignKey(
            "fk_nood_envelop_event_id", 
            "tbl_nood_envelop", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");						

	$this->addForeignKey(
            "fk_nood_envelop_route", 
            "tbl_nood_envelop", 
            "route_ID",
            "tbl_route", 
            "route_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_nood_envelop_create_user", 
            "tbl_nood_envelop", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");
	
	$this->addForeignKey(
            "fk_nood_envelop_update_user", 
            "tbl_nood_envelop", 
            "update_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");

        /**********************************************************************/
        /* open nood enveloppen
        /* In deze tabel staan de enveloppen die door een groepje zijn geopend. 
        /**********************************************************************/

	$this->createTable('tbl_open_nood_envelop', [
            'open_nood_envelop_ID'  => $this->primaryKey(),
            'nood_envelop_ID'       => $this->integer(11)->notNull(),
            'event_ID'              => $this->integer(11)->notNull(),
            'group_ID'              => $this->integer(11)->notNull(),
            'opened'                => $this->boolean(),
            'create_time'           => $this->dateTime(),
            'create_user_ID'        => $this->integer(11),
            'update_time'           => $this->dateTime(),
            'update_user_ID'        => $this->integer(11),
	],    
        'ENGINE=InnoDB');
        $this->createIndex(
            'nood_envelop_ID', 
            'tbl_open_nood_envelop', 
            ['nood_envelop_ID', 'group_ID'], 
            true);
        
	$this->addForeignKey(
            "fk_open_envelop_id", 
            "tbl_open_nood_envelop", 
             "nood_envelop_ID",
             "tbl_nood_envelop", 
             "nood_envelop_ID", 
             "RESTRICT", 
             "CASCADE");

	$this->addForeignKey(
            "fk_open_nood_envelop_event_id", 
            "tbl_open_nood_envelop", 
            "event_ID",
            "tbl_event_names", 
            "event_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_open_nood_envelop_group_id", 
            "tbl_open_nood_envelop", 
            "group_ID",
            "tbl_groups", 
            "group_ID", 
            "RESTRICT", 
            "CASCADE");

	$this->addForeignKey(
            "fk_open_envelop_create_user", 
            "tbl_open_nood_envelop", 
            "create_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");
	
	$this->addForeignKey(
            "fk_open_envelop_update_user", 
            "tbl_open_nood_envelop", 
            "update_user_ID",
            "tbl_users", 
            "user_ID", 
            "RESTRICT", 
            "CASCADE");

    }

    public function down()
    {
        $this->truncateTable('tbl_open_nood_envelop');
        $this->dropTable('tbl_open_nood_envelop');
        
        $this->truncateTable('tbl_nood_envelop');
        $this->dropTable('tbl_nood_envelop');
    }
}
