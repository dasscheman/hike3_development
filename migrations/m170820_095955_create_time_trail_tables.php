<?php

use yii\db\Migration;

class m170820_095955_create_time_trail_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('tbl_time_trail', [
                'time_trail_ID'     => $this->primaryKey(),
                'time_trail_name'	=> $this->string(255)->notNull(),
                'event_ID'          => $this->integer(11)->notNull(),
                'create_time'       => $this->dateTime(),
                'create_user_ID'    => $this->integer(11),
                'update_time'       => $this->dateTime(),
                'update_user_ID'    => $this->integer(11),
            ],
            'ENGINE=InnoDB');

        $this->createIndex('time_trail_name', 'tbl_time_trail', ['time_trail_name', 'event_ID'], true);

        $this->addForeignKey(
                "fk_time_trail_event_id",
                "tbl_time_trail",
                "event_ID",
                "tbl_event_names",
                "event_ID",
                "RESTRICT",
                "CASCADE");

        $this->addForeignKey(
                "fk_time_trail_create_user",
                "tbl_time_trail",
                "create_user_ID",
                "tbl_users",
                "user_ID",
                "RESTRICT",
                "CASCADE");

        $this->addForeignKey(
                "fk_time_trail_update_user",
                "tbl_time_trail",
                "update_user_ID",
                "tbl_users",
                "user_ID",
                "RESTRICT",
                "CASCADE");



     $this->createTable('tbl_time_trail_item', [
                'time_trail_item_ID'    => $this->primaryKey(),
                'time_trail_ID'     	=> $this->integer(11),
                'time_trail_item_name'	=> $this->string(255)->notNull(),
                'code'                  => $this->string(255)->notNull(),
                'instruction'           => $this->string(255)->notNull(),
                'event_ID'              => $this->integer(11)->notNull(),
                'volgorde'          => $this->integer(11),
                'score'             => $this->integer(11)->notNull(),
                'max_time'          => $this->time(),
                'create_time'       => $this->dateTime(),
                'create_user_ID'    => $this->integer(11),
                'update_time'       => $this->dateTime(),
                'update_user_ID'    => $this->integer(11),
            ],
            'ENGINE=InnoDB');

        $this->createIndex('code', 'tbl_time_trail_item', ['code', 'event_ID'], true);

        $this->addForeignKey(
                "fk_time_trail_item_time_trail_id",
                "tbl_time_trail_item",
                "time_trail_ID",
                "tbl_time_trail",
                "time_trail_ID",
                "RESTRICT",
                "CASCADE");

        $this->addForeignKey(
                "fk_time_trail_item_event_id",
                "tbl_time_trail_item",
                "event_ID",
                "tbl_event_names",
                "event_ID",
                "RESTRICT",
                "CASCADE");

        $this->addForeignKey(
                "fk_time_trail_item_create_user",
                "tbl_time_trail_item",
                "create_user_ID",
                "tbl_users",
                "user_ID",
                "RESTRICT",
                "CASCADE");

        $this->addForeignKey(
                "fk_time_trail_item_update_user",
                "tbl_time_trail_item",
                "update_user_ID",
                "tbl_users",
                "user_ID",
                "RESTRICT",
                "CASCADE");


        $this->createTable('tbl_time_trail_check', [
                'time_trail_check_ID'	=> $this->primaryKey(),
                'time_trail_item_ID'    => $this->integer(11)->notNull(),
                'event_ID'              => $this->integer(11)->notNull(),
                'group_ID'              => $this->integer(11)->notNull(),
                'start_time'            => $this->dateTime(),
                'end_time'          => $this->dateTime(),
                'succeded'          => $this->boolean(),
                'create_time'       => $this->dateTime(),
                'create_user_ID'    => $this->integer(11),
                'update_time'       => $this->dateTime(),
                'update_user_ID'    => $this->integer(11),
            ],
            'ENGINE=InnoDB');

            $this->createIndex('time_trail_item_ID', 'tbl_time_trail_check', ['time_trail_item_ID', 'group_ID'], true);

        $this->addForeignKey(
                "fk_time_trail_check_time_trail_item_id",
                "tbl_time_trail_check",
                "time_trail_item_ID",
                "tbl_time_trail_item",
                "time_trail_item_ID",
                "RESTRICT",
                "CASCADE");

        $this->addForeignKey(
                "fk_time_trail_check_time_trail_check_event_id",
                "tbl_time_trail_check",
                "event_ID",
                "tbl_event_names",
                "event_ID",
                "RESTRICT",
                "CASCADE");

        $this->addForeignKey(
                "fk_time_trail_check_time_trail_check_group_id",
                "tbl_time_trail_check",
                "group_ID",
                "tbl_groups",
                "group_ID",
                "RESTRICT",
                "CASCADE");

        $this->addForeignKey(
                "fk_time_trail_check_create_user",
                "tbl_time_trail_check",
                "create_user_ID",
                "tbl_users",
                "user_ID",
                "RESTRICT",
                "CASCADE");

        $this->addForeignKey(
                "fk_time_trail_check_update_user",
                "tbl_time_trail_check",
                "update_user_ID",
                "tbl_users",
                "user_ID",
                "RESTRICT",
                "CASCADE");
    }

    public function safeDown()
    {
        echo "m170820_095955_create_time_trail_tables cannot be reverted.\n";
        $this->truncateTable('tbl_time_trail_check');
        $this->dropTable('tbl_time_trail_check');

        $this->truncateTable('tbl_time_trail_item');
        $this->dropTable('tbl_time_trail_item');

        $this->truncateTable('tbl_time_trail');
        $this->dropTable('tbl_time_trail');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170820_095955_create_time_trail_tables cannot be reverted.\n";

        return false;
    }
    */
}
