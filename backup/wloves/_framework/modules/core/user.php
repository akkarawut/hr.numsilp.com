<?php
class W_User
{
	public static $get_current = [];
	public static $get_permission = [];
	static function isLogin()
	{
		$is_login = false;
		if (isset(static::$get_current['Dev']['is_login']) && static::$get_current['Dev']['is_login']) {
			$is_login = true;
		} else if (isset(static::$get_current['User']['is_login']) && static::$get_current['User']['is_login']) {
			$is_login = true;
		}

		return $is_login;
	}

	static function isDev()
	{
		$is_login = false;
		if (isset(static::$get_current['Dev']['is_login']) && static::$get_current['Dev']['is_login']) {
			$is_login = true;
		}
		return $is_login;
	}

	static function isLoginUser()
	{
		$is_login = false;

		if (isset(static::$get_current['User']['is_login']) && static::$get_current['User']['is_login']) {
			$is_login = true;
		}

		return $is_login;
	}

	static function getCurrentDev()
	{
		$dev = [];
		$path_link = '../../../';
		$this_url = $_SERVER['REQUEST_URI'];
		$this_url =  explode('/', $this_url);
		$key = array_search('_framework', $this_url);
		if (!$key) {
			$path_link = '../../';
		}
		if (static::$get_current['Dev']) {
			$dev['id'] = static::$get_current['Dev']['id'];
			$dev['username'] = static::$get_current['Dev']['username'];
			$dev['user_type'] = static::$get_current['Dev']['user_type'];
			$dev['profile_image'] = $path_link . '_framework/structure/image/placeholder/user.png';
		}
		return $dev;
	}

	static function getCurrentUser()
	{
		$user = [];
		if (static::$get_current['User']) {
			$user['id'] = static::$get_current['User']['id'];
			$user['username'] = static::$get_current['User']['username'];
			$user['user_type'] = static::$get_current['User']['user_type'];
			$user['profile_image'] = static::$get_current['User']['profile_image'];
		}
		return $user;
	}

	static function getCurrentUserID()
	{
		return static::$get_current['User']['id'];
	}
	static function getPermission()
	{
		return static::$get_permission;
	}
}
