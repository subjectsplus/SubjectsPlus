<?php
/**
 *
 */
namespace SubjectsPlus\Control;

class SlackMessenger {

	private $_message;
	private $_channel;
	private $_icon;
	private $_webhookurl;

	/**
	 * SlackMessenger constructor.
	 */
	public function __construct() {
	}

	/**
	 * @return mixed
	 */
	public function getMessage() {
		return $this->_message;
	}

	/**
	 * @param mixed $message
	 */
	public function setMessage( $message ) {
		$this->_message = $message;
	}

	/**
	 * @return mixed
	 */
	public function getChannel() {
		return $this->_channel;
	}

	/**
	 * @param mixed $channel
	 */
	public function setChannel( $channel ) {
		$this->_channel = $channel;
	}

	/**
	 * @return mixed
	 */
	public function getIcon() {
		return $this->_icon;
	}

	/**
	 * @param mixed $icon
	 */
	public function setIcon( $icon ) {
		$this->_icon = $icon;
	}

	/**
	 * @return mixed
	 */
	public function getWebhookurl() {
		return $this->_webhookurl;
	}

	/**
	 * @param mixed $webhookurl
	 */
	public function setWebhookurl( $webhookurl ) {
		$this->_webhookurl = $webhookurl;
	}

	/**
	 * @return bool|string
	 */
	public function send() {
		$data = "payload=" . json_encode( array(
				"channel"    => "#{$this->getChannel()}",
				"text"       => $this->getMessage(),
				"icon_emoji" => $this->getIcon() 
			));

		// Get your webhook endpoint from your Slack settings
		$ch = curl_init( $this->getWebhookurl() );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_exec( $ch );
		curl_close( $ch );

		return true;
	}




}