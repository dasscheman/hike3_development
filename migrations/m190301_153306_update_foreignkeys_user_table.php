<?php

//use Yii;
use yii\db\Migration;
use app\models\Users;


/**
 * Class m171104_143306_update_rbac_data
 */
class m190301_153306_update_foreignkeys_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            "fk_newsletter_create_user",
            "tbl_newsletter");

        $this->addForeignKey(
            "fk_newsletter_create_user",
            "tbl_newsletter",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );

        $this->dropForeignKey(
            "fk_newsletter_update_user",
            "tbl_newsletter");

        $this->addForeignKey(
            "fk_newsletter_update_user",
            "tbl_newsletter",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );


        $this->dropForeignKey(
            "fk_newsletter_mail_list_receive_user_id",
            "tbl_newsletter_mail_list");
        $this->addForeignKey(
            "fk_newsletter_mail_list_receive_user_id",
            "tbl_newsletter_mail_list",
            "user_id",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );

        $this->dropForeignKey(
            "fk_newsletter_mail_list_create_user",
            "tbl_newsletter_mail_list");
        $this->addForeignKey(
            "fk_newsletter_mail_list_create_user",
            "tbl_newsletter_mail_list",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
          );

        $this->dropForeignKey(
            "fk_newsletter_mail_list_update_user",
            "tbl_newsletter_mail_list");
        $this->addForeignKey(
            "fk_newsletter_mail_list_update_user",
            "tbl_newsletter_mail_list",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );

        $this->dropForeignKey(
            "fk_track_user",
            "tbl_track");

        $this->addForeignKey(
            "fk_track_user",
            "tbl_track",
            "user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );

        $this->dropForeignKey(
            "fk_track_create_user",
            "tbl_track");
        $this->addForeignKey(
            "fk_track_create_user",
            "tbl_track",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );

        $this->dropForeignKey(
            "fk_track_update_user",
            "tbl_track");
        $this->addForeignKey(
            "fk_track_update_user",
            "tbl_track",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );

        $this->dropForeignKey(
            "fk_route_track_create_user",
            "tbl_route_track");

       	$this->addForeignKey(
             "fk_route_track_create_user",
             "tbl_route_track",
             "create_user_ID",
             "user",
             "id",
             "RESTRICT",
             "CASCADE");

         $this->dropForeignKey(
             "fk_route_track_update_user",
             "tbl_route_track");
          $this->addForeignKey(
             "fk_route_track_update_user",
             "tbl_route_track",
             "update_user_ID",
             "user",
             "id",
             "RESTRICT",
             "CASCADE");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171104_143306_update_rbac_data cannot be reverted.\n";

        return false;
    }
    */
}
