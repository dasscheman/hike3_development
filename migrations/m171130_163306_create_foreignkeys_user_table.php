<?php

//use Yii;
use yii\db\Migration;
use app\models\Users;


/**
 * Class m171104_143306_update_rbac_data
 */
class m171130_163306_create_foreignkeys_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addForeignKey(
            "fk_events_create_user_name",
            "tbl_event_names",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_events_update_user_name",
            "tbl_event_names",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_groups_create_user",
            "tbl_groups",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_groups_update_user",
            "tbl_groups",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_deelnemers_user_id",
            "tbl_deelnemers_event",
            "user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_deelnemers_create_user",
            "tbl_deelnemers_event",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_deelnemers_update_user",
            "tbl_deelnemers_event",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

    	$this->addForeignKey(
            "fk_friend_list_user",
            "tbl_friend_list",
            "user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_friend_list_friends_with_user",
            "tbl_friend_list",
            "friends_with_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_friend_list_create_user",
            "tbl_friend_list",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_friend_list_update_user",
            "tbl_friend_list",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_route_create_user_name",
            "tbl_route",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_route_update_user_name",
            "tbl_route",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_open_vragen_create_user",
            "tbl_open_vragen",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_open_vragen_update_user",
            "tbl_open_vragen",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

    	$this->addForeignKey(
            "fk_open_vragen_antwoorden_create_user",
            "tbl_open_vragen_antwoorden",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_open_vragen_antwoorden_update_user",
            "tbl_open_vragen_antwoorden",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_qr_create_user",
            "tbl_qr",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_qr_update_user",
            "tbl_qr",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_qr_check_create_user",
            "tbl_qr_check",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_qr_check_update_user",
            "tbl_qr_check",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_posten_create_user",
            "tbl_posten",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_posten_update_user",
            "tbl_posten",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_post_passage_create_user",
            "tbl_post_passage",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_post_passage_update_user",
            "tbl_post_passage",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_nood_envelop_create_user",
            "tbl_nood_envelop",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_nood_envelop_update_user",
            "tbl_nood_envelop",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_open_envelop_create_user",
            "tbl_open_nood_envelop",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_open_envelop_update_user",
            "tbl_open_nood_envelop",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_bonuspunten_create_user",
            "tbl_bonuspunten",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_bonuspunten_update_user",
            "tbl_bonuspunten",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_time_trail_create_user",
            "tbl_time_trail",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_time_trail_update_user",
            "tbl_time_trail",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");


        $this->addForeignKey(
            "fk_time_trail_item_create_user",
            "tbl_time_trail_item",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_time_trail_item_update_user",
            "tbl_time_trail_item",
            "update_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");


        $this->addForeignKey(
            "fk_time_trail_check_create_user",
            "tbl_time_trail_check",
            "create_user_ID",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_time_trail_check_update_user",
            "tbl_time_trail_check",
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
