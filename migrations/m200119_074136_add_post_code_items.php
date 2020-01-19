<?php

use yii\db\Migration;
use app\models\Posten;

/**
 * Class m190901_074136_add_route_items
 */
class m190901_074136_add_post_code_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /*****************************************************************************/
        $this->addColumn('tbl_posten', 'incheck_code', $this->string(255)->notNull());
        $this->addColumn('tbl_posten', 'uitcheck_code', $this->string(255)->notNull());

        $posten = Posten::find()->all();
        foreach($posten as $post){
            $post->getUniqueQrCode();
            $this->update('tbl_posten', [
                'incheck_code' => $post->incheck_code,
                'uitcheck_code' => $post->uitcheck_code
            ], ['post_ID' => $post->post_ID] );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tbl_posten', 'incheck_code');
        $this->dropColumn('tbl_posten', 'uitcheck_code');
    }
}
