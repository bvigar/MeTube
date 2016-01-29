<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
		<title>
			MeTube - A Community Multimedia Sharing Database
		</title>
		<link rel="stylesheet" type="text/css" href="../styles/reset.css" media="screen">
		<link rel="stylesheet" type="text/css" href="../styles/common.css" media="screen">
		<link rel="stylesheet" type="text/css" href="../styles/index.css" media="screen">
		<link rel="stylesheet" type="text/css" href="../styles/view.css" media="screen">

		<script type="text/javascript" src="scripts/jquery.js"></script>
		<script>
			$(document).ready(function() {
				$('td.edit').click(function() {
					$('.ajax').html($('.ajax input').val());
					$('.ajax').removeClass('ajax');

					$(this).addClass('ajax');
					$(this).html('<input id="editbox" size="' + $(this).text().length + '" value="' + $(this).text() + '" type="text">');

					$('#editbox').focus();
				});

				$('td.edit').keydown(function(event) {
					vals = $(this).attr('class').split( " " );

					if (event.which == 13) {
						$.ajax(
								{
									type: "POST",
									url: "account.php",
									data: "value=" + $('.ajax input').val() + "&rownum=" + vals[2] + "&field=" + vals[1],
									success: function(data) {
													$('.ajax').html($('.ajax input').val());
													$('.ajax').removeClass('ajax');
												}
						});
					}
				});

				$('#editbox').live('blue', function() {
														$('.ajax').html($('.ajax input').val());
														$('.ajax').removeClass('ajax');
				});
	</script>
	</head>
	<body>
