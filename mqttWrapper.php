<?php
	include("resources/phpMQTT.php");
	error_reporting(0);

	$myServer = "not set";
	$myPort = "not set";
	$myUserName = "not set";
	$myPassword = "not set";
	$myIdentifier = "PHP MQTT Client";

	if (validParam("server")) { $myServer = $_GET["server"]; }
	if (validParam("port")) { $myPort = $_GET["port"]; }
	if (validParam("userName")) { $myUserName = $_GET["userName"]; }
	if (validParam("password")) { $myPassword = $_GET["password"]; }

	if (validParam("action"))
	{
		switch($_GET["action"])
		{
			case "getTopic": getTopic(); break;
			case "pubMess": pubMess(); break;
			default: 
				displayJson("invalid action", "", ""); break;
		}
	}


	function getMQTT()
	{
		global $myServer, $myPort, $myUserName, $myPassword, $myIdentifier;

		if(($myServer == "not set" || $myPort == "not set") ||
			($myUserName != "not set" && $myPassword == "not set" ))
		{
			// invalid details
			return null;
		}
		else if($myUserName == "not set" && $myPassword == "not set" )
		{
			// connected with no ACL
			return new phpMQTT($myServer, $myPort, $myIdentifier, "", "");
		}
		else
		{
			// connected with ACL
			return new phpMQTT($myServer, $myPort, $myIdentifier,
								$myUserName, $myPassword);
		}
	}

	function validParam($paramName)
	{
		if(isset($_GET[$paramName]) && strlen(trim($_GET[$paramName])) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function getTopic()
	{
		if (validParam("topic"))
		{
			$myTopic = $_GET["topic"];

			if(($mqtt = getMQTT()) == null)
			{
				displayJson("invalid connection details", $myTopic, "");
				return -1;
			}
			else
			{
				// connected to MQTT
			}

			if ($mqtt->connect())
			{
				$topics[$myTopic] = array("qos"=>0, "function"=>"procmsg");
				$mqtt->subscribe($topics);
				if($mqtt->proc() == 0)
				{
					displayJson("no message", $myTopic, "");
				}
				else
				{
					//displayJson("unknown error", $myTopic, "");
				}
				$mqtt->close();
			}
			else
			{
				displayJson("failed to connect", $myTopic, "");
			}
		}
		else
		{
			displayJson("invalid topic", "", "");
		}
	}

	function procmsg($topic,$message)
	{
		displayJson("ok", $topic, $message);
	}

	function displayJson($status, $topic, $message)
	{
		global $myServer, $myPort, $myUserName, $myPassword, $myIdentifier;

		$message = array ( "status" => $status,
				"topic" => $topic, "message" => $message,
				"server" => $myServer, "port"=>$myPort,
				"userName"=>$myUserName, "password"=>$myPassword);

		echo json_encode($message);
	}


	function pubMess()
	{
		$myTopic = $_GET["topic"];
		$myMessage = $_GET["message"];
		$myRetain = $_GET["retain"];

		if($myRetain == "true") $myRetain = 1;
		else $myRetain = 0;

		$myQos = 0;

		if (!validParam("message") & !validParam("topic"))
		{
			displayJson("invalid message & topic", "", "");
		}
		else if (!validParam("message"))
		{
			displayJson("invalid message", $myTopic, "");
		}
		else if (!validParam("topic"))
		{
			displayJson("invalid topic", "", $myTopic);
		}
		else
		{
			if(($mqtt = getMQTT()) == null)
			{
				displayJson("invalid connection details", $myTopic, $myMessage);
				return -1;
			}

			if ($mqtt->connect())
			{
				$mqtt->publish($myTopic,$myMessage,$myQos,$myRetain);
				displayJson("ok", $myTopic, $myMessage);
			}
			else
			{
				displayJson("failed to connect", $myTopic, $myMessage);
			}
		}
	}
?>
