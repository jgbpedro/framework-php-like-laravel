<?php 
namespace controllers\http;

/**
 * 
 - Get request uri
 - Get request method
 - Fiter on router list the current path and respective method
 *
**/

class Route{
 	
	public static $uri;
	public static $method;
	public static $code;

 	public static function search($routeList){
 		
 		#Set URI from resource
 		self::$uri = $_SERVER['REQUEST_URI'];
 		
 		#Set Method from request method
		self::$method = $_SERVER['REQUEST_METHOD'];

		#Set status code
		self::$code = 404;

		#Get Queries
		$query_str = parse_url(self::$uri, PHP_URL_QUERY);
		parse_str($query_str, $query_uri);

		#Get data from request method
		parse_str(file_get_contents("php://input"),$request);

		#Removing queries from URI
		self::$uri = str_replace($query_str, '', self::$uri);
		
		#Verify if query is empty
		self::$uri = !empty($query_str) ? substr(self::$uri, 0 , -1) : self::$uri;

		$request = array_merge($request, $query_uri);

 		foreach ($routeList as $route) {
			#Break the components of path
			$route[0] = _BASEPATH_.$route[0];
			$route_path = explode('/', $route[0]);
			$uri_path = explode('/', self::$uri);
			
			#String to compare
			$compare_uri = [];

			#Params from uri
			$params_uri = [];

			if (count($route_path) == count($uri_path)) {
				
				for($i = 0; $i < count($uri_path); $i++) {
					if ($route_path[$i] == $uri_path[$i]) {
							#
							array_push($compare_uri, $uri_path[$i]);

					}else{
						if(preg_match('/{(.*?)}/', $route_path[$i], $match) == 1){

							array_push($compare_uri, $uri_path[$i]);
							$params_uri[$match[1]] = $uri_path[$i];
						}
					}
				}

				#If URI path is equals to route path 
				if ($compare_uri == $uri_path) {
					#If method of path is allowed
					if (self::$method == $route[1]) {
						#Merge data with data from URI params						
						$request = array_merge($request, $params_uri);
						#Access method of resource path
						$classname = "controllers\\".ucfirst($route[2])."Controller";

						try {
							$controller = new $classname();	
							return $controller->{$route[3]}(count($request) == 0 ? [] : $request);

						} catch (\Exception $e) {
							print_r($e->getMessage());
						}

					}else{
						if (self::$method != $route[1]) {
							#Method not allowed
							self::$code = 405;
						}	
					}
				}else{
					$compare_uri = [];
					$params_uri = [];
				}
			}

		}

		return http_response_code(self::$code);
 	}
 } 