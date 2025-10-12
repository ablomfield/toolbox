<header>
	<h2>Webex Calling Local Gateway Configuration Generator</h2>
	<p>This tool uses the Webex APIs to retrieve information about your Local Gateway trunk configuration and automatically builds the configuration for you to paste into your Cisco CUBE.</p>
</header>
<p>Before applying the configuration there are some prerequisite tasks that must be completed before you can apply the LGW config.</p>
<h3>Basic Configuration</h3>
<p>Before continuing, you need to ensure that you have completed the basic configration of your CUBE router. This includes:</p>
<p>
<ul>
	<li>Configuring all appropriate interfaces with IP addresses</li>
	<li>Configuring IP routing so the device is able to reach internal servers, and the Webex cloud</li>
	<li>Configuring a correct timezone and reachable NTP server</li>
	<li>Configuring DNS servers that can resolve any internal servers, and external names</li>
</ul>
</p>
<h3>Set up Password Encryption</h3>
<p>The credentials for your SIP trunk to Webex Calling are stored in the CUBE configuration. These are stored as "Type 6" passwords with an AES cipher. This requires a master key to be set. Paste the command below into your CUBE, changing the password to something of your own choice:</p>
<textarea rows="4" cols="80" id ="passwordconfig">configure terminal
key config-key password-encrypt Password123
password encryption aes
end</textarea>
<button onclick="myFunction('passwordconfig')" class="button">Copy</button>
<br>
<br>
<h3>Import Certificate Authorities</h3>
<p>All communications between your Local Gateway and the Webex Calling cloud are encrypted using TLS 1.2. To accomplish this your CUBE must trust the certificates used by the Webex Calling servers.</p>
<p>You can check if the certificates are installed by running the following command:</p>
<textarea rows="2" cols="80" id="checkcerts">show crypto pki trustpool | include DigiCert
show crypto pki trustpool | include IdenTrust Commercial</textarea>
<button onclick="myFunction('checkcerts')" class="button">Copy</button>
<br>
<br>
<p>If there is no output, use the following commands to import the certificate bundle directly from Cisco:</p>
<textarea rows="3" cols="80" id="caimport">configure terminal
crypto pki trustpool import clean url http://www.cisco.com/security/pki/trs/ios_union.p7b
end</textarea>
<button onclick="myFunction('caimport')" class="button">Copy</button>
<br>
<br>
<h3>Build CUCM Trunk</h3>
<p>This tool assumes that the local gateway is being deployed between on on-premise CUCM cluster and the Webex Calling Cloud to facilitate a migration or hybrid deployment. Before the CUBE is configured and registered, the necessary trunk configuration must be completed in CUCM.</p>
<p>Now that you have completed all the prerequisite tasks, click the "Sign In in with Webex" button below to continue.</p>
<p><button onclick="window.location.href='<?php echo ($oauth_url); ?>'" class="button">Sign In with Webex</button></p>