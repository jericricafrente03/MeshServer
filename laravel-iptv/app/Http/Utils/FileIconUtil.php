<?php

namespace App\Http\Utils;

use App\Models\StoreFile;
use Illuminate\Support\Facades\DB;

trait FileIconUtil
{
    protected function styles()
    {
        return [
            "jpg"       => [
                "icon" => "bx-image", 
                "color" => "text-danger"
            ],
            "jpeg"      => [
                "icon" => "bx-image", 
                "color" => "text-danger"
            ],
            "png"       => [
                "icon" => "bx-image", 
                "color" => "text-primary"
            ],
            "svg"       => [
                "icon" => "bx-image", 
                "color" => "text-warning"
            ],
            "mp3"       => [
                "icon" => "bx-music", 
                "color" => "text-primary"
            ],
            "mp4"       => [
                "icon" => "bxs-video", 
                "color" => "text-success"
            ],
            "pdf"       => [
                "icon" => "bxs-file-pdf", 
                "color" => "text-danger"
            ],
            "doc"       => [
                "icon" => "bxs-file", 
                "color" => "text-primary"
            ],
            "docx"      => [
                "icon" => "bxs-file", 
                "color" => "text-primary"
            ],
            "txt"       => [
                "icon" => "bx-notepad", 
                "color" => "text-primary"
            ],
            "csv"       => [
                "icon" => "bxs-file-doc", 
                "color" => "text-success"
            ],
            "xlsx"      => [
                "icon" => "bxs-file-doc", 
                "color" => "text-success"
            ],
            "default"   => [
                "icon" => "bx-file-blank", 
                "color" => "text-primary"
            ]
        ];
    }

    public function custom_number_format($number, $decimals = 2) {
        $formatted_number = number_format($number, $decimals);
    
        // Check if the formatted number ends with ".00" or ".0" (for 2 or 1 decimal places)
        if (strpos($formatted_number, '.00') !== false) {
            // Remove the decimal part if it's ".00" or ".0"
            $formatted_number = str_replace('.00', '', $formatted_number);
        }
    
        return $formatted_number;
    }

