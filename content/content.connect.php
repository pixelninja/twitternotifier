<?php

require_once(TOOLKIT . '/class.administrationpage.php');
require_once(EXTENSIONS . '/twitternotifier/lib/twitteroauth/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;

class contentExtensionTwitterNotifierConnect extends AdministrationPage {
	protected $_driver = null;
	protected $_uri = null;
	protected $TwitterOAuth = null;

	public function __construct() {
		parent::__construct();
		$this->_driver = Symphony::ExtensionManager()->create('twitternotifier');
		$this->_uri = URL . "/symphony/extension/twitternotifier/callback/";
	}

	public function __viewIndex() {
		$this->TwitterOAuth = new TwitterOAuth(
			$this->_driver->getConsumerKey(),
			$this->_driver->getConsumerSecret()
		);

        $request_token = $this->TwitterOAuth->oauth("oauth/request_token");

		unset($request_token['oauth_callback_confirmed']);
        
		Symphony::Database()->insert($request_token, $this->_driver->table);

		if($this->TwitterOAuth->getLastHttpCode() == 200) {
            $url = $this->TwitterOAuth->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
			redirect("Location: " . $url);
		}
		else {
			// Exception? Error?
		}
	}
}

?>
