<?
/**
 * Class View
 * Супер-класс для генерации Html-страниц
 * Принимает данные из контроллера действий
 * Подставляет данные в шаблон
 * формирует готовую страницу
 *
 * @author Anton Tovstenko
 */
class System_View
{

    /**
     * Название файла представления
     * По умолчанию он определяетcя как название Действия
     * @var null
     */
    private $view    = null;


    /**
     * Название файла обложки (основного шаблона)
     * @var string
     */
    private $layout  = 'main';


    /**
     * Массив данных, поступающих из контролера
     * @var array
     */
    private $data    = array();


    /**
     * Переменная для хранения готовой страницы представления
     * @var string
     */
    private $content;


    /**
     * Экземпляр класса Cache
     * Определяется в процессе создания экземпляра класса View в главном контроллере
     * @var null
     */
    public  $cache   = null;


    /**
     * Определение основного шаблона
     * По умолчанию 'main'
     * @param $layout
     */
    public function setLayout( $layout )
    {
        $this->layout = $layout;
    }


    /**
     * Получение текущего названия основного шаблона
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }


    /**
     * Определение названия файла представления
     * @param $view
     */
    public function tpl( $view )
    {
        $this->view = $view;
    }


    /**
     * Передача данных из контроллера в шаблон
     * @param $name
     * @param $value
     */
    public function assign( $name, $value )
    {
        $this->data[$name] = $value;
    }


    /**
     * Генерация представления
     * Подстановка данных в представление
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
     * Генерация всей страницы
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
     * Магический метод, позволяющий получать данные в представлении через $this
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
     * Магический метод, позволяющий записывать данние в представление через свойства его экземпляра
     * Аналог метода assign()
     * @param $name
     * @param $value
     */
    public function __set ( $name , $value )
    {
        $this->data[$name] = $value;
    }

}

