<?php

namespace Api\Model\DataTable;

use Cake\Routing\Router;
use Api\Model\Service\Core;
use Cake\ORM\TableRegistry;

class EventDataTable
{

    public function ajaxEventSearch($requestData)
    {
        $events = TableRegistry::get('Api.Events');
        $events = $events->find('all')->contain('Companies');
        $detail = $events;

        $count = $events->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        $this->autoRender = false;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(Companies.name) LIKE' => '%' . $search . '%'],
                    ['(Companies.symbol) LIKE' => '%' . $search . '%'],
                    ['(Events.title) LIKE' => '%' . $search . '%'],
                    ['(Events.activity_type) LIKE' => '%' . $search . '%'],
                    ['(Events.location) LIKE' => '%' . $search . '%'],
                    ['(Events.date) LIKE' => '%' . $search . '%']
            ]]);
            $brokers_count = $detail;
            $totalFiltered = $brokers_count->count();
        }

        $columns = array(
            0 => 'name',
            1 => 'symbol',
            2 => 'title',
            3 => 'activity_type',
            4 => 'description',
            5 => 'date',
            6 => 'location',
            7 => 'meeting_link'
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
                $enable = Router::url(['_name' => 'enable_event', 'id' => $row["id"]]);
            } else {
                $enable = Router::url(['_name' => 'disable_event', 'id' => $row["id"]]);
            }
            $edit = Router::url(['_name' => 'edit_event', 'id' => $row["id"]]);

            $nestedData = [];
            $nestedData[] = $row['company']['name'];
            $nestedData[] = $row['company']['symbol'];
            $nestedData[] = $row["title"];
            $nestedData[] = $row["activity_type"];
            $nestedData[] = $row["description"];
            $nestedData[] = $row["date"];
            $nestedData[] = $row["location"];
            $nestedData[] = $row["meeting_link"];
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
                    . 'data-name="' . $row['company']['name'] . '" '
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
