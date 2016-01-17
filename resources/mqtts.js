function initPage()
{
	popTopicSelect();
	initRows();
}

function popTopicSelect()
{
	var myTopics = getTopics();

	var mySelect = document.getElementById("topicSelect");
	for(loop in myTopics)
	{
		mySelect.options[mySelect.options.length] = new Option(myTopics[loop]);
	}
}

(function processTopics()
{
	setTimeout(processTopics, 2500);


	// the topics we are subscribed to
	var myTopics = getTopicsString();

	var self = this;

	var xmlHttpReq = false;
	if (window.XMLHttpRequest) { // Mozilla/Safari
		self.xmlHttpReq = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) { // IE
		self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	}

	// first param is to stop caching
	var url = "getTopics.php?ranNum=" + Math.random() + "&topic=" + encodeURIComponent(myTopics);

	self.xmlHttpReq.open('POST', url, false);
	self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

	// this takes up to 2.5 seconds!!!!!
	self.xmlHttpReq.onreadystatechange = function()
	{
		if (self.xmlHttpReq.readyState == 4 && self.xmlHttpReq.status == 200)
		{
			// convert our JQuery response (in JSON) to an array
			try {
				var myMessageObjs = JSON.parse(self.xmlHttpReq.responseText);
				for (var i=0; i < myMessageObjs.length; i++)
				{
					var myMessageObj = myMessageObjs[i];

					// get the row for this query
					var tbl = document.getElementById('messageTable').tBodies[0];
					var row = getRow(tbl, myMessageObj.topic);	

					var tpCell = getCell(row, 0)
					var msCell = getCell(row, 1)

					// set the data for our cells
					tpCell.innerHTML = myMessageObj.topic;

					if(myMessageObj.status == "ok")
					{
						msCell.innerHTML = myMessageObj.message;
							msCell.className = "";
					}
					else
					{
						msCell.innerHTML = myMessageObj.status;
						msCell.className = "errorCell";
					}
				}
			} catch (e) {
				msCell.innerHTML = e + "(" + self.xmlHttpReq.responseText + ")";
				msCell.className = "errorCell";
			}
		}
		else
		{
			// nothing for now
		}

	}

	self.xmlHttpReq.send();
}) ()

function initRows()
{
	var rowNames = getTopics();
	for (var i=0; i < rowNames.length; i++)
	{
		// get the row for this query
		var tbl = document.getElementById('messageTable').tBodies[0];
		var row = getRow(tbl, rowNames[i]);	

		var tpCell = getCell(row, 0)
		var msCell = getCell(row, 1)

		// set the data for our cells
		tpCell.innerHTML = rowNames[i];

		msCell.innerHTML = "no message";
		msCell.className = "errorCell";
	}
}

function getCell(row, pos)
{
	var cells = row.getElementsByTagName('td');
	if(cells.length == pos) return row.insertCell(-1);
	else return cells[pos];
}

function getRow(tbl, id)
{
	var row = document.getElementById(id);
	if (row == null)
	{
		row = tbl.insertRow(-1);
		row.id = id;
	}
	return row;
}

function getTopicsString()
{
	return getTopics().join(",");
}

function getTopics()
{
	return [
		"WEATHER/a",
		"AHMS/door",
		"AHMS/doorCl",
		"AHMS/move",
		"AHMS/pir",
		"AHMS/timeNow",
		"AHMS/dateNow",
		"AHMS/alarm",
		"AHMS/memory",
		"AHMS/brdTemp", 
		"AHMS/brdLt",
		"AHMS/outTemp",
		"AHMS/outLt",
		"AHMS/timeUp",
		"AHMS/SNIFF",
		"AHMS/NFC",
		"AHMS/CMD",
		"AHMS/empty"
		];
}

function publishMessage()
{
	var self = this;

	var xmlHttpReq = false;
	if (window.XMLHttpRequest) { // Mozilla/Safari
		self.xmlHttpReq = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) { // IE
		self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	}

	var myTopic = document.publishMessageForm.elements["topic"].value;
	var myMessage = document.publishMessageForm.elements["message"].value;

	// first param is to stop caching
	var url = "pubMess.php?ranNum=" + Math.random() + "&topic=" + encodeURIComponent(myTopic) + "&message=" + encodeURIComponent(myMessage);

	self.xmlHttpReq.open('POST', url, false);
	self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

	self.xmlHttpReq.onreadystatechange = function()
	{
		if (self.xmlHttpReq.readyState == 4 && self.xmlHttpReq.status == 200)
		{
			// convert our JQuery response (in JSON) to an array
			var myMessageObj = JSON.parse(self.xmlHttpReq.responseText);
			if(myMessageObj.status == "ok")
			{
				document.getElementById('publishResults').innerHTML = "Message submitted";
			}
			else
			{
				document.getElementById('publishResults').innerHTML = myMessageObj.status;
			}
		}
		else
		{
			document.getElementById('publishResults').innerHTML = "Error connecting to server";
		}

	}

	self.xmlHttpReq.send();
}
