<?
/**
 * Class Cache
 * ����� ��� ����������� �������
 *
 *  @author 	Anton Tovstenko
 */
class System_Cache
{

    /**
     * ����� � ������� ����
     * @var string
     */
    private $_cache_dir = '/cache/';


    /**
     * ������ �������� ���������/��������� ���������� ��������.
     * @var bool
     */
    public $enable = false;


    /**
     * ���������� ����������� �������� � ���
     * @param $content
     * @return bool
     */
    public function  setCache($content)
    {

        if ( ! System_Config::get('cache')  || ! $this->enable ) {
            return false;
        }

        $path = $this->_getCachePath();

        if ( file_exists( $path ) ) {
            return false;
        }

        file_put_contents( $path, $content );

    }


    /**
     * ��������� ����������� �������� �� ����
     * ���� ��� ����������, �� ������� ��� ����������
     * ����� ��������� ������������ ��������
     */
    public function getCache()
    {
        $path = $this->_getCachePath();

        if ( file_exists( $path ) ) {
            print file_get_contents( $path );
            ob_end_flush();
            exit;
        }
        else {
            $this->enable = true;
        }

    }


    /**
     * ������� ����
     */
    public function clear()
    {
            if ($objs = glob(ROOT.$this->_cache_dir."/*")) {
                foreach($objs as $obj) {
                    unlink($obj);
                }
            }
    }

    /**
     * ��������� ����� ����� ����
     * @return string
     */
    private function _createName()
    {
        return urlencode(md5($_SERVER['REQUEST_URI']));
    }


    /**
     * ��������� ���� � ����� ����
     * @return string
     */
    private function _getCachePath()
    {
        return ROOT.$this->_cache_dir.$this->_createName();
    }


}