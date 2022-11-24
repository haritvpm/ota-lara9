<?php

namespace App\Http\Controllers\Api\V1;

use App\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSettingsRequest;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use Yajra\DataTables\DataTables;

class SettingsController extends Controller
{
    public function index()
    {
        return Setting::all();
    }

    public function show($id)
    {
        return Setting::findOrFail($id);
    }

    public function update(UpdateSettingsRequest $request, $id)
    {
        $setting = Setting::findOrFail($id);
        $setting->update($request->all());
        

        return $setting;
    }

    public function store(StoreSettingsRequest $request)
    {
        $setting = Setting::create($request->all());
        

        return $setting;
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return '';
    }
}
