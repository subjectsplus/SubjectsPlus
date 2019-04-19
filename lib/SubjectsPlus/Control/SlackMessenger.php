<?php
/**
 *
 */
namespace SubjectsPlus\Control;

class SlackMessenger {

	public $message;
	public $channel;
	public $icon;
	public $webhookurl;


	public function __construct() {
	}

	/**
	 * @return mixed
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param mixed $message
	 */
	public function setMessage( $message ) {
		$this->message = $message;
	}

	/**
	 * @return mixed
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * @param mixed $channel
	 */
	public function setChannel( $channel ) {
		$this->channel = $channel;
	}

	/**
	 * @return mixed
	 */
	public function getIcon() {
		return $this->icon;
	}

	/**
	 * @param mixed $icon
	 */
	public function setIcon( $icon ) {
		$this->icon = $icon;
	}

	/**
	 * @return mixed
	 */
	public function getWebhookurl() {
		return $this->webhookurl;
	}

	/**
	 * @param mixed $webhookurl
	 */
	public function setWebhookurl( $webhookurl ) {
		$this->webhookurl = $webhookurl;
	}
	

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
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec( $ch );
		curl_close( $ch );

		return $result;
	}




}