<?php
/**
 * Created by PhpStorm.
 * User: zhaozuowu
 * Date: 17/9/21
 * Time: 下午12:07
 */

namespace Stephen\Chunk\Service;
class ChunkService
{
    public function exportCsv($config, callable $callback, $pageSize = 1000)
    {
        $page = 0;
        $headerList = isset($config['headers']) ? $config['headers'] : [];
        $query = isset($config['models']) ? $config['models'] : [];
        $fileName = isset($config['filename']) ? $config['filename'] : 'exports.xls';

        if (empty($headerList)) {
            throw new \Exception('Config headers must be set');
        }
        if (empty($fileName)) {
            throw new \Exception('Config fileName must be set');
        }
        if (empty($query)) {
            throw new \Exception('Config query must be set');
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Cache-Control: max-age=0');

        $fp = fopen('php://output', 'a');
        foreach ($headerList as $i => $v) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $headerList[$i] = iconv('UTF-8', 'GBK', $v);
        }
        fputcsv($fp, $headerList);

        do {
            $offset = $page * $pageSize;
            $list = $query->skip($offset)->take($pageSize)->get();
            $list = $list ? $list->toArray() : [];
            $totalNum = count($list);
            if (empty($totalNum)) {
                break;
            }

            foreach ($list as $item) {
                $output = $callback($item);

                fputcsv($fp, $output);
            }

            $page++;

        } while ($totalNum == $pageSize);

        exit();


    }

}