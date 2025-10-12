		<section class="wrapper style1">
			<div class="container">
				<div class="row gtr-200"><?php
											$rsdata = mysqli_query($dbconn, "SELECT name, icon, path FROM tools WHERE isactive = 1 ORDER BY name") or die("Error in Selecting " . mysqli_error($dbconn));
											if ($rsdata) {
												if (mysqli_num_rows($rsdata) > 0) {
													while ($row = mysqli_fetch_assoc($rsdata)) {
														echo ("					<section class=\"col-4 col-12-narrower\">\n");
														echo ("						<div class=\"box highlight\">\n");
														echo ("							<i class=\"icon major solid " . $row["icon"] . "\" style=\"text-decoration: none;\"></i>\n");
														echo ("							<h3><a href=\"" . $row["path"] . "\">" . $row["name"] . "</a></h3>\n");
														echo ("						</div>\n");
														echo ("					</section>\n");
													}
												}
											}
											?>				</div>
			</div>
		</section>