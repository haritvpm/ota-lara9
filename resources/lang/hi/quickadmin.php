<?php

return [
		'user-management' => [		'title' => 'User management',		'fields' => [		],	],
		'roles' => [		'title' => 'Roles',		'fields' => [			'title' => 'Title',		],	],
		'users' => [		'title' => 'Users',		'fields' => [			'name' => 'Name',			'email' => 'Email',			'password' => 'Password',			'role' => 'Role',			'remember-token' => 'Remember token',			'username' => 'User Id',		],	],
		'designations' => [		'title' => 'Designations',		'fields' => [			'designation' => 'Designation',			'rate' => 'Rate',		],	],
		'employees' => [		'title' => 'Employees',		'fields' => [			'name' => 'Name',			'pen' => 'PEN',			'designation' => 'Designation',		],	],
		'sessions' => [		'title' => 'Sessions',		'fields' => [			'name' => 'Name',			'kla' => 'KLA',			'session' => 'Session',			'dataentry-allowed' => 'Dataentry allowed',			'show-in-datatable' => 'Show in datatable',		],	],
		'calenders' => [		'title' => 'Calenders',		'fields' => [			'date' => 'Date',			'day-type' => 'Type of Day',			'session' => 'Session',		],	],
		'settings' => [		'title' => 'Settings',		'fields' => [			'name' => 'Name',			'value' => 'Value',		],	],
		'routing' => [		'title' => 'Routing',		'fields' => [			'user' => 'User',			'route' => 'Route',		],	],
		'forms' => [		'title' => 'Forms ',		'fields' => [			'session' => 'Session',			'creator' => 'Creator',			'owner' => 'Owner',			'form-no' => 'Form no',			'overtime-slot' => 'Overtime slot',			'duty-date' => 'Duty date',			'date-from' => 'Date from',			'date-to' => 'Date to',		],	],
		'overtimes' => [		'title' => 'Overtimes',		'fields' => [			'pen' => 'Pen',			'designation' => 'Designation',			'form' => 'Form',			'from' => 'From date or time',			'to' => 'To date or time',			'count' => 'Count',			'worknature' => 'Worknature/Remarks	',		],	],
	'qa_create' => 'बनाइए (क्रिएट)',
	'qa_save' => 'सुरक्षित करे ',
	'qa_edit' => 'संपादित करे (एडिट)',
	'qa_view' => 'देखें',
	'qa_update' => 'सुधारे ',
	'qa_list' => 'सूची',
	'qa_no_entries_in_table' => 'टेबल मे एक भी एंट्री नही है ',
	'qa_custom_controller_index' => 'विशेष(कस्टम) कंट्रोलर इंडेक्स ।',
	'qa_logout' => 'लोग आउट',
	'qa_add_new' => 'नया समाविष्ट करे',
	'qa_are_you_sure' => 'आप निस्चित है ?',
	'qa_back_to_list' => 'सूची पे वापस जाए',
	'qa_dashboard' => 'डॅशबोर्ड ',
	'qa_delete' => 'मिटाइए',
	'quickadmin_title' => 'Overtime Allowance',
];