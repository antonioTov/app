<?
/**
 * Класс-обертка для обращения к адресной строке
 *
 * @author Anton Tovstenko
 */
class System_Url
{

    /**
     * Получение параметров из Url-строки
     * @param null $section
     * @param null $type
     * @return bool|int|string
     */
    public function get( $section = null, $type = null )
    {
        $uri = array();
        
        $requestURI = explode('/', $_SERVER['REQUEST_URI']);
        $scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
        
        for($i= 0; $i < sizeof($scriptName); $i++) {
        
            if ( $requestURI[$i] == $scriptName[$i] ) {
				unset($requestURI[$i]);
			}
        }
                  
        foreach( array_values( $requestURI ) as $item )
        {
            if ( isset( $item ) && empty( $item ) ) continue;
            $uri[] = $item;
        }
    
        if(isset($uri[0])) { $pos = strrpos($uri[0], "?"); if ($pos === false) { $uri[0] = $this->sanitizeURL($uri[0]); } else { $uri[0] = $this->sanitizeURL(substr($uri[0], 0, $pos)); } }
        if(isset($uri[1])) { $pos = strrpos($uri[1], "?"); if ($pos === false) { $uri[1] = $this->sanitizeURL($uri[1]); } else { $uri[1] = $this->sanitizeURL(substr($uri[1], 0, $pos)); } }
        if(isset($uri[2])) { $pos = strrpos($uri[2], "?"); if ($pos === false) { $uri[2] = $this->sanitizeURL($uri[2]); } else { $uri[2] = $this->sanitizeURL(substr($uri[2], 0, $pos)); } }
        if(isset($uri[3])) { $pos = strrpos($uri[3], "?"); if ($pos === false) { $uri[3] = $this->sanitizeURL($uri[3]); } else { $uri[3] = $this->sanitizeURL(substr($uri[3], 0, $pos)); } }
        if(isset($uri[4])) { $pos = strrpos($uri[4], "?"); if ($pos === false) { $uri[4] = $this->sanitizeURL($uri[4]); } else { $uri[4] = $this->sanitizeURL(substr($uri[4], 0, $pos)); } }
        if(isset($uri[5])) { $pos = strrpos($uri[5], "?"); if ($pos === false) { $uri[5] = $this->sanitizeURL($uri[5]); } else { $uri[5] = $this->sanitizeURL(substr($uri[5], 0, $pos)); } }

        if( isset( $section ) && isset( $uri[$section] ) ) {

            if ( $type == 'string' || $type == 'str' )
                return strval( preg_replace( '/[^\p{L}\p{Nd}\d\s_\-\.\%\s]/ui', '', $uri[$section] ) );

            if ( $type == 'integer' || $type == 'int' )
                return intval( $uri[$section] );

            return $uri[$section];
        }
        elseif( ! empty( $uri ) )
            return false;
        else    
            return false;
    }


    private function sanitizeURL($url) 
    {
        $url = trim($url);
        $url = rawurldecode($url);
        $url = str_replace(array('--','&quot;','!','@','#','$','%','^','*','(',')','+','{','}','|',':','"','<','>','[',']','\\',';',"'",',','/','*','+','~','`','laquo','raquo',']>','&#8216;','&#8217;','&#8220;' ,'&#8221;','&#8211;' ,'&#8212;'),
                                    array('-','-','','','','','','','','','','','','','','','','','','','','','','','','','','','',''),
                                    $url);
        $url = str_replace('--','-',$url);
        $url = rtrim($url, "-");
        return $url;
    } 
}