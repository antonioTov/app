<?php


//Функция автоматической загрузки классов           
function __autoload($className) 
{
    $className 	= str_replace('_','/',$className);
	$class_path = $className.".php";
	$class_path = strtolower(substr($class_path,0,strripos($class_path,'/')+1)).ucfirst(substr($class_path,strripos($class_path,'/')+1));
    
    if(file_exists(ROOT.'/'.$class_path))
        require_once(ROOT.'/'.$class_path);
}


register_shutdown_function('work_time');

// функция опеределния времени работы скрипта 
function work_time() 
{
    	global $time_start;        
        echo "<style>
            .debug_info { border: 1px solid rgb(80, 175, 66); position: fixed; bottom: 10px; left: 10px; z-index: 99999999; }
            .debug_info tbody { background: rgb(180, 255, 157) !important; }
            .debug_info td { padding: 4px; }
            .close43 { font-size: 10px; font-weight: bold; color: rgb(69, 133, 57); display: inline-block; float: right; cursor: pointer; padding: 0 3px; }
            .debug_info_header { color: rgb(92, 96, 150); font-weight: bold; }
            </style>
            
            <table class='debug_info' id='debug_info'>
                <tr>
                    <td class='debug_info_header'>Debug info</td>
                    <td><div class='close43'>X</div></td>
                </tr>
                <tr>
                    <td>Memory:</td>
                    <td>".round(memory_get_peak_usage()/1048576,3)." Mb</td>
                </tr>
                <tr>
                    <td>Time:</td>
                    <td>".round(microtime(true)-$time_start, 3)." s</td>
                </tr>
            </table>
            
            <script type='text/javascript'>
            $(document).ready(function(){
                $('.close43').click(function(){
                    $('#debug_info').fadeOut(100);
                });
            });
            </script>
            ";
}
