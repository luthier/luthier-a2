<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Luthier authentication controller using Wouter's A2 library
 *
 * @package     Luthier/A2
 * @category    Controller
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Controller_Luthier_Auth extends Controller {

	/**
	 * User login
	 */
	public function action_login()
	{
		Kohana::$log->add(Log::DEBUG, 'Executing Controller_Luthier_Auth::action_login');

		$config = Kohana::config('luthier.auth');
		$a2 = A2::instance($config['instance']);

		// If user is already logged in, don't do anything
		if ($a2->logged_in())
		{
			Kohana::$log->add($config['log'], 'Attempt to login made by logged-in user');
			Luthier::message(Kohana::message('a2', 'login.already'), Luthier::ERROR);
			$this->request->redirect( Route::get('luthier')->uri() );
		}

		$view = Kostache::factory('luthier/auth/login')
			->bind('post', $post)
			->bind('errors', $errors);

		if ($_POST)
		{
			$post = Validation::factory($_POST)
				->rule('username', 'not_empty')
				->rule('password', 'not_empty');

			if ($post->check())
			{
				if ($a2->a1->login($post['username'], $post['password'],
					! empty($post['remember'])))
				{
					Kohana::$log->add($config['log'], 'Successful login made with username :name',
						array(':name' => $post['username']));
					Luthier::message(Kohana::message('auth', 'login.success'), Luthier::INFO);
					$this->request->redirect( Route::get('luthier')->uri() );
				}
				else
				{
					Kohana::$log->add($config['log'], 'Unsuccessful login attempt made with username :name',
						array(':name' => $post['username']));
					Luthier::message(Kohana::message('auth', 'login.failure'), Luthier::ERROR);
				}
			}

			$errors = $post->errors();
		}

		$this->response->body($view->render());
	}

	/**
	 * User logout
	 */
	public function action_logout()
	{
		Kohana::$log->add(Log::DEBUG, 'Executing Controller_Luthier_Auth::action_logout');

		$config = Kohana::config('luthier.auth');
		$a2 = A2::instance($config['instance']);
		$a2->a1->logout();

		Kohana::$log->add($config['log'], 'Successful logout made by user');
		Luthier::message(Kohana::message('auth', 'logout.success'), Luthier::INFO);
		$this->request->redirect( Route::get('luthier')->uri() );
	}

}
