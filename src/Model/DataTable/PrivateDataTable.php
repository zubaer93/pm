<?php

namespace App\Model\DataTable;


use App\Controller\PrivateController;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;


class PrivateDataTable
{

    public function ajaxManagePrivateFrontSearch($requestData,$id)
    {

        $Private = TableRegistry::get('Private');
        $private = $Private->find()->where(['user_id'=>$id])->contain([
            'UserInvite'=>function ($q)
            {
                return $q->autoFields(false)
                    ->contain(['AppUsers'=>function ($q){
                        return $q->autoFields(false)
                            ->select('AppUsers.username');
                    }
                    ]);
            },
        ]);


        $detail = $private;
        $count = $private->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;

        $this->autoRender = false;
        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                ->where([
                    'OR' => [
                        ['(Private.name) LIKE' => '%' . $search . '%']
                    ]]);
            $news_count = $detail;
            $totalFiltered = $news_count->count();
        }

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'created_at'
        );
//        Router::url(['_name' => 'private_room', 'room' => $row["slug"]])
        $sidx = $columns[$requestData['order'][0]['column']];
        $sord = $requestData['order'][0]['dir'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $page = ($start) ? $start / $length : 1;
        $results = $detail
            ->order($sidx . ' ' . $sord)
            ->limit((int)$length)
            ->page($page);
        $data = array();
        foreach ($results as $row) {
            $userName = '';
            $i = 0;
            foreach ($row['user_invite'] as $appuser) {
                if ($i < 4) {
                $userName .= $appuser['app_user']['username'] . ',';
                }else{
                        break;
                }
                $i++;
            }
            $nestedData = [];
            $nestedData[] = '<a href="/USD' .'/private/'.$row["slug"]. '" class="tab-post-link">' . $row["name"] . '</a>';
            $nestedData[] = '<a href="/USD' .'/private/'.$row["slug"]. '" class="tab-post-link">' . $userName . '</a>';
            $nestedData[] = '<a href="/USD' .'/private/'.$row["slug"]. '" class="tab-post-link">' . $row["created_at"]->nice() . '</a>';
//            $nestedData[] = '<a href="/USD' .'/private/'.$row["slug"]. '" class="tab-post-link"><button>Edit</button></a>';
            $nestedData[] = '<a href="/USD' .'/private/edit/'.$row["slug"]. '" class="tab-post-link btn btn-info">Edit</a>
                             <a href="javascript:;" data-id="' .$row['id']. '" data-toggle="modal" class="delete_private btn btn-danger ">
                             Delete</a>';
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return json_encode($json_data);
    }
}