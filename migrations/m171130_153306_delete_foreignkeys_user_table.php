<?php

//use Yii;
use yii\db\Migration;
use app\models\Users;


/**
 * Class m171104_143306_update_rbac_data
 */
class m171130_153306_delete_foreignkeys_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            "fk_events_create_user_name",
            "tbl_event_names");

        $this->dropForeignKey(
            "fk_events_update_user_name",
            "tbl_event_names");

        $this->dropForeignKey(
            "fk_groups_create_user",
            "tbl_groups");

        $this->dropForeignKey(
            "fk_groups_update_user",
            "tbl_groups");

        $this->dropForeignKey(
            "fk_deelnemers_user_id",
            "tbl_deelnemers_event");

        $this->dropForeignKey(
            "fk_deelnemers_create_user",
            "tbl_deelnemers_event");

        $this->dropForeignKey(
            "fk_deelnemers_update_user",
            "tbl_deelnemers_event");

    	$this->dropForeignKey(
            "fk_friend_list_user",
            "tbl_friend_list");

        $this->dropForeignKey(
            "fk_friend_list_friends_with_user",
            "tbl_friend_list");

        $this->dropForeignKey(
            "fk_friend_list_create_user",
            "tbl_friend_list");

        $this->dropForeignKey(
            "fk_friend_list_update_user",
            "tbl_friend_list");

        $this->dropForeignKey(
            "fk_route_create_user_name",
            "tbl_route");

       $this->dropForeignKey(
            "fk_route_update_user_name",
            "tbl_route");

        $this->dropForeignKey(
            "fk_open_vragen_create_user",
            "tbl_open_vragen");

        $this->dropForeignKey(
            "fk_open_vragen_update_user",
            "tbl_open_vragen");

        $this->dropForeignKey(
            "fk_open_vragen_antwoorden_create_user",
            "tbl_open_vragen_antwoorden");

        $this->dropForeignKey(
            "fk_open_vragen_antwoorden_update_user",
            "tbl_open_vragen_antwoorden");

        $this->dropForeignKey(
            "fk_qr_create_user",
            "tbl_qr");

        $this->dropForeignKey(
            "fk_qr_update_user",
            "tbl_qr");

    	$this->dropForeignKey(
            "fk_qr_check_create_user",
            "tbl_qr_check");

        $this->dropForeignKey(
            "fk_qr_check_update_user",
            "tbl_qr_check");

        $this->dropForeignKey(
            "fk_posten_create_user",
            "tbl_posten");

        $this->dropForeignKey(
            "fk_posten_update_user",
            "tbl_posten");

        $this->dropForeignKey(
            "fk_post_passage_create_user",
            "tbl_post_passage");

        $this->dropForeignKey(
            "fk_post_passage_update_user",
            "tbl_post_passage");

        $this->dropForeignKey(
            "fk_nood_envelop_create_user",
            "tbl_nood_envelop");

        $this->dropForeignKey(
            "fk_nood_envelop_update_user",
            "tbl_nood_envelop");

        $this->dropForeignKey(
            "fk_open_envelop_create_user",
            "tbl_open_nood_envelop");

        $this->dropForeignKey(
            "fk_open_envelop_update_user",
            "tbl_open_nood_envelop");

        $this->dropForeignKey(
            "fk_bonuspunten_create_user",
            "tbl_bonuspunten");

        $this->dropForeignKey(
            "fk_bonuspunten_update_user",
            "tbl_bonuspunten");

        $this->dropForeignKey(
            "fk_time_trail_create_user",
            "tbl_time_trail");

        $this->dropForeignKey(
            "fk_time_trail_update_user",
            "tbl_time_trail");

        $this->dropForeignKey(
            "fk_time_trail_item_create_user",
            "tbl_time_trail_item");

        $this->dropForeignKey(
            "fk_time_trail_item_update_user",
            "tbl_time_trail_item");


        $this->dropForeignKey(
            "fk_time_trail_check_create_user",
            "tbl_time_trail_check");

        $this->dropForeignKey(
            "fk_time_trail_check_update_user",
            "tbl_time_trail_check");
        
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
