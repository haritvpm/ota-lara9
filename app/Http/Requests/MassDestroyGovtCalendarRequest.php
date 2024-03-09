<?php

namespace App\Http\Requests;

use App\GovtCalendar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyGovtCalendarRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('govt_calendar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:govt_calendars,id',
        ];
    }
}
