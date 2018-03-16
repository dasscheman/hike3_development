<?php

//use Yii;
use yii\db\Migration;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180315_000010_fill_auth_item_table extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->execute("
            INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
                ('deelnemer', 1, '', 'deelnemer', NULL, 1520807822, 1520807822),
                ('deelnemerGestart', 1, '', 'deelnemerGestart', NULL, 1520839189, 1520839189),
                ('deelnemerGestartTime', 1, '', 'deelnemerGestartTime', NULL, 1520839203, 1520839203),
                ('deelnemerIntroductie', 1, '', 'deelnemerItroductie', NULL, 1520839245, 1520839245),
                ('gebruiker', 1, '', NULL, NULL, 1520807887, 1520839361),
                ('organisatie', 1, '', 'organisatie', NULL, 1520807836, 1520807836),
                ('organisatieGestart', 1, '', 'organisatieGestart', NULL, 1520839275, 1520839275),
                ('organisatieGestartTime', 1, '', 'organisatieGestartTime', NULL, 1520839315, 1520839315),
                ('organisatieIntroductie', 1, '', 'organisatieIntroductie', NULL, 1520839332, 1520839332),
                ('organisatieOpstart', 1, '', 'organisatieOpstart', NULL, 1520839352, 1520839352); ");
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->truncateTable('auth_item');
    }

}
