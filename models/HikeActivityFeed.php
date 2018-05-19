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

    public function getData()
    {
        $data = [];
        $models = [];

        $db = Yii::$app->db;
        $bonuspunten = $db->cache(function ($db) {
            return Bonuspunten::find()
                ->where('event_ID =:event_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
                ->asArray()
                ->orderBy(['create_time'=>SORT_DESC])
                ->all();
        });

        foreach ($bonuspunten as $bonuspunt) {
            $user = $db->cache(function ($db) use ($bonuspunt) {
                return Users::findOne($bonuspunt['create_user_ID']);
            });

            $group = $db->cache(function ($db) use ($bonuspunt) {
                return Groups::findOne($bonuspunt['group_ID']);
            });

            $data[] = [
                'id' => $bonuspunt['bouspunten_ID'],
                'source' => 'bonuspunten',
                'timestamp' => $bonuspunt['create_time'],
                'title' => Yii::t('app', 'Received bonus points'),
                'description' => $bonuspunt['omschrijving'],
                'score' => $bonuspunt['score'],
                'username' => $user->voornaam . ' ' . $user->achternaam,
                'groupname' => $group->group_name
            ];
        }

        $qrchecks = $db->cache(function ($db) {
            return QrCheck::find()
                ->where('event_ID =:event_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
                ->asArray()
                ->orderBy(['create_time'=>SORT_DESC])
                ->all();
        });

        foreach ($qrchecks as $qrcheck) {
            $qr = $db->cache(function ($db) use ($qrcheck) {
                return Qr::findOne($qrcheck['qr_ID']);
            });

            $user = $db->cache(function ($db) use ($qrcheck) {
                return Users::findOne($qrcheck['create_user_ID']);
            });

            $group = $db->cache(function ($db) use ($qrcheck) {
                return Groups::findOne($qrcheck['group_ID']);
            });

            $data[] = [
                'id' => $qrcheck['qr_check_ID'],
                'source' => 'qrcheck',
                'timestamp' => $qrcheck['create_time'],
                'title' => Yii::t('app', 'Check silent station'),
                'description' => $qr->qr_name,
                'score' => $qr->score,
                'username' => $user->voornaam . ' ' . $user->achternaam,
                'groupname' => $group->group_name
            ];
        }


        $timetrailchecks = $db->cache(function ($db) {
            return TimeTrailCheck::find()
                ->where('event_ID =:event_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
                ->asArray()
                ->orderBy(['create_time'=>SORT_DESC])
                ->all();
        });

        foreach ($timetrailchecks as $timetrailcheck) {
            $timetrailItem = $db->cache(function ($db) use ($timetrailcheck) {
                return TimeTrailItem::findOne($timetrailcheck['time_trail_item_ID']);
            });

            $user = $db->cache(function ($db) use ($timetrailcheck) {
                return Users::findOne($timetrailcheck['create_user_ID']);
            });

            $group = $db->cache(function ($db) use ($timetrailcheck) {
                return Groups::findOne($timetrailcheck['group_ID']);
            });

            $data[] = [
                'id' => $timetrailcheck['time_trail_check_ID'],
                'source' => 'qrcheck',
                'timestamp' => $timetrailcheck['create_time'],
                'title' => Yii::t('app', 'Check time trail'),
                'description' => $timetrailItem->time_trail_item_name,
                'score' => $timetrailItem->score,
                'username' => $user->voornaam . ' ' . $user->achternaam,
                'groupname' => $group->group_name
            ];
        }

        $answers = $db->cache(function ($db) {
            return OpenVragenAntwoorden::find()
                ->where('event_ID =:event_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
                ->asArray()
                ->orderBy(['create_time'=>SORT_DESC])
                ->all();
        });

        foreach ($answers as $answer) {
            $question = $db->cache(function ($db) use ($answer) {
                return OpenVragen::findOne($answer['open_vragen_ID']);
            });

            $user = $db->cache(function ($db) use ($answer) {
                return Users::findOne($answer['create_user_ID']);
            });

            $group = $db->cache(function ($db) use ($answer) {
                return Groups::findOne($answer['group_ID']);
            });

            $data[] = [
                'id' => $answer['open_vragen_antwoorden_ID'],
                'source' => 'openvragenantwoorden',
                'timestamp' => $answer['create_time'],
                'title' => Yii::t('app', 'Answered question'),
                'description' => $question->open_vragen_name,
                'score' => $question->score,
                'username' => $user->voornaam . ' ' . $user->achternaam,
                'groupname' => $group->group_name
            ];
        }

        $posts = $db->cache(function ($db) {
            return PostPassage::find()
                ->where('event_ID =:event_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
                ->asArray()
                ->orderBy(['create_time'=>SORT_DESC])
                ->all();
        });

        foreach ($posts as $post) {
            $postData = $db->cache(function ($db) use ($post) {
                return Posten::findOne($post['post_ID']);
            });

            $user = $db->cache(function ($db) use ($post) {
                return Users::findOne($post['create_user_ID']);
            });

            $group = $db->cache(function ($db) use ($post) {
                return Groups::findOne($post['group_ID']);
            });

            $data[] = [
                'id' => $post['posten_passage_ID'],
                'source' => 'postenpassage',
                'timestamp' => $post['create_time'],
                'title' => Yii::t('app', 'Checked in at station'),
                'description' => $postData->post_name,
                'score' => $postData->score,
                'username' => $user->voornaam . ' ' . $user->achternaam,
                'groupname' => $group->group_name
            ];
        }

        $hints = OpenNoodEnvelop::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
            ->asArray()
            ->orderBy(['create_time'=>SORT_DESC])
            ->all();

        foreach ($hints as $hint) {
            $hintData = $db->cache(function ($db) use ($hint) {
                return NoodEnvelop::findOne($hint['nood_envelop_ID']);
            });

            $user = $db->cache(function ($db) use ($hint) {
                return Users::findOne($hint['create_user_ID']);
            });
            $group = $db->cache(function ($db) use ($hint) {
                return Groups::findOne($hint['group_ID']);
            });

            $data[] = [
                'id' => $hint['open_nood_envelop_ID'],
                'source' => 'openhints',
                'timestamp' => $hint['create_time'],
                'title' => Yii::t('app', 'Opened an hint'),
                'description' => $hintData->nood_envelop_name,
                'score' => $hintData->score,
                'username' => $user->voornaam . ' ' . $user->achternaam,
                'groupname' => $group->group_name
            ];
        }

        $pages = new CustomPagination(['pageSize' => $this->pageSize, 'pageCount' => $this->pageCount]);
        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => $pages,
            'sort' => [
                'defaultOrder' => ['timestamp'=>SORT_DESC],
                'attributes' => ['timestamp', 'source', 'title', 'description', 'score', 'username', 'groupname'],
            ],
        ]);
        return $provider;
    }



    public function getLastGroupsActivity()
    {
        $data = [];

        $groups = Groups::find()
            ->where('event_ID =:event_id')
            ->params(['event_id' => Yii::$app->user->identity->selected_event_ID])
            ->all();

        foreach ($groups as $group) {
            $db = Yii::$app->db;

            $qrchecks = $db->cache(function ($db) use ($group) {
                return QrCheck::find()
                    ->where('event_ID =:event_id AND group_ID =:group_id')
                    ->params([
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':group_id' => $group->group_ID])
                    ->orderBy(['create_time'=>SORT_DESC])
                    ->one();
            });

            if (isset($qrchecks)) {
                $data[] = [
                    'id' => $qrchecks['qr_check_ID'],
                    'source' => 'qrcheck',
                    'timestamp' => $qrchecks['create_time'],
                    'title' => Yii::t('app', 'Check silent station'),
                    'description' => $qrchecks->qr->qr_name,
                    'score' => $qrchecks->qr->score,
                    'username' => $qrchecks->createUser->voornaam . ' ' . $qrchecks->createUser->achternaam,
                    'groupname' => $group->group_name
                ];
            }

            $answers = $db->cache(function ($db) use ($group) {
                return OpenVragenAntwoorden::find()
                    ->where('event_ID =:event_id AND group_ID =:group_id')
                    ->params([
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':group_id' => $group->group_ID])
                    ->orderBy(['create_time'=>SORT_DESC])
                    ->one();
            });

            if (isset($answers)) {
                $data[] = [
                    'id' => $answers['open_vragen_antwoorden_ID'],
                    'source' => 'openvragenantwoorden',
                    'timestamp' => $answers['create_time'],
                    'title' => Yii::t('app', 'Answered question'),
                    'description' => $answers->openVragen->open_vragen_name,
                    'score' => $answers->openVragen->score,
                    'username' => $answers->createUser->voornaam . ' ' . $answers->createUser->achternaam,
                    'groupname' => $group->group_name
                ];
            }

            $posts = $db->cache(function ($db) use ($group) {
                return PostPassage::find()
                    ->where('event_ID =:event_id AND group_ID =:group_id')
                    ->params([
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':group_id' => $group->group_ID])
                    ->orderBy(['create_time'=>SORT_DESC])
                    ->one();
            });

            if (isset($posts)) {
                $data[] = [
                    'id' => $posts['posten_passage_ID'],
                    'source' => 'postenpassage',
                    'timestamp' => $posts['create_time'],
                    'title' => Yii::t('app', 'Checked in at station'),
                    'description' => $posts->post->post_name,
                    'score' => $posts->post->score,
                    'username' => $posts->createUser->voornaam . ' ' . $posts->createUser->achternaam,
                    'groupname' => $group->group_name
                ];
            }

            $hints = OpenNoodEnvelop::find()
                ->where('event_ID =:event_id AND group_ID =:group_id')
                ->params([
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':group_id' => $group->group_ID])
                ->orderBy(['create_time'=>SORT_DESC])
                ->one();

            if (isset($hints)) {
                $data[] = [
                    'id' => $hints['open_nood_envelop_ID'],
                    'source' => 'openhints',
                    'timestamp' => $hints['create_time'],
                    'title' => Yii::t('app', 'Opened an hint'),
                    'description' => $hints->noodEnvelop->nood_envelop_name,
                    'score' => $hints->noodEnvelop->score,
                    'username' => $hints->createUser->voornaam . ' ' . $hints->createUser->achternaam,
                    'groupname' => $group->group_name
                ];
            }
        }

        $pages = new CustomPagination(['pageSize' => $this->pageSize, 'pageCount' => $this->pageCount]);
        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => $pages,
            'sort' => [
                'defaultOrder' => ['timestamp'=>SORT_DESC],
                'attributes' => ['timestamp', 'source', 'title', 'description', 'score', 'username', 'groupname'],
            ],
        ]);
        return $provider;
    }
}
