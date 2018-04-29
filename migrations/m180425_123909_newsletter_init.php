<?php

use yii\db\Migration;
use yii\db\Schema;

class m180425_123909_newsletter_init extends Migration
{
    public function up()
    {
        $this->createTable(
            'tbl_newsletter',
            [
                'id' 					=> $this->primaryKey(),
                'subject'             	=> $this->string(45)->notNull(),
                'body'        			=> $this->string(1050)->notNull(),
                'is_active'             => $this->boolean(),
                'schedule_date_time'  	=> $this->dateTime(),
                'create_time'           => $this->dateTime(),
                'create_user_ID'        => $this->integer(11),
                'update_time'           => $this->dateTime(),
                'update_user_ID'        => $this->integer(11),
            ],
            'ENGINE=InnoDB'
        );

        $this->createTable(
            'tbl_newsletter_mail_list',
            [
                'id' 				=> $this->primaryKey(),
                'newsletter_id'     => $this->integer(11)->notNull(),
                'user_id'           => $this->integer(11)->notNull(),
                'email'        		=> $this->string(150)->notNull(),
                'send_time' 		=> $this->dateTime(),
                'is_sent'         	=> $this->boolean(),
                'create_time'       => $this->dateTime(),
                'create_user_ID'    => $this->integer(11),
                'update_time'       => $this->dateTime(),
                'update_user_ID'    => $this->integer(11),
            ],
            'ENGINE=InnoDB'
        );

        $this->addForeignKey(
            "fk_newsletter_create_user",
            "tbl_newsletter",
            "create_user_ID",
            "tbl_users",
            "user_ID",
            "RESTRICT",
            "CASCADE"
        );

        $this->addForeignKey(
            "fk_newsletter_update_user",
            "tbl_newsletter",
            "update_user_ID",
            "tbl_users",
            "user_ID",
            "RESTRICT",
            "CASCADE"
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk_newsletter_mail_list_id',
            'tbl_newsletter_mail_list',
            'newsletter_id',
            'tbl_newsletter',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            "fk_newsletter_mail_list_receive_user_id",
            "tbl_newsletter_mail_list",
            "user_id",
            "tbl_users",
            "user_ID",
            "RESTRICT",
            "CASCADE"
        );

        $this->addForeignKey(
            "fk_newsletter_mail_list_create_user",
            "tbl_newsletter_mail_list",
            "create_user_ID",
            "tbl_users",
            "user_ID",
            "RESTRICT",
            "CASCADE"
        );

        $this->addForeignKey(
            "fk_newsletter_mail_list_update_user",
            "tbl_newsletter_mail_list",
            "update_user_ID",
            "tbl_users",
            "user_ID",
            "RESTRICT",
            "CASCADE"
        );
    }

    public function down()
    {
        $this->dropTable('tbl_newsletter_mail_list');
        $this->dropTable('tbl_newsletter');
    }
}
