<?php

use yii\db\Migration;

class m180522_202503_track_table extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            'tbl_track',
            [
                'track_ID'      => $this->primaryKey(),
                'event_ID'      => $this->integer(11)->notNull(),
                'user_ID'       => $this->integer(11)->notNull(),
                'group_ID'      => $this->integer(11),
                'latitude'      => $this->decimal(10, 8),
                'longitude'     => $this->decimal(11, 8),
                'accuracy'      => $this->integer(11),
                'timestamp'     => $this->integer(11),
                'create_time'   => $this->dateTime(),
                'create_user_ID'=> $this->integer(11),
                'update_time'   => $this->dateTime(),
                'update_user_ID'=> $this->integer(11),
            ],
            'ENGINE=InnoDB'
        );


        $this->addForeignKey(
            "fk_track_event_id",
            "tbl_track",
            "event_ID",
            "tbl_event_names",
            "event_ID",
            "RESTRICT",
            "CASCADE"
        );
    
        $this->addForeignKey(
            "fk_track_group_id",
            "tbl_track",
            "group_ID",
            "tbl_groups",
            "group_ID",
            "RESTRICT",
            "CASCADE"
        );
    
        $this->addForeignKey(
            "fk_track_user",
            "tbl_track",
            "user_ID",
            "tbl_users",
            "user_ID",
            "RESTRICT",
            "CASCADE"
        );

        $this->addForeignKey(
            "fk_track_create_user",
            "tbl_track",
            "create_user_ID",
            "tbl_users",
            "user_ID",
            "RESTRICT",
            "CASCADE"
        );
    
        $this->addForeignKey(
            "fk_track_update_user",
            "tbl_track",
            "update_user_ID",
            "tbl_users",
            "user_ID",
            "RESTRICT",
            "CASCADE"
        );
    }

    public function safeDown()
    {
        $this->truncateTable('tbl_track');
        $this->dropTable('tbl_track');
    }
}
