 <?php
	function sendMessage($topic, $message, $mqtt)
	{
		if ($mqtt->connect())
		{
			$mqtt->publish($topic,$message,0,1);
			return true;
		}
		else
		{
			return false;
		}
	}

	require("resources/phpMQTT.php");
	$mqtt = new phpMQTT("192.168.1.10", 1883, "Web PHP MQTT Client", "ahmsNode", "ahms2013");

	$message = trim($_GET["message"]);
	$topic = trim($_GET["topic"]);
	if (isset($message) && isset($topic) && strlen($message) > 0 && strlen($topic) > 0)
	{
		if(sendMessage($_GET["topic"], $_GET["message"], $mqtt))
		{
			$message = array ( "status" => "ok", "topic" => $topic, "message" => $message );
			echo json_encode($message);
		}
		else
		{
			$message = array ( "status" => "failes to send message", "topic" => $topic, "message" => $message );
			echo json_encode($message);
		}
	}
	else
	{
		$message = array ( "status" => "invalid arguments", "topic" => $topic, "message" => $message );
		echo json_encode($message);
	}
?>
