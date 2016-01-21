<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_073307_users_table extends Migration
{
    public function up()
    {
        /*****************************************************************************/
        /* In alle tabellen komen de kolommen create_time, create_user_name,
        /* update_time en update_user_name voor. Dit is om wijzigingen te kunnen 
        /* bijhouden.                  
        /*****************************************************************************/
	$this->createTable('tbl_users', [
            'user_ID'           => $this->primaryKey(),
            'username'          => $this->string()->notNull()->unique(),
            'voornaam'          => $this->string()->notNull(),
            'achternaam'        => $this->string()->notNull(),
            'organisatie'       => $this->string()->notNull(),
            'email'             => $this->string()->notNull()->unique(),
            'password'          => $this->string()->notNull(),
            'macadres'          => $this->string()->notNull(),
            'birthdate'         => $this->date(),
            'last_login_time'   => $this->dateTime(),
            'create_time'	=> $this->dateTime(),
            'create_user_ID'    => $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
        ], 
	'ENGINE=InnoDB');
    }

    public function down()
    {  
        $this->truncateTable('tbl_users');
        $this->dropTable('tbl_users');
    }
}
