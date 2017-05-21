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
class ProfileActivityFeed extends Model
{
    public $pageSize;
    public $pageCount;

    public function getData() {
        $data = [];
        $models = [];

        $querydeelnemersevents = DeelnemersEvent::find()
            ->where('user_ID =:user_id')
            ->params([':user_id' => Yii::$app->user->id]);

        $deelnemersevents = $querydeelnemersevents
            ->asArray()
            ->orderBy(['update_time'=>SORT_DESC])
            ->all();

        foreach ($deelnemersevents as $deelnemersevent) {
            // Check if event exists, turns out this is not always the case.
            // Consider it as a large bug it is possible to have an
            // deeelnemersevent entry without eventnames entry. But I'm not sure
            // if this is caused by debugging.
            if (!EventNames::find()
                    ->where('event_ID =:event_id')
                    ->params([':event_id' => $deelnemersevent['event_ID']])
                    ->exists()) {
                // Skip this entry when eventnames entry not exists.
                continue;
            }
            if ($deelnemersevent['rol'] === DeelnemersEvent::ROL_deelnemer) {
                $data[] = [
                    'id' => $deelnemersevent['deelnemers_ID'],
                    'source' => 'deelnemersevent',
                    'timestamp' => $deelnemersevent['update_time'],
                    'title' => Yii::t('app', 'Activity for hike {hikename}',
                        [
                            'hikename' => EventNames::findOne($deelnemersevent['event_ID'])->event_name,
                        ]),

                    'description' => Yii::t('app', '{username} added you to the group {groupname}',
                        [
                            'username' => Users::findOne($deelnemersevent['create_user_ID'])->username,
                            'groupname' => Groups::findOne($deelnemersevent['group_ID'])->group_name
                        ]),
                ];

            } else {
                $data[] = [
                    'id' => $deelnemersevent['deelnemers_ID'],
                    'source' => 'deelnemersevent',
                    'timestamp' => $deelnemersevent['update_time'],
                    'title' => Yii::t('app', 'Activity for hike {hikename}',
                        [
                            'hikename' => EventNames::findOne($deelnemersevent['event_ID'])->event_name,
                        ]),

                    'description' => Yii::t('app', '{username} added you as {role}',
                        [
                            'username' => Users::findOne($deelnemersevent['create_user_ID'])->username,
                            'role' => DeelnemersEvent::getRolText($deelnemersevent['rol']),
                        ]),
                ];            }
        }

        $querydeelnemersevents->select('event_ID');
        $eventnames = EventNames::find()
            ->where(['in', 'tbl_event_names.event_ID', $querydeelnemersevents])
            ->asArray()
            ->orderBy(['create_time'=>SORT_DESC])
            ->all();

        foreach ($eventnames as $eventname) {
            if (($eventname = EventNames::findOne($eventname['event_ID'])) === null) {
                continue;
            }

            $description = Yii::t('app', '{username} modified the hike.
                Hike has status: {status}',
                [
                    'username' => Users::findOne($eventname['create_user_ID'])->username,
                    'status' => $eventname->statusText,
                ]);
            if ($eventname->status === EventNames::STATUS_gestart) {
                $description .= Yii::t('app', 'Activeday is: {day} and the max walking time is {time}, ',
                    [
                        'day' => $eventname->active_day,
                        'time' => $eventname->max_time,
                    ]);
            }
            $data[] = [
                'id' => $eventname['event_ID'],
                'source' => 'eventnames',
                'timestamp' => $eventname['update_time'],
                'title' => Yii::t('app', 'Activity for hike {hikename}',
                    [
                        'hikename' => $eventname['event_name'],
                    ]),
                'description' => $description,
            ];
        }

        $friends = Yii::$app->user->identity->friendListsByUserId;
        foreach ($friends as $friend) {
            if ($friend['status'] === FriendList::STATUS_accepted) {
                $title = Yii::t('app', '{username} is a friend',
                    [
                        'username' => $friend->friendsWithUser['username'],
                    ]);
            } elseif ($friend['status'] === FriendList::STATUS_waiting) {
                $title = Yii::t('app', 'You\'ve sent {username} a friendship request',
                    [
                        'username' => $friend->friendsWithUser['username'],
                    ]);
            } elseif ($friend['status'] === FriendList::STATUS_pending) {
                $title = Yii::t('app', '{username} sended you a friendship request',
                    [
                        'username' => $friend->friendsWithUser['username'],
                    ]);
            }

            $data[] = [
                'id' => $friend['friend_list_ID'],
                'source' => 'friendlist',
                'timestamp' => $friend['update_time'],
                'title' => $title,
                'description' => $description,
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
