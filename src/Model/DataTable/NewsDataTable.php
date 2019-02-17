<?php

namespace App\Model\DataTable;

use Cake\Routing\Router;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;

class NewsDataTable
{

    public function ajaxManageNewsSearch($requestData)
    {

        $News = TableRegistry::get('News');
        $news = $News->find();
        $detail = $news;

        $count = $news->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        $this->autoRender = false;
        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(News.title) LIKE' => '%' . $search . '%'],
                    ['(News.market) LIKE' => '%' . $search . '%'],
                    ['(News.id) LIKE' => '%' . $search . '%']
            ]]);
            $news_count = $detail;
            $totalFiltered = count($news_count->toArray());
        }

        $columns = array(
            0 => 'id',
            2 => 'title',
            3 => 'market'
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
            $nestedData = [];
            $nestedData[] = $row["id"];
            $nestedData[] = '<img src="' . Core::getImagePath($row["urlToImage"]) . '"  width="50">';
            $nestedData[] = $row["title"];
            $nestedData[] = $row["market"];
            $nestedData[] = '<a href="' . Router::url(['_name' => 'edit_news', 'id' => $row["id"]]) . '" class="btn btn-default btn-sm" id="' . $row["id"] . '"><span>Edit</span></a>';
            $nestedData[] = '<button class="btn btn-default btn-sm delete" data-toggle="modal" data-target="#confirm-delete" data-url="delete/' . $row["id"] . '" >Delete</button>';
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

    public function ajaxManageNewsFrontSearch($requestData, $lng)
    {

        $News = TableRegistry::get('News');
        $news = $News->find('all')
            ->where(['market' => $lng]);

        $detail = $news;
        $count = $news->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;

        $this->autoRender = false;
        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(News.title) LIKE' => '%' . $search . '%']
            ]]);
            $news_count = $detail;
            $totalFiltered = $news_count->count();
        }

        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'created_at'
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
            $nestedData = [];
            $nestedData[] = '<a href="/' . $row['market'] . '/news/' . $row['slug'] . '"> <img src="' . Core::getImagePath($row["urlToImage"]) . '"  width="120"></a>';
            $nestedData[] = '<a href="/' . $row['market'] . '/news/' . $row['slug'] . '" class="tab-post-link">' . $row["title"] . '</a>';
            $nestedData[] = '<a href="/' . $row['market'] . '/news/' . $row['slug'] . '" class="tab-post-link">' . $row["created_at"]->nice() . '</a>';
            ;
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
