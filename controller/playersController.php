<?
/**
 * ���������� �������� ��� ��������
 */
class PlayersController extends System_Controller
{

	/**
	 * �������������
	 */
	public function init() {

		// �������� ������ �������������� � ������
        $this->view->ident = $this->ident;

		$request = new Component_Request();

		// ������������ ������� � ������� ��������� �������
		if( $request->post('event') )
		{
			$this->event( $request->post('event') );
		}
    }

	/**
	 * ������� ������ ���� �������
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
	 * ���������� ������
	 */
	public function addAction()
    {
		$request = new Component_Request();

		// ���� ������ ������ �� �����, ��������� ��
		if ( $request->post() )
		{
			$this->validProcess($request);
		}

		// ���������� �������, ����� ��������,
		// ��� ����� ��������� ��� ���������� ��� �������������� ������
		$this->view->context 	= 'add';

		// ID ������, ������� ����� ���������� ������
		$this->view->admin_id 	= $this->ident->getId();

		// ������ �������
		$this->view->legend 		= Widget_Legend::getEditLegend('���������� ������ ������','New player');

		// �������������� ������ �������������
		$this->view->tpl('form');
        
    }


	/**
	 * �������������� ������ ������
	 */
	public function editAction()
	{
		$request 	= new Component_Request();

		if ( $request->post() )
		{
			$this->validProcess( $request );
		}

		// �������� ID �������������� ������ �� �������� ������
		$url			= new System_Url();
		$player_id 	= $url->get(2,'int');

		// �������� ������ �������������� ������
		$model 		= new System_Model();
		$player		= $model->players;
		$data 		= $player->getById($player_id);

        if($data){
            // �������� ��� ������ � ������ �������������
    		foreach( $data as $key => $val ) {
    			$this->view->$key = $val;
    		}
    
    		$this->view->context 	= 'edit';
    		$this->view->player_id	= $player_id;
    		$this->view->admin_id 	= $data->admin_id;
    		$this->view->legend 		= Widget_Legend::getEditLegend('�������������� ������','New player');
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
	 * �������� ������
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
	 * ����� �������
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
			// ���������� ��� GET-��������� � ���������� �� � ���������� ����� �������,
			// ��� �������������� ������
			foreach ( $_GET as $key => $val )
			{
				if ( !empty( $val ) )
				{
					if ( in_array( $key, $columns ) )
					{
						// ���� ������ ��� ������, �� ��������� ��� � ID ������
						if ( $key == 'admin_id') {

							$admin_name = $admins->getByUserName( $val )->id;
							$val = ($admin_name) ? $admin_name : 'null';
						}

						// ��������� �������
						$condition .= $players->table() . '.' . $key . " LIKE '%" . $players->escape( $val ) . "%' OR ";
					}
				}
			}

			// ���� �� �������������� �������, �� ������ �� �������
			if ( !$condition ) {
				$condition = ' 0 ';
				$this->view->errorEmpty = true;
			} else {
				$condition = substr($condition, 0, -3);
			}

			// �������� ��� ������ � ������ �������������
			foreach( $_GET as $key => $val ) {
				$this->view->$key = $val;
			}
		}

		// ��������� �������� ����� ������
		$this->view->showSearchForm = true;

		$this->view->players 	= $players->search($condition);
		$this->view->count 	= $players->count;
		$this->view->tpl('index');
	}



	/**
	 * ������� ��������� ������ �����
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

		// ��������. �������������� ��� ����������
		$context 	= $request->post('context');

		$model 		= new System_Model();
		$player		= $model->players;

		$validator 	= new Component_Validator();

		if( $validator->is_valid($data) )
		{
			// �������� ���������� ������
			$playerData = $validator->getData();

			//���������� �� ����� ��������������
			$playerData['player_id'] =  $request->post('player_id','integer');

			// ��������� ������������ ����� ������������
			if( $player->getByName( $playerData['username'] ) && $context != 'edit' ) {
				// ������� ������, ��� username �����
				$this->view->errors = array('username' => true);
			}

			// ��������� ������ ���� first_name
			if (!$validator->checkLength( $playerData['first_name'] )) {
				$this->view->errors = array(
													'length' 	=> System_Config::get('input_max_len'),
													'name' 	=> 'First name'
												);
			}

			// ��������� ������ ���� last_name
			if (!$validator->checkLength( $playerData['last_name'] )) {
				$this->view->errors = array(
													'length' 	=> System_Config::get('input_max_len'),
													'name' 	=> 'Last name'
												);
			}

			// ��������� Email �� ����������
			if (!$validator->checkEmail( $playerData['email'] )) {
				$this->view->errors = array('email' => true);
			}

			// ���� ��� ��������� ������, �������� ������
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

		// �������� ��� ������ � ������ �������������
		foreach( $data as $key => $val ) {
			$this->view->$key = $val;
		}
	}


	/**
	 * �������� ������� ����� ������
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
	 * ���������� ������� � ������� ��������� �������
	 * ���� ��� ���� �������, �� ��� ����� ���������
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
