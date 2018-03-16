<?php

//use Yii;
use yii\db\Migration;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180315_000020_fill_auth_item_child_table extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->execute("
            INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
                ('gebruiker', 'deelnemer'),
                ('gebruiker', 'deelnemerGestart'),
                ('gebruiker', 'deelnemerGestartTime'),
                ('gebruiker', 'deelnemerIntroductie'),
                ('gebruiker', 'organisatie'),
                ('gebruiker', 'organisatieGestart'),
                ('gebruiker', 'organisatieGestartTime'),
                ('gebruiker', 'organisatieIntroductie'),
                ('gebruiker', 'organisatieOpstart'); ");
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->truncateTable('auth_item_child');
    }

}
