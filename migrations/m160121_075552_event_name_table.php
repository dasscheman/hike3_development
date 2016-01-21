<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_075552_event_name_table extends Migration
{
    public function up()
    {
        /*********************** table event_names ***********************************/
        /*****************************************************************************/
        /* De tabel tbl_events bevat alle hike georganiseerd. Elk event heeft een 
        /* startdatum een einddatum en een status. de status kan zijn: opstart, gestart, 
        /* beindigd, geannuleerd. 
        /*****************************************************************************/
                                           
	$this->createTable('tbl_event_names', [
            'event_ID'          => $this->primaryKey(),
            'event_name'        => $this->string()->notNull()->unique(),
            'start_date'        => $this->date(),
            'end_date'         	=> $this->date(),
            'status'            => $this->integer(11),
            'active_day'        => $this->date(),
            'max_time'          => $this->time(),
            'image'             => $this->string(255),
            'organisatie'       => $this->string(255)->notNull(),
            'website'           => $this->string(255),
            'create_time'       => $this->dateTime(),
            'create_user_ID'	=> $this->integer(11),
            'update_time'       => $this->dateTime(),
            'update_user_ID'    => $this->integer(11),
         ],                                       
        'ENGINE=InnoDB');   
                     
        /*****************************************************************************/
        /* add foreignkays
        /*****************************************************************************/

	$this->addForeignKey("fk_events_create_user_name", 
			     "tbl_event_names", 
			     "create_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE");
	$this->addForeignKey("fk_events_update_user_name", 
			     "tbl_event_names", 
			     "update_user_ID",
			     "tbl_users", 
			     "user_ID", 
			     "RESTRICT", 
			     "CASCADE"); 
 
    }

    public function down()
    {
        $this->truncateTable('tbl_event_names');
        $this->dropTable('tbl_event_names');
    }
}
