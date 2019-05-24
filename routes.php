<?php

use controllers\http\Route;

#[ URI, METHOD, controllers, ACTION ]
#[  0      1         2        3    ]

$routeList = [
	['/OOPAPI/produtos','GET','site','index'],
	['/OOPAPI/produtos','POST','site','create'],
	['/OOPAPI/produtos/{id}','GET','site','show'],
	['/OOPAPI/produtos/{id}','PUT','site','update'],
	['/OOPAPI/produtos/{id}','DELETE','site','delete'],
	['/OOPAPI/token','PUT','site','jwt'],
	['/OOPAPI/token/{id}','POST','site','login'],
	
];

Route::search($routeList);