				<div id="header">
					<!-- Logo -->
					<h1><a href="/"><img height="75px" src="/images/icononly_transparent_nobuffer.png" alt="<?php echo ($sitetitle); ?>"></a>&nbsp;&nbsp;&nbsp;<a href="/" id="logo"><?php echo ($sitetitle); ?></a></h1>
					<!-- Nav -->
					<?php
					if ($loggedin) {
						echo ("					<nav id=\"nav\">\n");
						echo ("					  <ul>\n");
						echo ("					    <li");
						if ($sitesec == "home") {
							echo (" class=\"current\"");
						}
						echo ("><a href=\"/\">Home</a></li>\n");
						echo ("					    <li");
						if ($sitesec == "customers") {
							echo (" class=\"current\"");
						}
						echo ("><a href=\"/customers/\">Customers</a></li>\n");
						echo ("					    <li");
						if ($sitesec == "tools") {
							echo (" class=\"current\"");
						}
						echo ("><a href=\"#\">Tools</a>\n");
						echo ("					      <ul>\n");
						$rsdata = mysqli_query($dbconn, "SELECT name, path FROM tools WHERE isactive = 1 ORDER BY name") or die("Error in Selecting " . mysqli_error($dbconn));
						if ($rsdata) {
							if (mysqli_num_rows($rsdata) > 0) {
								while ($row = mysqli_fetch_assoc($rsdata)) {
									echo ("					        <li><a href=\"" . $row["path"] . "\">" . $row["name"] . "</a></li>\n");
								}
							}
						}

						echo ("					      </ul>\n");
						echo ("					    </li>\n");
						if ($isadmin) {
							echo ("					    <li");
							if ($sitesec == "admin") {
								echo (" class=\"current\"");
							}
							echo ("><a href=\"#\">Admin</a>\n");
							echo ("					      <ul>\n");
							echo ("					        <li><a href=\"/admin/domains/\">Domains</a></li>\n");
							echo ("					        <li><a href=\"/admin/history/\">History</a></li>\n");
							echo ("					        <li><a href=\"/admin/settings/\">Settings</a></li>\n");
							echo ("					        <li><a href=\"/admin/tools/\">Tools</a></li>\n");
							echo ("					        <li><a href=\"/admin/users/\">Users</a></li>\n");
							echo ("					      </ul>\n");
							echo ("					    </li>\n");
						}
						echo ("					    <li");
						if ($sitesec == "preferences") {
							echo (" class=\"current\"");
						}
						echo ("><a href=\"/preferences/\">Preferences</a></li>\n");
						echo ("					    <li><a href=\"/logout/\">Logout</a></li>\n");
						echo ("					  </ul>\n");
						echo ("					</nav>\n");
						if ($orgname != "") {
							echo ("<div class=\"row aln-right\">\n");
							echo ("<h3>Customer: " . $orgname . "<h3>\n");
							echo ("</div>\n");
						}
					}
					?>
				</div>