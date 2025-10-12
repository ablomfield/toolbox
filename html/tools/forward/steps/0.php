	<h2>Forwarding Remover</h2>
	<p>This tool is used to remove call forwarding for a list of users.</p>
	<p>Extension numbers are always active in Webex Calling, so when staging users in Control Hub a forwarding destination was set (steering code + extension) to route it back to CUCM so that existing Webex Calling users are able to reach the new users until cutover.
	<p>During cutover the forwarding on the extension must be removed to make the user reachable on Webex Calling</p>
	<p>You will need to provide a list of up to 500 email addresses (one address per row).</p>
	<p>
	<form method="post"><input type="hidden" name="toolstep" value="1"><input type="submit" value="Continue"></form>
	</p>