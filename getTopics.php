<?php
	include("resources/phpMQTT.php");
	error_reporting(0);

	$results = [];

	$topics = "WEATHER/a,AHMS/pir,AHMS/move,AHMS/door";

	if (isset($_GET["topic"])) { $topics = $_GET["topic"]; }

	$mqtt = new phpMQTT("192.168.1.20", 1883, "PHP MQTT Client", "ahmsclient", "ahms2013");

	if ($mqtt->connect())
	{
		foreach(explode(",",$topics) as $topic)
		{
			$myTopics = [];
			$myTopics[$topic] = array("qos"=>0, "function"=>"procmsg");
			$mqtt->subscribe($myTopics);
			if($mqtt->proc() == 0)
			{
				array_push($results, array ( "status" => "no message", "topic" => $topic, "message" => "" ));
			}
		}
		$mqtt->close();
	}
	else
	{
		array_push($results, array ( "status" => "ok", "no connection" => $topic, "message" => "" ));
	}

	echo json_encode($results);

	function procmsg($topic,$message)
	{
		global $results;
		array_push($results,array ( "status" => "ok", "topic" => $topic, "message" => $message ));
	}

?>