    public function getUsedFilesData($pageIds = [])
    {
        $files = StoreFile::query()
            ->select('ext', DB::raw('count(id) as count'), DB::raw('sum(size) as total_size'))
            ->whereHas('folder', function ($query) use ($pageIds) {
                $query->whereIn('page_id', $pageIds);
            })
            ->groupBy('ext')->get();
        $images = ['png', 'jpeg', 'jpg'];
        $documents = ['doc', 'docx', 'pdf', 'xlsx', 'xls', 'csv'];
        $media = ['mp3', 'mp4'];
        $used = [
            'images'    => ['count' => 0, 'total_size' => ['KB' => 0, 'MB' => 0, 'GB' => 0], 'size_type' => 'KB', 'percentage' => 0],
            'documents' => ['count' => 0, 'total_size' => ['KB' => 0, 'MB' => 0, 'GB' => 0], 'size_type' => 'KB', 'percentage' => 0],
            'media'     => ['count' => 0, 'total_size' => ['KB' => 0, 'MB' => 0, 'GB' => 0], 'size_type' => 'KB', 'percentage' => 0],
            'other'     => ['count' => 0, 'total_size' => ['KB' => 0, 'MB' => 0, 'GB' => 0], 'size_type' => 'KB', 'percentage' => 0],
            'unknown'   => ['count' => 0, 'total_size' => ['KB' => 0, 'MB' => 0, 'GB' => 0], 'size_type' => 'KB', 'percentage' => 0],
            'total'     => ['count' => 0, 'total_size' => ['KB' => 0, 'MB' => 0, 'GB' => 0], 'size_type' => 'KB', 'percentage' => 0],
        ];
        foreach($files as $file) 
        {
            $ext = strtolower($file->ext);
            $flag = false;
            if(in_array($ext, $images)) {
                $used['images']['count'] += $file->count;
                $used['images']['total_size']['KB'] += $file->total_size;
                $flag = true;
            }
            if(in_array($ext, $documents)) {
                $used['documents']['count'] += $file->count;
                $used['documents']['total_size']['KB'] += $file->total_size;
                $flag = true;
            }
            if(in_array($ext, $media)) {
                $used['media']['count'] += $file->count;
                $used['media']['total_size']['KB'] += $file->total_size;
                $flag = true;
            }
            if(empty($ext)) {
                $used['unknown']['count'] += $file->count;
                $used['unknown']['total_size']['KB'] += $file->total_size;
                $flag = true;
            }
            if($flag === false) {
                $used['other']['count'] += $file->count;
                $used['other']['total_size']['KB'] += $file->total_size;
            }
            $used['total']['count'] += $file->count;
        }
        
        // total
        $used['total']['total_size']['KB'] = $used['images']['total_size']['KB'] + $used['documents']['total_size']['KB'] + $used['media']['total_size']['KB'] + $used['other']['total_size']['KB'] + $used['unknown']['total_size']['KB'];
        $kb = $used['total']['total_size']['KB'];
        $mb = $kb/1000;
        $used['total']['total_size']['MB'] = $mb;
        $used['total']['total_size']['GB'] = $kb/1000000;
        if($kb >= 1000){
            $used['total']['size_type'] = 'MB';
        }
        if($mb >= 1000){
            $used['total']['size_type'] = 'GB';
        }

        // images
        $kb = $used['images']['total_size']['KB'];
        $mb = $kb/1000;
        $used['images']['total_size']['MB'] = $mb;
        $used['images']['total_size']['GB'] = $kb/1000000;
        if($kb >= 1000){
            $used['images']['size_type'] = 'MB';
        }
        if($mb >= 1000){
            $used['images']['size_type'] = 'GB';
        }

        // documents
        $kb = $used['documents']['total_size']['KB'];
        $mb = $kb/1000;
        $used['documents']['total_size']['MB'] = $mb;
        $used['documents']['total_size']['GB'] = $kb/1000000;
        if($kb >= 1000){
            $used['documents']['size_type'] = 'MB';
        }
        if($mb >= 1000){
            $used['documents']['size_type'] = 'GB';
        }

        // media
        $kb = $used['media']['total_size']['KB'];
        $mb = $kb/1000;
        $used['media']['total_size']['MB'] = $mb;
        $used['media']['total_size']['GB'] = $kb/1000000;
        if($kb >= 1000){
            $used['media']['size_type'] = 'MB';
        }
        if($mb >= 1000){
            $used['media']['size_type'] = 'GB';
        }

        // other
        $kb = $used['other']['total_size']['KB'];
        $mb = $kb/1000;
        $used['other']['total_size']['MB'] = $mb;
        $used['other']['total_size']['GB'] = $kb/1000000;
        if($kb >= 1000){
            $used['other']['size_type'] = 'MB';
        }
        if($mb >= 1000){
            $used['other']['size_type'] = 'GB';
        }

        // unknown
        $kb = $used['unknown']['total_size']['KB'];
        $mb = $kb/1000;
        $used['unknown']['total_size']['MB'] = $mb;
        $used['unknown']['total_size']['GB'] = $kb/1000000;
        if($kb >= 1000){
            $used['unknown']['size_type'] = 'MB';
        }
        if($mb >= 1000){
            $used['unknown']['size_type'] = 'GB';
        }


        //
        $img = $used['images']['total_size'][$used['images']['size_type']];
        $doc = $used['documents']['total_size'][$used['documents']['size_type']];
        $med = $used['media']['total_size'][$used['media']['size_type']];
        $oth = $used['other']['total_size'][$used['other']['size_type']];
        $unk = $used['unknown']['total_size'][$used['unknown']['size_type']];
        $tot = $used['total']['total_size'][$used['total']['size_type']];
        $used['images']['size_text'] = $img == 0 ? '0 KB' : $this->custom_number_format($img, 2).' '.$used['images']['size_type'];
        $used['documents']['size_text'] = $doc == 0 ? '0 KB' : $this->custom_number_format($doc, 2).' '.$used['documents']['size_type'];
        $used['media']['size_text'] = $med == 0 ? '0 KB' : $this->custom_number_format($med, 2).' '.$used['media']['size_type'];
        $used['other']['size_text'] = $oth == 0 ? '0 KB' : $this->custom_number_format($oth, 2).' '.$used['other']['size_type'];
        $used['unknown']['size_text'] = $unk == 0 ? '0 KB' : $this->custom_number_format($unk, 2).' '.$used['unknown']['size_type'];
        $used['total']['size_text'] = $tot == 0 ? '0 KB' : $this->custom_number_format($tot, 2).' '.$used['total']['size_type'];

        $storage = env('STORAGE_KB_SIZE');
        $used['storage'] = [
            'total_size' => [
                'KB' => $storage,
                'MB' => $storage/1000,
                'GB' => $storage/1000000,
            ],
            'size_type' => 'GB'
        ];

        $used['images']['percentage'] = round((($used['images']['total_size']['KB']/$storage)*100));
        $used['documents']['percentage'] = round((($used['documents']['total_size']['KB']/$storage)*100));
        $used['media']['percentage'] = round((($used['media']['total_size']['KB']/$storage)*100));
        $used['other']['percentage'] = round((($used['other']['total_size']['KB']/$storage)*100));
        $used['unknown']['percentage'] = round((($used['unknown']['total_size']['KB']/$storage)*100));
        $used['total']['percentage'] = round((($used['total']['total_size']['KB']/$storage)*100));

        return $used;
    }
}