<?php
function openapiautoload($classname)
{
    $classpath = getClassPath();
    if (isset($classpath[$classname]))
    {
        include($classpath[$classname]);
    }
}
function getClassPath()
{
    static $classpath=array();
    if(function_exists('apc_fetch'))
    {
        $classpath = apc_fetch('cdvphp:fanjiapeng:autoload:server:1453905785');
        if ($classpath) return $classpath;

        $classpath = getClassMapDef();
        apc_store('cdvphp:fanjiapeng:autoload:server:1453905785', $classpath, 86400); 
    }
    else if(function_exists("eaccelerator_get"))
    {
        $classpath = eaccelerator_get('cdvphp:fanjiapeng:autoload:server:1453905785');
        if ($classpath) return $classpath;

        $classpath = getClassMapDef();
        eaccelerator_put('cdvphp:fanjiapeng:autoload:server:1453905785', $classpath, 86400); 
    }
    else
    {
        $classpath = getClassMapDef();
    }
    return $classpath;
}
function getClassMapDef()
{
    return array(
        	"BasePdo" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/BasePdo/BasePdo.php",
			"BasePdoCurd" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/BasePdo/BasePdoCurd.php",
			"CacheInterface" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Cache/CacheInterface.php",
			"MemcachedCache" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Cache/MemcachedCache.php",
			"RedisCache" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Cache/RedisCache.php",
			"Censor" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Censor/Censor.php",
			"Code" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Code/Code.php",
			"Curl" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Curl/Curl.php",
			"HashTable" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/HashTable/HashTable.php",
			"BizResult" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Http/BizResult.php",
			"HttpRequest" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Http/HttpRequest.php",
			"HttpResponse" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Http/HttpResponse.php",
			"ConfigLoader" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Loader/ConfigLoader.php",
			"Loader" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Loader/Loader.php",
			"Logger" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Logger/Logger.php",
			"AbstractBaseAction" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Mvc/AbstractBaseAction.php",
			"Application" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Mvc/Application.php",
			"RandLottery" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/RandLottery/RandLottery.php",
			"Session" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Session/Session.php",
			"Sign" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Sign/Sign.php",
			"Sg" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Superglobal/Superglobal.php",
			"Superglobal" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Superglobal/Superglobal.php",
			"Timer" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/Timer/Timer.php",
			"View" => 			"/home/fanjiapeng/test/cdvphp/CdvPHP/View/View.php",
			"Constants" => 			"/home/fanjiapeng/test/cdvphp/Application/Constants/Constants.php",
			"ApiController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/Api.php",
			"BookController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/Book.php",
			"CacheController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/Cache.php",
			"ClassController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/Class.php",
			"GpcController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/Gpc.php",
			"HttpController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/Http.php",
			"IndexController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/Index.php",
			"LibraryController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/Library.php",
			"UserController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/User.php",
			"ViewTestController" => 			"/home/fanjiapeng/test/cdvphp/Application/Controller/ViewTest.php",
			"HttpLibrary" => 			"/home/fanjiapeng/test/cdvphp/Application/Library/Http.php",
			"UserLogic" => 			"/home/fanjiapeng/test/cdvphp/Application/Logic/User.php",
			"BookModel" => 			"/home/fanjiapeng/test/cdvphp/Application/Model/Book.php",
			"UserModel" => 			"/home/fanjiapeng/test/cdvphp/Application/Model/User.php",

    );
}
spl_autoload_register('openapiautoload');
?>