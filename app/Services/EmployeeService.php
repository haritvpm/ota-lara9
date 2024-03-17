<?php

namespace App\Services;
use Carbon\Carbon;
use App\Employee;

class EmployeeService {

  
    public  function getEmployeeType(Employee $emp) 
    {
    
        $desig = strtolower($emp->designation->designation);
        $category =   strtolower($emp->categories?->category); 
        // Log::info($desig);
        // Log::info($category);

        $isPartime = str_contains($desig,"part time") || str_contains($desig,"parttime") || 
                     str_contains($category,"parttime")||
                     $emp->designation->normal_office_hours == 3; //ugly
        $isFulltime = str_contains($category,"fulltime")||
                      $emp->designation->normal_office_hours == 6;

        $isWatchnward = str_contains($category,"watch") ;
        $isNormal = !$isPartime && !$isFulltime && !$isWatchnward;

        return [$isPartime,$isFulltime,  $isWatchnward,  $isNormal];
    }
   
}