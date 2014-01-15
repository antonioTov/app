<?
/**
 * Class View
 * �����-����� ��� ��������� Html-�������
 * ��������� ������ �� ����������� ��������
 * ����������� ������ � ������
 * ��������� ������� ��������
 *
 * @author Anton Tovstenko
 */
class System_View
{

    /**
     * �������� ����� �������������
     * �� ��������� �� ����������c� ��� �������� ��������
     * @var null
     */
    private $view    = null;


    /**
     * �������� ����� ������� (��������� �������)
     * @var string
     */
    private $layout  = 'main';


    /**
     * ������ ������, ����������� �� ����������
     * @var array
     */
    private $data    = array();


    /**
     * ���������� ��� �������� ������� �������� �������������
     * @var string
     */
    private $content;


    /**
     * ��������� ������ Cache
     * ������������ � �������� �������� ���������� ������ View � ������� �����������
     * @var null
     */
    public  $cache   = null;


    /**
     * ����������� ��������� �������
     * �� ��������� 'main'
     * @param $layout
     */
    public function setLayout( $layout )
    {
        $this->layout = $layout;
    }


    /**
     * ��������� �������� �������� ��������� �������
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }


    /**
     * ����������� �������� ����� �������������
     * @param $view
     */
    public function tpl( $view )
    {
        $this->view = $view;
    }


    /**
     * �������� ������ �� ����������� � ������
     * @param $name
     * @param $value
     */
    public function assign( $name, $value )
    {
        $this->data[$name] = $value;
    }


    /**
     * ��������� �������������
     * ����������� ������ � �������������
     * @param $controllerName
     * @param $actionName
     */
    public function content( $controllerName, $actionName )
    {
        if( ! isset( $this->view ) ) {
            $this->view = substr( $actionName, 0 , strpos( $actionName , 'Action' ) );
        }

        $controllerName = strtolower( substr( $controllerName , 0 , strpos( $controllerName , 'Controller' ) ) );
        
        ob_start();

        include ROOT.'/view/'.$controllerName.'/'.$this->view.'.html';
        
        $this->content = ob_get_contents();

        ob_end_clean();
        
    }


    /**
     * ��������� ���� ��������
     * @return string
     */
    public function renderLayout()
    {
        
        ob_start();

        include ROOT.'/layout/'.$this->layout.'.html';
        
        $res = ob_get_contents();
        
        $this->cache->setCache($res);

        ob_end_clean();
        
        return $res;
    }


    /**
     * ���������� �����, ����������� �������� ������ � ������������� ����� $this
     * @param $name
     * @return bool
     */
    public function __get( $name )
    {
        if ( array_key_exists( $name, $this->data ) ) {
            return $this->data[$name];
        }
        else {
            return false;
        }
    }



    /**
     * ���������� �����, ����������� ���������� ������ � ������������� ����� �������� ��� ����������
     * ������ ������ assign()
     * @param $name
     * @param $value
     */
    public function __set ( $name , $value )
    {
        $this->data[$name] = $value;
    }

}

