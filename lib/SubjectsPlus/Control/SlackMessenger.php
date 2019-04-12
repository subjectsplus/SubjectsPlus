<?php
/**
 *
 */
namespace SubjectsPlus\Control;

class SlackMessenger {


	public function __construct() {
	}


	public function send($message, $channel, $icon, $webhookUrl ) {
		$channel = ( $channel ) ? $channel : "talkback";
		$data    = "payload=" . json_encode( array(
				"channel"    => "#{$channel}",
				"text"       => $message,
				"icon_emoji" => $icon
			) );

		// Get your webhook endpoint from your Slack settings
		$ch = curl_init( $webhookUrl );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec( $ch );
		curl_close( $ch );


		return $result;
	}




}