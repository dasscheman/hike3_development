<?php

use yii\db\Schema;
use yii\db\Migration;

class m181022_202415_route_track_table extends Migration
{
    public function up()
    {
        /**********************************************************************/
        /* Track for a route
        /* In deze tabel staan de tracks van een route
        /**********************************************************************/

	$this->createTable('tbl_route_track', [
            'route_track_ID'    => $this->primaryKey(),
            'event_ID'          => $this->integer(11)->notNull(),
            'name'              => $this->string(255),
            'elevation'         => $this->decimal(15, 2),
            'latitude'          => $this->decimal(11, 9),
            'longitude'         => $this->decimal(11, 9),
            'timestamp'         => $this->dateTime(),
            'type'              => $this->integer(11),
            'create_time'       => $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ],
        'ENGINE=InnoDB');

        /*********************** Foreign keys ****************************/

   $this->addForeignKey(
        "fk_route_track_event_id",
        "tbl_route_track",
        "event_ID",
        "tbl_event_names",
        "event_ID",
        "RESTRICT",
        "CASCADE");

	$this->addForeignKey(
        "fk_route_track_create_user",
        "tbl_route_track",
        "create_user_ID",
        "tbl_users",
        "user_ID",
        "RESTRICT",
        "CASCADE");

	$this->addForeignKey(
        "fk_route_track_update_user",
        "tbl_route_track",
        "update_user_ID",
        "tbl_users",
        "user_ID",
        "RESTRICT",
        "CASCADE");


    }

    public function down()
    {
        $this->truncateTable('tbl_route_track');
        $this->dropTable('tbl_route_track');
    }
}
