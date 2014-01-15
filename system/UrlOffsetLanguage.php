<?
/**
 * Class UrlOffsetLanguage
 * Определяет смещение Url-параметров
 * Определяет текущий язык локали
 *
 * @author Anton Tovstenko
 */
class System_UrlOffsetLanguage
{


    /**
     * Значение смещеения Url-параметров
     * @var int
     */
    private $offset;


    /**
     * Определение смещения
     * Определение языка и передаче его главному контроллеру
     * @param $config
     * @param $url
     * @return int
     */
    public function init( $config , $url )
	{
        if( $url->get(0) )
		{
            if( in_array( $url->get(0), $config->list ) )
			{
                $this->offset	= 1;
                $language 		= $url->get(0);
            }
            else {
                $this->offset	= 0;
                $language 		= $config->default;
            }  
        } 
        else {
            $language = $config->default;
        }

		System_Controller::setLanguage($language);
        
        return $this->offset;

    }


    /**
     * Получение значения смещения
     * @return int
     */
    public function getOffset() {
        return $this->offset;
    }
    
    
    
    
    
}