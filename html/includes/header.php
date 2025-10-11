				<div id="header">
					<!-- Logo -->
					<h1><a href="/"><img height="75px" src="/images/icononly_transparent_nobuffer.png" alt="<?php echo($sitetitle); ?>"></a>&nbsp;&nbsp;&nbsp;<a href="/" id="logo"><?php echo($sitetitle); ?></a></h1>
					<!-- Nav -->
					<nav id="nav">
						<ul>
							<li<?php if ($sitesec == "home"){ echo(" class=\"current\""); } ?>><a href="/">Home</a></li>
							<li<?php if ($sitesec == "tools"){ echo(" class=\"current\""); } ?>><a href="#">Tools</a>
								<ul>
									<li><a href="/lgw">LGW Generator</a></li>
								</ul>
							</li>
						</ul>
					</nav>
				</div>