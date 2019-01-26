<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SpreadsheetReader;
use Illuminate\Support\Facades\File;


class CsvImportController extends Controller
{

    public function parse(Request $request) {

        $file = $request->file('csv_file');
        if (!$file->isValid() || $file->getClientOriginalExtension() != 'csv') {
            abort(415, 'Invalid file type');
        }

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];
        $lines[] = $reader->next();
        $lines[] = $reader->next();


        $filename = str_random(10) . '.csv';
        $file->storeAs('csv_import', $filename);

        $modelName = $request->input('model', false);
        $fullModelName = "App\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();
        $hasid = $request->input('idfield', false) ? true : false;
        if($hasid){ //for update
            array_unshift($fillables, "id");
        }

        $redirect = url()->previous();

        return view('csvImport.parse_import', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect'));

    }

    public function process(Request $request) {

        $filename = $request->input('filename', false);
        $path = storage_path('app/csv_import/' . $filename);

        $hasHeader = $request->input('hasHeader', false);

        //$hasid = $request->input('idfield', false) ? true : false;
        

        $fields = $request->input('fields', false);
        $fields = array_flip(array_filter($fields));

        $hasid = FALSE != array_key_exists('id',$fields);

        $modelName = $request->input('modelName', false);
        $model = "App\\" . $modelName;

        $reader = new SpreadsheetReader($path);
        $insert = [];
        

        foreach($reader as $key => $row) {
            if ($hasHeader && $key == 0) {
              continue;
            }

            if(count($row) == count($fields))
            {
                $tmp = [];
                foreach($fields as $header => $k) {

                    // if( count($row) <= $k )
                    // {
                    //     dd($row);
                    // }

                    $tmp[$header] = $row[$k];
                }
                $insert[] = $tmp;
            }

        }

        $for_insert = array_chunk($insert, 100);

        foreach ($for_insert as $insert_item) {
         
               //$model::insert($insert_item);

           
                foreach ($insert_item as $item) {

                    foreach ($item as $key => $value) {
                     if($value == null){
                        unset($item[$key]);
                     }
                    }
                    if(array_key_exists('id',$item )){
                        $id = $item['id'];
                        unset($item['id']);
                        $model::where('id',$id)->update($item);
                    } else {
                        //dd('no id field found');
                        $model::insert($item);

                    }
                }
        }

        $rows = count($insert);
        $table = str_plural($modelName);

        File::delete($path);

        $redirect = $request->input('redirect', false);
        return redirect()->to($redirect)->with('message', trans('quickadmin.qa_imported_rows_to_table', ['rows' => $rows, 'table' => $table]));

    }

}
