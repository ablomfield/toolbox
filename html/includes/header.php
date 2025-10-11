				<div id="header">
					<!-- Logo -->
					<h1><a href="/"><img height="75px" src="/images/icononly_transparent_nobuffer.png" alt="CollabToolbox"></a>&nbsp;&nbsp;&nbsp;<a href="/" id="logo">CollabToolbox</a></h1>
					<!-- Nav -->
					<nav id="nav">
						<ul>
							<li<?php if ($sitesec == "home"){ echo(" class=\"current\""); } ?>><a href="/">Home</a></li>
							<li<?php if ($sitesec == "tools"){ echo(" class=\"current\""); } ?>><a href="#">Tools</a>
								<ul>
									<li><a href="/lgw">LGW Generator</a></li>
									<li><a href="/ryg">RedYellowGreen</a></li>
<!--									<li><a href="/verify">CollabVerify</a></li> -->
								</ul>
							</li>
<?php							
if ($personid != "") {
	echo("							<li");
	if ($sitesec == "ryg"){
		echo(" class=\"current\"");
	}
	echo("><a href=\"/ryg\">RedYellowGreen</a>\n");
	echo("								<ul>\n");
	echo("									<li><a href=\"/ryg/devices\">Devices</a></li>\n");
	echo("									<li><a href=\"/ryg/logout\">Logout</a></li>\n");
	echo("								</ul>\n");
	echo("							</li>\n");
}
?><!--							<li<?php if ($sitesec == "articles"){ echo(" class=\"current\""); } ?>><a href="/articles">Articles</a></li>
							<li<?php if ($sitesec == "videos"){ echo(" class=\"current\""); } ?>><a href="/videos">Videos</a></li> -->
						</ul>
					</nav>
				</div>