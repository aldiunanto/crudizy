<!DOCTYPE html>
<html>
	<head>
		<title>A Sample Web App Using CRUDIZY.</title>
		<link rel="stylesheet" href="public/css/style.css" />
		<script type="text/javascript" src="public/js/vendor/jquery-1.10.min.js"></script>
		<script type="text/javascript" src="public/js/config.js"></script>
		<script type="text/javascript" src="public/js/routes.js"></script>
		<script type="text/javascript" src="public/js/app.js"></script>
	</head>
	<body data-crudizy-container="#frame">
		<div class="container">
			<table>
				<tr>
					<td class="sidebar">
						<ul>
							<li><a href="#/page1">Page 1</a></li>
							<li><a href="#/page2">Page 2</a></li>
							<li><a href="#/page3">Page 3</a></li>
						</ul>
					</td>
					<td class="content">
						<div id="frame"></div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>