<?
/**
 * Class Session
 * Класс для работы с сессионным массивом
 *
 * @author Anton Tovstenko
 */
class System_Session
{

    /**
     * Получение значения
     * @param $name
     * @return null
     */
    public static function getParam( $name )
    {
        return isset( $_SESSION[$name] ) ? $_SESSION[$name] : null;
    }


    /**
     * Сохранение значения
     * @param $name
     * @param $value
     */
    public static function setParam( $name, $value )
    {
        $_SESSION[$name] = $value;
    }



    /**
     * Удаление значения
     * @param $name
     */
    public static function delParam( $name )
    {
        if ( isset( $_SESSION[$name] ) ) {
            unset( $_SESSION[$name] );
        }
    }


    /**
     * Разрушение сессии
     */
    public static function destroy()
    {
        session_destroy();
    }


    /**
     * Вывод сессионного массива в форматированом виде
     */
    public static function show()
    {
        print '<pre>';
        print_r($_SESSION);
        print '</pre>';
    }


}