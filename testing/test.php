 

<html>
	<header>
		<title>test parent</title>
		<script type="text/javascript">
			//<![CDATA[
			
			var invocation = new XMLHttpRequest();
			var url = 'http://192.168.0.251/vawesome/ip.php';
			var invocationHistoryText;
			
			function callOtherDomain(){
				if(invocation)
				{    
					invocation.open('GET', url, true);
					invocation.onreadystatechange = handler;
					invocation.send(); 
				}
				else
				{
					invocationHistoryText = "No Invocation TookPlace At All";
					var textNode = document.createTextNode(invocationHistoryText);
					var textDiv = document.getElementById("textDiv");
					textDiv.appendChild(textNode);
				}
				
			}
			
			function handler(evtXHR)
			{
				if (invocation.readyState == 4)
				{
						if (invocation.status == 200)
						{
							var response = invocation.responseXML;
							var invocationHistory = response.getElementsByTagName('machine').item(0).firstChild.data;
							invocationHistoryText = document.createTextNode(invocationHistory);
							var textDiv = document.getElementById("textDiv");
							textDiv.appendChild(invocationHistoryText);
							
						}
						else
							alert("Invocation Errors Occured" + invocation.status );
				}
				else
					dump("currently the application is at" + invocation.readyState);
			}
			//]]>
			
		
		</script>
	</header>
	
	<body>
		<div>
			<input type="button" onclick="callOtherDomain()" value="Push Me!" ></input>
		</div>
		<div id="textDiv">
			<?php $input = '#text "? (192.168.0.26) at bc:ae:c5:12:6e:13 [ether] on eth0"';
			
			$ip = substr($input, stripos($input, '(') + 1, stripos($input, ')') - stripos($input, '(') - 1);
			$mac = substr($input, stripos($input, 'at ') + 3, stripos($input, '[') - stripos($input, 'at ') - 5);
			
			echo $ip; 
			echo $mac;?>
		</div>
	</body>
</html>