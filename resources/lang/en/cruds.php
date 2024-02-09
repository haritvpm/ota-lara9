<?php

return [
    'designation' => [
       
        'fields'         => [
          
            'punching'                   => 'Punching',
            'punching_helper'            => 'Whether this designation has punching enabled',
            'normal_office_hours'        => 'Normal Office Hours',
            'normal_office_hours_helper' => ' ',
        ],
    ],
    'punching' => [
        'title'          => 'Punching',
        'title_singular' => 'Punching',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'date'              => 'Date',
            'date_helper'       => ' ',
            'punch_in'          => 'Punch In',
            'punch_in_helper'   => ' ',
            'punch_out'         => 'Punch Out',
            'punch_out_helper'  => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'form'              => 'Form',
            'form_helper'       => ' ',
            'pen'               => 'PEN',
            'pen_helper'        => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
        ],
    ],
    'category' => [
        'title'          => 'Category',
        'title_singular' => 'Category',
        'fields'         => [
            'id'                         => 'ID',
            'id_helper'                  => ' ',
            'category'                   => 'Category',
            'category_helper'            => ' ',
            'normal_office_hours'        => 'Normal Office Hours',
            'normal_office_hours_helper' => ' ',
            'created_at'                 => 'Created at',
            'created_at_helper'          => ' ',
            'updated_at'                 => 'Updated at',
            'updated_at_helper'          => ' ',
            'deleted_at'                 => 'Deleted at',
            'deleted_at_helper'          => ' ',
            'punching'                   => 'Punching',
            'punching_helper'            => ' ',
        ],
    ],
];