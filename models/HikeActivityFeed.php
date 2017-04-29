<?php

namespace app\models;

    use Yii;
    use yii\base\Model;
    use yii\data\ArrayDataProvider;
    use app\components\CustomPagination;
// use app\models\Bonuspunten;

/**
 * UsersSearch represents the model behind the search form about `app\models\Users`.
 */
class HikeActivityFeed extends Model
{
    public $pageSize;
    public $pageCount;

    public function getData() {
        $data = [];
        $models = [];
        $bonuspunten = Bonuspunten::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected])
            ->asArray()
            ->orderBy(['create_time'=>SORT_DESC])
            //->limit(20)
            ->all();

        foreach ($bonuspunten as $bonuspunt) {
            $data[] = [
                'id' => $bonuspunt['bouspunten_ID'],
                'source' => 'bonuspunten',
                'timestamp' => $bonuspunt['create_time'],
                'title' => Yii::t('app', 'Received bonus points'),
                'description' => $bonuspunt['omschrijving'],
                'score' => $bonuspunt['score'],
                'username' => Users::findOne($bonuspunt['create_user_ID'])->username,
                'groupname' => Groups::findOne($bonuspunt['group_ID'])->group_name
            ];
        }

        $qrchecks = QrCheck::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected])
            ->asArray()
            ->orderBy(['create_time'=>SORT_DESC])
            //->limit(20)
            ->all();

        foreach ($qrchecks as $qrcheck) {
            $qr =  Qr::findOne($qrcheck['qr_ID']);
            $data[] = [
                'id' => $qrcheck['qr_check_ID'],
                'source' => 'qrcheck',
                'timestamp' => $qrcheck['create_time'],
                'title' => Yii::t('app', 'Check silent station'),
                'description' => $qr->qr_name,
                'score' => $qr->score,
                'username' => Users::findOne($qrcheck['create_user_ID'])->username,
                'groupname' => Groups::findOne($qrcheck['group_ID'])->group_name
            ];
        }

        $answers = OpenVragenAntwoorden::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected])
            ->asArray()
            ->orderBy(['create_time'=>SORT_DESC])
            //->limit(20)
            ->all();

        foreach ($answers as $answer) {
            $question = OpenVragen::findOne($answer['open_vragen_ID']);
            $data[] = [
                'id' => $answer['open_vragen_antwoorden_ID'],
                'source' => 'openvragenantwoorden',
                'timestamp' => $answer['create_time'],
                'title' => Yii::t('app', 'Answered question'),
                'description' => $question->open_vragen_name,
                'score' => $question->score,
                'username' => Users::findOne($answer['create_user_ID'])->username,
                'groupname' => Groups::findOne($answer['group_ID'])->group_name
            ];
        }

        $posts = PostPassage::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected])
            ->asArray()
            ->orderBy(['create_time'=>SORT_DESC])
            //->limit(20)
            ->all();

        foreach ($posts as $post) {
            $postData = Posten::findOne($post['post_ID']);
            $data[] = [
                'id' => $post['posten_passage_ID'],
                'source' => 'postenpassage',
                'timestamp' => $post['create_time'],
                'title' => Yii::t('app', 'Checked in at station'),
                'description' => $postData->post_name,
                'score' => $postData->score,
                'username' => Users::findOne($post['create_user_ID'])->username,
                'groupname' => Groups::findOne($post['group_ID'])->group_name
            ];
        }

        $hints = OpenNoodEnvelop::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected])
            ->asArray()
            ->orderBy(['create_time'=>SORT_DESC])
            //->limit(20)
            ->all();

        foreach ($hints as $hint) {
            $hintData = NoodEnvelop::findOne($hint['nood_envelop_ID']);
            $data[] = [
                'id' => $hint['open_nood_envelop_ID'],
                'source' => 'openhints',
                'timestamp' => $hint['create_time'],
                'title' => Yii::t('app', 'Opened an hint'),
                'description' => $hintData->nood_envelop_name,
                'score' => $hintData->score,
                'username' => Users::findOne($hint['create_user_ID'])->username,
                'groupname' => Groups::findOne($hint['group_ID'])->group_name
            ];
        }

        $pages = new CustomPagination(['pageSize' => $this->pageSize, 'pageCount' => $this->pageCount]);
        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => $pages,
            'sort' => [
                'defaultOrder' => ['timestamp'=>SORT_DESC],
                'attributes' => ['timestamp'],
            ],
        ]);
        // dd($provider->);
        return $provider;

    }
    // get the rows in the currently requested page
    // $rows = $provider->getModels();
}
