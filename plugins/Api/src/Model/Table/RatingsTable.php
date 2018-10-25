<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Countries Model
 *
 * @property \App\Model\Table\ExchangesTable|\Cake\ORM\Association\HasMany $Exchanges
 *
 * @method \App\Model\Entity\Country get($primaryKey, $options = [])
 * @method \App\Model\Entity\Country newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Country[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Country|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Country patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Country[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Country findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RatingsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('ratings');
    }

    public function setRating($data, $user_id)
    {
        $result = false;
        if (!empty($data)) {
            if ($this->check($data['message_id'], $user_id) && $data['grade'] >= 1 && $data['grade'] <= 5) {

                $rating = $this->newEntity();
                $rating->user_id = $user_id;
                $rating->message_id = $data['message_id'];
                $rating->grade = $data['grade'];

                if ($this->save($rating)) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    public function check($message_id, $user_id)
    {
        $check = $this->find()
            ->where(['message_id' => $message_id])
            ->where(['user_id' => $user_id])
            ->first();
        $Messages = TableRegistry::get('Api.Messages');
        $result = $Messages->get($message_id);

        if ($result['user_id'] == $user_id) {
            return false;
        }

        if (!is_null($check)) {
            return false;
        } else {
            return true;
        }
    }

    public static function getAverageRanking($message_id)
    {
        $Ratings = TableRegistry::get('Api.Ratings');
        $query = $Ratings->find('all');
        $data = $query->select([
            'count' => $query->func()->count('message_id'),
            'grade' => $query->func()->sum('grade')
        ])->where(['message_id' => trim($message_id)])->first();
        $rating = 0;
        if (!empty($data['count']) && !empty($data['grade'])) {
            $rating = $data['grade'] / $data['count'];

            return (int) $rating;
        }
        return 0;
    }

}
