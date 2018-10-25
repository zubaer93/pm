<?php

namespace Api\Model\DataTable;

use Cake\Routing\Router;
use Api\Model\Service\Core;
use Cake\ORM\TableRegistry;

class PartnerDataTable
{

    public function ajaxManagePartnerSearch($requestData)
    {

        $Partners = TableRegistry::get('Api.Partners');

        $partners = $Partners->find('all');

        $detail = $partners;
        $count = $partners->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        $this->autoRender = false;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(Partners.id) LIKE' => '%' . $search . '%'],
                    ['(Partners.name) LIKE' => '%' . $search . '%'],
                    ['(Partners.subdomain) LIKE' => '%' . $search . '%'],
                    ['(Partners.main_color) LIKE' => '%' . $search . '%'],
                    ['(Partners.main_border_color) LIKE' => '%' . $search . '%']
            ]]);
            $brokers_count = $detail;
            $totalFiltered = $brokers_count->count();
        }

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'subdomain',
            4 => 'main_color',
            5 => 'main_border_color'
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
            $checked = (!$row['enable']) ? "checked" : "";
            if ($row['enable']) {
                $enable = Router::url(['_name' => 'enable_partner', 'id' => $row["id"]]);
            } else {
                $enable = Router::url(['_name' => 'disable_partner', 'id' => $row["id"]]);
            }
            $edit = Router::url(['_name' => 'edit_partner', 'id' => $row["id"]]);
            $nestedData = [];
            $nestedData[] = $row["id"];
            $nestedData[] = $row["name"];
            $nestedData[] = $row["subdomain"];
            $nestedData[] = '<img src="' . Core::getPartnerImagePath($row["logo_path"]) . '"  width="50">';
            $nestedData[] = $row["main_color"];
            $nestedData[] = $row["main_border_color"];
            $nestedData[] = '<label class="switch switch-info btn-sm">
                                        <input class="disable" onchange="window.location.assign(&quot;' . $enable . '&quot;)" type="checkbox"' . $checked . ' >
                                        <span class="switch-label" data-on="YES" data-off="NO"></span>
                                    </label>';
            $nestedData[] = '<a href="' . $edit . '"'
                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-success">'
                    . '<i class="glyphicon glyphicon-pencil"></i>'
                    . '<span>Edit</span>'
                    . '</a>';
            $nestedData[] = '<button class="btn btn-danger btn-3d btn-sm" '
                    . 'data-toggle="modal" data-target="#confirm-delete" '
                    . 'data-name="' . $row["name"] . '" '
                    . 'data-url="delete/' . $row["id"] . '" '
                    . 'type="submit">'
                    . 'Delete'
                    . '</button>';
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
