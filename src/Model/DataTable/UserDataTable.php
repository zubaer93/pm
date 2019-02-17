<?php

namespace App\Model\DataTable;

use Cake\Routing\Router;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;

class UserDataTable
{

    public function ajaxManageUserSearch($requestData)
    {
        $Users = TableRegistry::get('AppUsers');
        $users = $Users->find('all');
        $detail = $users;
        $count = $users->count();
        $this->autoRender = false;
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(AppUsers.first_name) LIKE' => '%' . $search . '%'],
                    ['(AppUsers.last_name) LIKE' => '%' . $search . '%'],
                    ['(AppUsers.email) LIKE' => '%' . $search . '%'],
                    ['(AppUsers.role) LIKE' => '%' . $search . '%'],
                    ['(AppUsers.account_type) LIKE' => '%' . $search . '%'],
            ]]);
            $user_count = $detail;
            $totalFiltered = $user_count->count();
        }
        
        $columns = array(
            0 => 'created',
            2 => 'first_name',
            3 => 'last_name',
            4 => 'email',
            5 => 'role',
            6 => 'account_type',
            7 => 'last_online_time',
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
            $edit = Router::url(['_name' => 'edit_user', 'id' => $row["id"]]);
            $delete = Router::url(['_name' => 'delete_user', 'id' => $row["id"]]);
            
            $checked = (!$row['enable']) ? "checked" : "";
            if ($row['enable']) {
                $enable = Router::url(['_name' => 'enable_user', 'id' => $row["id"]]);
            } else {
                $enable = Router::url(['_name' => 'disable_user', 'id' => $row["id"]]);
            }

            $nestedData = [];
            if (file_exists(WWW_ROOT . $row['avatarPath'])) {
                $img = '<img src="' . $row['avatarPath'] . '"  width="50" height="50">';
            } else {
                $img = '<img src="/cake_d_c/users/img/avatar_placeholder.png"  width="50" height="50">';
            }
            $last_online = intval(abs(strtotime($row["last_online_time"]) - time()) / 60);
            $nestedData[] = date("d M, Y",strtotime($row["created"]));
            $nestedData[] = $img;
            $nestedData[] = $row["first_name"];
            $nestedData[] = $row["last_name"];
            $nestedData[] = $row["email"];
            $nestedData[] = $row["role"];
            $nestedData[] = $row["account_type"];
            if($last_online <=2){
                $nestedData[] = '<span class="label label-success">Online</span>';
            }
            elseif($last_online > 2 && $last_online <= 60){
                $nestedData[] = '<span class="label label-default">'.$last_online. ' minutes ago</span>';
            }
            elseif($last_online > 60){
                $nestedData[] = '<span class="label label-danger">Offline</span>';
            }

            $nestedData[] = '<label class="switch switch-info btn-sm">
                                        <input class="disable" onchange="window.location.assign(&quot;' . $enable . '&quot;)" type="checkbox"' . $checked . ' >
                                        <span class="switch-label" data-on="YES" data-off="NO"></span>
                                    </label>';
            $nestedData[] = '<a href="' . $edit . '"'
                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-success">'
                    . '<i class="glyphicon glyphicon-pencil"></i>'
                    . '<span>Edit</span>'
                    . '</a> ';
    
            $nestedData[] =  '<button class="btn btn-danger btn-3d btn-sm" '
                    . 'data-toggle="modal" data-target="#confirm-delete" '
                    . 'data-name="' . $row["first_name"].' '.$row["last_name"]. '" '
                    . 'data-url="delete/' . $row["id"] . '" '
                    . 'type="submit">'
                    . 'Delete'
                    . '</button>';
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
