<?
/**
 * Обработчик ошибок
 */
class ErrorController extends System_Controller
{

	public function indexAction()
    {
        if ( System_Errors::isErrors() ) {

            if ( DEBUG_MODE ) {

                foreach( System_Errors::getAll() as $mess) {
                    echo $mess.'<br/>';
                }

                exit;

            } else {
                $this->view->setLayout('error');
            }
        }
    }


	/**
	 * Ошибка 404
	 */
	public function error404Action()
    {
        $this->view->setLayout('error');
    }


	/**
	 * Ошибка 403
	 */
	public function error403Action()
	{
		$this->view->setLayout('error');
	}
}