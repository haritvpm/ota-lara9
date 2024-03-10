<?php

namespace App\Http\Requests\Admin;

use App\GovtCalendar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreGovtCalendarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('govt_calendar_create');
    }

    public function rules()
    {
        return [
            'date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'govtholidaystatus' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'restrictedholidaystatus' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'bankholidaystatus' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'success_attendance_fetched' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'success_attendance_lastfetchtime' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'success_attendance_rows_fetched' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'attendance_today_trace_fetched' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'attendancetodaytrace_lastfetchtime' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'attendance_today_trace_rows_fetched' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
