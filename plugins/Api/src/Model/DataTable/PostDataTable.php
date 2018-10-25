<?php

namespace Api\Model\DataTable;

use Cake\Routing\Router;
use Api\Model\Service\Core;
use Cake\ORM\TableRegistry;

class PostDataTable
{

    public function ajaxManagePostSearch($requestData)
    {
        ini_set('memory_limit', '-1');
        $Messages = TableRegistry::get('Api.Messages');
        $company = TableRegistry::get('Api.Companies');

        $messages = $Messages->find('all')
                ->contain('AppUsers')
                ->contain('Countries');
        $detail = $messages;
        
        $count = $messages->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        
        $this->autoRender = false;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(AppUsers.username) LIKE' => '%' . $search . '%'],
                    ['(Messages.id) LIKE' => '%' . $search . '%'],
                    ['(Messages.message) LIKE' => '%' . $search . '%'],
                    ['(Countries.name) LIKE' => '%' . $search . '%'],
            ]]);
            $post_count = $detail;
            $totalFiltered = $post_count->count();
        }

        $columns = array(
            0 => 'Messages.id',
            2 => 'AppUsers.username',
            4 => 'message',
            5 => 'Countries.name'
        );

        $sidx = $columns[$requestData['order'][0]['column']];
        $sord = $requestData['order'][0]['dir'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $page = ($start) ? $start / $length : 1;
        $results = $detail
                ->order($sidx . ' ' . $sord)
                ->limit((int) $length)
                ->page($page);
        $i = 0;
        $data = array();
        foreach ($results as $row) {
            $edit = Router::url(['_name' => 'edit_post', 'id' => $row["id"]]);
            $nestedData = [];
            if (file_exists(WWW_ROOT . $row['app_user']['avatarPath'])) {
                $img = '<img src="' . $row['app_user']['avatarPath'] . '"  width="50" height="50">';
            } else {
                $img = '<img src="/cake_d_c/users/img/avatar_placeholder.png"  width="50" height="50">';
            }

            $nestedData[] = $row["id"];
            $nestedData[] = $img;
            $nestedData[] = $row['app_user']['username'];
            $nestedData[] = $company->getCompanyName($row['company_id']);
            $nestedData[] = $row["message"];
            $nestedData[] = $row["country"]["name"];
            $nestedData[] = '<a href="' . $edit . '" class="btn btn-default btn-sm">Edit</a>'
                    . '<button class="btn btn-default'
                    . ' btn-sm delete"'
                    . ' data-toggle="modal"'
                    . ' data-target="#confirm-delete"'
                    . ' data-url="delete/' . $row["id"] . '" >'
                    . 'Delete'
                    . '</button> ';
            $data[] = $nestedData;
            $i++;
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
