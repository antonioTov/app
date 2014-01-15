<?
/**
 * Class UrlOffsetLanguage
 * ���������� �������� Url-����������
 * ���������� ������� ���� ������
 *
 * @author Anton Tovstenko
 */
class System_UrlOffsetLanguage
{


    /**
     * �������� ��������� Url-����������
     * @var int
     */
    private $offset;


    /**
     * ����������� ��������
     * ����������� ����� � �������� ��� �������� �����������
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
     * ��������� �������� ��������
     * @return int
     */
    public function getOffset() {
        return $this->offset;
    }
    
    
    
    
    
}