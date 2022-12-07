<?php

namespace App\Http\Controllers\Admin;

//use Alert;

use App\Http\Controllers\Controller;
use Artisan;

use Log;
use Storage;
use Carbon\Carbon;
// use Illuminate\Support\Facades\Mail;


class BackupController extends Controller
{
    public function index()
    {
        if (!count(config('backup.backup.destination.disks'))) {
            dd('backpack::backup.no_disks_configured');
        }

        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);


        //$files = $disk->allFiles();

        //dd(storage_path('app/backup-temp'));
        $files = $disk->files(config('backup.backup.name'));
        $backups = [];

        // dd($files);

        // make an array of backup files, with their filesize and creation date
        foreach ($files as $f) {

            $pathstring = pathinfo($f);

            $dt = Carbon::createFromTimestamp($disk->lastModified($f)
                                                 ,'Asia/Kolkata');


            // only take the zip files into account
            if ($pathstring['extension'] == 'zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $pathstring['dirname'],
                    'file_name' => $pathstring['basename'],
                    'file_size' => $disk->size($f),
                    'last_modified' => $dt->format('jS F Y, h:i a'),
                    'age' => $dt->diffForHumans( Carbon::now())
                ];
            }
        }
        // reverse the backups, so the newest one would be on top
        $backups = array_reverse($backups);

        return view("admin.backups.index")->with(compact('backups'));
    }

    public function create()
    {

        /*\Mail::send(['text'=>'email'], ['name'=>"Hari"], function ($m) {
             $m->from('legisectt@gmail.com', 'OT App');

             $m->to('harirs@gmail.com', 'Balu')->subject('Your Reminder!');
         });*/

        try 
        {
            // start the backup process
            //well notifications was not working, so disable it
             $exitCode = Artisan::call('backup:run',['--only-db' => true, '--disable-notifications'  => true]);


          //  Artisan::call('backup:run');

            $output = Artisan::output();

            // log the results
           // Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);
            // return the results as a response to the ajax call
            //Alert::success('New backup created');
            return redirect()->back();
        } 
        catch (Exception $e) 
        {
            Flash::error($e->getMessage());
            return redirect()->back();
        }


        
        


    }

    /**
     * Downloads a backup zip file.
     *
     * TODO: make it work no matter the flysystem driver (S3 Bucket, etc).
     */
    public function download($file_name)
    {       
        $file = config('backup.backup.name') . '/' . $file_name;
        
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists($file)) {
            $fs = Storage::disk(config('backup.backup.destination.disks')[0])->getDriver();
            $stream = $fs->readStream($file);

            return \Response::stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => Storage::mimeType($file),// $fs->getMimetype($file),
                "Content-Length" => Storage::size($file),
                "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
            ]);
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

    /**
     * Deletes a backup file.
     */
    public function delete($file_name)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists(config('backup.backup.name') . '/' . $file_name)) {
            $disk->delete(config('backup.backup.name') . '/' . $file_name);
            return redirect()->back();
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }
}