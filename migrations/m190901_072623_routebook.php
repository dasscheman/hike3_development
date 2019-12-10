<?php

use yii\db\Migration;

/**
 * Class m190901_072623_routebook
 */
class m190901_072623_routebook extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'tbl_routebook',
            [
                'routebook_ID'  => $this->primaryKey(),
                'event_ID'      => $this->integer(11)->notNull(),
                'route_ID'      => $this->integer(11)->notNull(),
                'tekst'         => $this->string(2555),
                'create_time'   => $this->dateTime(),
                'create_user_ID'=> $this->integer(11),
                'update_time'   => $this->dateTime(),
                'update_user_ID'=> $this->integer(11),
            ],
            'ENGINE=InnoDB'
        );


        $this->addForeignKey(
            "fk_routebook_event_id",
            "tbl_routebook",
            "event_ID",
            "tbl_event_names",
            "event_ID",
            "RESTRICT",
            "CASCADE"
        );

        $this->addForeignKey(
            "fk_routebook_route_id",
            "tbl_routebook",
            "route_ID",
            "tbl_route",
            "route_ID",
            "RESTRICT",
            "CASCADE"
        );

        $this->addForeignKey(
            "fk_routebook_create_user",
            "tbl_routebook",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );

        $this->addForeignKey(
            "fk_routebook_update_user",
            "tbl_routebook",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );
    }

    public function safeDown()
    {
        $this->truncateTable('tbl_routebook');
        $this->dropTable('tbl_routebook');
    }
}
