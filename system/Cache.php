<?
/**
 * Class Cache
 * Класс для кеширования страниц
 *
 *  @author 	Anton Tovstenko
 */
class System_Cache
{

    /**
     * Папка с файлами кеша
     * @var string
     */
    private $_cache_dir = '/cache/';


    /**
     * Данное свойство разрешает/запрешает кешировать страницу.
     * @var bool
     */
    public $enable = false;


    /**
     * Сохранение содержимого страницы в кеш
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
     * Получение содержимого страницы из кеша
     * Если кеш существует, то выводим его содержимое
     * Иначе разрешаем закешировать страницу
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
     * Очистка кеша
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
     * Генерация имени файла кеша
     * @return string
     */
    private function _createName()
    {
        return urlencode(md5($_SERVER['REQUEST_URI']));
    }


    /**
     * Получение пути к файлу кеша
     * @return string
     */
    private function _getCachePath()
    {
        return ROOT.$this->_cache_dir.$this->_createName();
    }


}