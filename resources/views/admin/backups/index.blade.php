@extends('layouts.app')

<?php
function humanFilesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}
?>

@section('content')
    <h3>Database Backups</h3>
    <div class="row">
        <div class="col-12 clearfix">
            <a id="create-new-backup-button" href="{{ url('admin/backup/create') }}" class="btn btn-primary pull-right"
               style="margin-bottom:2em;"><i
                    class="fa fa-plus"></i> Create New Backup
            </a>
        </div>
        <div class="col-12">
            @if (count($backups))

                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>File</th>
                        <th>Size</th>
                        <th>Date</th>
                        <th>Age</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($backups as $backup)
                        <tr>
                            <td>{{ $backup['file_name'] }}</td>
                            <td>{{ humanFilesize($backup['file_size']) }}</td>
                            <td>
                                {{  $backup['last_modified'] }}
                            </td>
                            <td>
                                {{ $backup['age'] }}
                            </td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-default"
                                   href="{{ url('admin/backup/download/'.$backup['file_name']) }}"><i
                                        class="fa fa-cloud-download"></i> Download</a>
                                <a class="btn btn-sm btn-danger" data-button-type="delete"
                                   href="{{ url('admin/backup/delete/'.$backup['file_name']) }}"><i class="fa fa-trash-o"></i>
                                    Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="well">
                    <h4>There are no backups</h4>
                </div>
            @endif
        </div>
    </div>
@endsection