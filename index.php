<?
//xdebug_start_trace('dm');
$time_start = microtime(true);
//Запускаем сессию
    session_start();     

    define("DEBUG_MODE", true);
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
    
//Настройка сообщений об ошибках  
    ini_set("display_errors", DEBUG_MODE);

    include(ROOT."/system/_autoload.php");
		
//Создаем приложение
    $app = new System_App();
    $app::run();


//xdebug_stop_trace();