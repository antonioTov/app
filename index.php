<?
//xdebug_start_trace('dm');
$time_start = microtime(true);
//��������� ������
    session_start();     

    define("DEBUG_MODE", true);
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
    
//��������� ��������� �� �������  
    ini_set("display_errors", DEBUG_MODE);

    include(ROOT."/system/_autoload.php");
		
//������� ����������
    $app = new System_App();
    $app::run();


//xdebug_stop_trace();