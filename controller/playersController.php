<?
/**
 * Обработчик действий над игроками
 */
class PlayersController extends System_Controller
{

	/**
	 * Инициализация
	 */
	public function init() {

		// передаем объект идентификатора в шаблон
        $this->view->ident = $this->ident;

		$request = new Component_Request();

		// обрабатываем события с группой выделеных записей
		if( $request->post('event') )
		{
			$this->event( $request->post('event') );
		}
    }

	/**
	 * Виводим список всех игроков
	 */ 
	public function indexAction()
    {
        $this->view->cache->getCache();
        
        $xcache = new Component_XCache();

        if($data = $xcache->get('players')){
            $data = unserialize($data);
            echo 'cache';
        }
        else {
            $model 		= new System_Model();
            $players	= $model->players;
            $data       = $players->getAll();
            $xcache->set('players', serialize($data)); 
        }
 
        $this->view->players = $data;

    }


	/**
	 * Добавление игрока
	 */
	public function addAction()
    {
		$request = new Component_Request();

		// если пришли данные из формы, проверяем их
		if ( $request->post() )
		{
			$this->validProcess($request);
		}

		// определяем котекст, чтобы понимать,
		// что форма выводится для добавления или редактирования данных
		$this->view->context 	= 'add';

		// ID админа, который будет создателем игрока
		$this->view->admin_id 	= $this->ident->getId();

		// Выджет легенды
		$this->view->legend 		= Widget_Legend::getEditLegend('Добавление нового игрока','New player');

		// Переопределяем шаблон представления
		$this->view->tpl('form');
        
    }


	/**
	 * Редактирование данных игрока
	 */
	public function editAction()
	{
		$request 	= new Component_Request();

		if ( $request->post() )
		{
			$this->validProcess( $request );
		}

		// получаем ID редактируемого игрока из адресной строки
		$url			= new System_Url();
		$player_id 	= $url->get(2,'int');

		// получаем данные редактируемого игрока
		$model 		= new System_Model();
		$player		= $model->players;
		$data 		= $player->getById($player_id);

        if($data){
            // передаем все данные в шаблон представления
    		foreach( $data as $key => $val ) {
    			$this->view->$key = $val;
    		}
    
    		$this->view->context 	= 'edit';
    		$this->view->player_id	= $player_id;
    		$this->view->admin_id 	= $data->admin_id;
    		$this->view->legend 		= Widget_Legend::getEditLegend('Редактирование игрока','New player');
    		$this->view->tpl('form');
        }
        else {
            $this->call( array(
                'controller' =>'error', 
                'action' => 'error404'
                ));
                
            exit;
        }
		
	}


	/**
	 * Удаление игрока
	 */
	public function deleteAction()
	{
		$url			= new System_Url();
		$player_id 	= $url->get(2,'int');

		$model 		= new System_Model();
		$player		= $model->players;

		if( $player->delete( $player_id ) ) {
			$this->redirect('/');
		}
        else {
            $this->call( array(
                'controller' =>'error', 
                'action' => 'error404'
                ));
                
            exit;
        }
	}



	/**
	 * Поиск игроков
	 */
	public function searchAction()
	{
		$model 		= new System_Model();
		$players		= $model->players;
		$admins		= $model->administrators;
		$columns 	= $players->getColumns();

		$condition 	= '';

		if ( !empty( $_GET ) )
		{
			// перебыраем все GET-параметры и сравниваем их с названиями полей таблици,
			// для предотвращения ошибок
			foreach ( $_GET as $key => $val )
			{
				if ( !empty( $val ) )
				{
					if ( in_array( $key, $columns ) )
					{
						// если задано имя админа, то переводим его в ID админа
						if ( $key == 'admin_id') {

							$admin_name = $admins->getByUserName( $val )->id;
							$val = ($admin_name) ? $admin_name : 'null';
						}

						// формируем условие
						$condition .= $players->table() . '.' . $key . " LIKE '%" . $players->escape( $val ) . "%' OR ";
					}
				}
			}

			// если не сформировалось условие, то ничего не выводим
			if ( !$condition ) {
				$condition = ' 0 ';
				$this->view->errorEmpty = true;
			} else {
				$condition = substr($condition, 0, -3);
			}

			// передаем все данные в шаблон представления
			foreach( $_GET as $key => $val ) {
				$this->view->$key = $val;
			}
		}

		// оставляем открытой форму поиска
		$this->view->showSearchForm = true;

		$this->view->players 	= $players->search($condition);
		$this->view->count 	= $players->count;
		$this->view->tpl('index');
	}



