<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_183043_friend_list_table extends Migration
{
    public function up()
    {          
        /**********************************************************************/
        /* Vriendenlijst
        /* In deze tabel staan de vriendschappen gedefinieerd. 
        /**********************************************************************/

	$this->createTable('tbl_friend_list', [
            'friend_list_ID'		=> $this->primaryKey(),
            'user_ID'                   => $this->integer(11),
            'friends_with_user_ID' 	=> $this->integer(11),
            'status' 			=> $this->integer(11),
            'create_time' 		=> $this->dateTime(),
            'create_user_ID' 		=> $this->integer(11),
            'update_time' 		=> $this->dateTime(),
            'update_user_ID' 		=> $this->integer(11),
	],
        'ENGINE=InnoDB');

        $this->createIndex('friendship_ID', 'tbl_friend_list', ['user_ID', 'friends_with_user_ID'], true);

        /*********************** table friens_list ****************************/
  
	$this->addForeignKey("fk_friend_list_user", 
			     "tbl_friend_list", 
			     "user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	
	$this->addForeignKey("fk_friend_list_friends_with_user", 
			     "tbl_friend_list", 
			     "friends_with_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE"); 

	$this->addForeignKey("fk_friend_list_create_user", 
			     "tbl_friend_list", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	
	$this->addForeignKey("fk_friend_list_update_user", 
			     "tbl_friend_list", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE"); 
    }

    public function down()
    {
        $this->truncateTable('tbl_friend_list');
        $this->dropTable('tbl_friend_list');
    }
}
