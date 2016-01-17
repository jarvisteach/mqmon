<html>

<head>
    <title>RoboRecipes MQTT on <?= gethostname(); ?></title>
    <link rel="stylesheet" href="resources/style.css" type="text/css">
    <style type="text/css"></style>
    <script src="resources/mqtts.js"></script>
</head>

<body onload="initPage()">

    <table id="messageTable">
	<caption>RoboRecipes MQTT on <?= gethostname(); ?></caption>
	<thead>
	    <tr><th>Topic</th><th>Message</th></tr>
	</thead>
	<tfoot>
	    <tr><td colspan="2">RoboRecipes.com - Arduino Home Monitoring System</td></tr>
	</tfoot>

	<tbody>
		<!-- javascript will auto populate here -->
	</tbody>

	<tbody>
		<form name="publishMessageForm" action="index.hrml">
		<tr>
			<th>Publish A Message:</th>
			<th class="errorCell" id="publishResults"></th>
		</tr>
		<tr>
			<td colspan="2"><select name="topic" id="topicSelect">
				<option value="" disabled>  -- select the topic --  </option>
			</select>&nbsp;
			<input name="message" type="text" size="24" placeholder="  -- enter a message --  ">&nbsp;
			<button type="button" onclick="publishMessage();">Publish</button></td>
		</tr>

		</form>
		</p>
	</tbody>
    </table>

	<ul>
	<li><a href="http://knolleary.net/arduino-client-for-mqtt/">MQTT Client</a></li>
	<li><a href="http://www.airspayce.com/mikem/arduino/RadioHead/index.html">RadioHead</a></li>
	</ul>
</body>
</html>