	/**
	 * Процесс валидации данных формы
	 * @param $request
	 */
	private function validProcess( $request )
	{
		$data = array(
			'username' 	=> $request->post('username'),
			'first_name' 	=> $request->post('first_name'),
			'last_name' 	=> $request->post('last_name'),
			'birth_date' 	=> $request->post('birth_date'),
			'email' 			=> $request->post('email'),
			'admin_id'		=> $request->post('admin_id','integer')
		);

		// Контекст. Редактирование или добавление
		$context 	= $request->post('context');

		$model 		= new System_Model();
		$player		= $model->players;

		$validator 	= new Component_Validator();

		if( $validator->is_valid($data) )
		{
			// получаем безопасные данные
			$playerData = $validator->getData();

			//используем во время редактирования
			$playerData['player_id'] =  $request->post('player_id','integer');

			// проверяем уникальность имени пользователя
			if( $player->getByName( $playerData['username'] ) && $context != 'edit' ) {
				// выводим ошибку, что username занят
				$this->view->errors = array('username' => true);
			}

			// проверяем длинну поля first_name
			if (!$validator->checkLength( $playerData['first_name'] )) {
				$this->view->errors = array(
													'length' 	=> System_Config::get('input_max_len'),
													'name' 	=> 'First name'
												);
			}

			// проверяем длинну поля last_name
			if (!$validator->checkLength( $playerData['last_name'] )) {
				$this->view->errors = array(
													'length' 	=> System_Config::get('input_max_len'),
													'name' 	=> 'Last name'
												);
			}

			// проверяем Email на валидность
			if (!$validator->checkEmail( $playerData['email'] )) {
				$this->view->errors = array('email' => true);
			}

			// если вся валидация прошла, сохраним данные
			if(!$this->view->errors)
			{
				if( $player->$context( $playerData ) )
				{
				    $xcache = new Component_XCache();
                    $xcache->remove('players');
					
                    $this->redirect('/');
				}
			}

		} else {
			$this->view->errors = array('empty' => true);
		}

		// передаем все данные в шаблон представления
		foreach( $data as $key => $val ) {
			$this->view->$key = $val;
		}
	}


	/**
	 * Проверка наличия имени игрока
	 * Ajax
	 */
	public function checkloginAction()
	{
		$request = new Component_Request();

		if( $request->isAjax() )
		{
			$username 	= $request->post('username');

			$model 			= new System_Model();
			$player			= $model->players;

			if( $player->getByName( $username ) )
			{
				echo json_encode( array(
					'match' => true
				) );

			} else {
				echo json_encode( array(
					'match' => false
				) );
			}
		}
		exit;
	}


	/**
	 * обработчик событий с группой выделеных записей
	 * пока тут одно событие, но его можно расширять
	 * @param $event
	 * @return bool
	 */
	private function event($event)
	{
		$request 	= new Component_Request();
		$model 		= new System_Model();
		$player		= $model->players;

		if( $items 	= $request->post('check') )
		{
			$ids = '';
			foreach( $items as $id)
			{
				$ids .= $id.", ";
			}
			$ids = substr($ids, 0, -2);

			if($event == 'delete') 	{
				$player->deleteByIds($ids);
			}

		}
		return true;
	}

}
