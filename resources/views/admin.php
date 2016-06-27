<?php
/*
 * @file testing.php
 * @author Stefan Jonker (programmer at stefanjonker dot nl)
 * @date 15-05-2016
 *
 * Created: Sun 15-05-2016, 22:58:34 (:-0500)
 * Last modified: Sun 26-06-2016, 22:44:21 (-0500)
 */
?>
<!doctype html>
<html>
<head lang="en">
	<meta charset="utf-8" />
	<title></title>

	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
	<!--[if lte IE 8]>
		 <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-old-ie-min.css">
	<![endif]-->
	<!--[if gt IE 8]><!-->
		 <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css">
	<!--<![endif]-->

	<style>
	.pure-button {
		border-radius: 5px;
	}

	.pure-button-delete {
		background: rgb(202, 60, 60); /* this is a maroon */
		color: #fff;
	}

	.pure-button-new {
      background: rgb(28, 184, 65); /* this is a green */
		color: #fff;
	}

	.pure-button-fullwidth {
		width: 100% !important;	
	}

	[contenteditable="true"] {
		border: 1px dotted black;
		display: block;
		padding: .5em 1em;
	}

	.input {
		margin-left: 1em;
	}

	.button-delete {
		float: right;
	}

	.pure-button {
		margin-left: 1em;
	}

	.tweets__wrapper {
		width: 800px;
		margin-left: auto;
		margin-right: auto;
	}

	.pure-g {
		margin-bottom: 1em;
	}
	</style>
</head>

<body>

<div class="tweets__wrapper">
	<h1>Tweets admin</h1>

	<div class="pure-g">
		<div class="pure-u-lg-1-3">
			<span class="input" data-tweet="new" contenteditable="true"></span>
		</div>
		<div class="pure-u-lg-1-3">
			<span class="input" data-time="new" contenteditable="true"></span>
		</div>
		<div class="pure-u-lg-1-3"><a class="pure-button pure-button-new button-new" data-update-button="new" href="#">New</a></div>
	</div>
	
	<div class="newrows"> </div>
	<div class="addrows"> </div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

<script>
function getTweets () {
	//Get all Tweets
	$.ajax({ 
		type: "GET",
			dataType: "JSON",
			url: "http://localhost:8000/tweet/",
			success: function(data) {
				console.log(data);

				$.each(data.reverse(), function(a, b) {
	
					$(".addrows")
						.append('<div class="pure-g" data-hide-id="' + b.id + '">'
						+ '<div data-relative-id="' 
						+ a 
						+ '" hidden>' 
						+ b.id 
						+ '</div>'
	
						+ '<div class="pure-u-lg-1-3">' 
						+ '<span data-tweet="' + a + '" class="input" contenteditable="true">'
						+ b.tweet 
						+ '</span>'
						+ '</div>'
	
						+ '<div class="pure-u-lg-1-3">'
						+ '<span data-time="' + a + '" class="input" contenteditable="true">' 
						+ b.time 
						+ '</span>'
						+ '</div>'
	
						+ '<div class="pure-u-lg-1-3">'
							+ '<a class="pure-button pure-button-warning button-delete pure-button-delete" data-delete-button="' 
							+	a 
							+ '" href="#">Delete</a>'
	
							+ '<a class="pure-button button-update" data-update-button="' 
							+	a 
							+ '" href="#">Update</a>'
						+ '</div>'
					);
				});
	
				init();
			}
	});
}

function init() {
	console.log('init');

	$(".button-new").on("click", function () {
		var tweet = $("[data-tweet=new]").html();
		var time = $("[data-time=new]").html();

		newTweet(tweet, time);
	});

	$(".button-update").on("click", function () {
		var relativeId = $(this).attr("data-update-button");
		var realId = $("[data-relative-id=" + relativeId + "]").html();
		var tweet = $("[data-tweet=" + relativeId + "]").html();
		var time = $("[data-time=" + relativeId + "]").html();

		console.log(relativeId);

		updateTweet(realId, relativeId, tweet, time);
	});

	$(".button-delete").on("click", function () {
		var relativeId = $(this).attr("data-delete-button");
		var realId = $("[data-relative-id=" + relativeId + "]").html();

		deleteTweet(realId);
	});
}

//Create a tweet
function newTweet(tweet, time) {
	var tweet = tweet.replace(/<(?:.|\n)*?>/gm, '');;
	var time = time.replace(/<(?:.|\n)*?>/gm, '');;

	$.ajax({ 
	   type: "POST",
		url: "http://localhost:8000/tweet/?tweet=" + tweet + "&time=" + time,
	   success: function(data) {
		   var relativeId = $(".addrows > .pure-g").length + 1;
	
			$(".newrows")
				.append('<div class="pure-g">'
				+ '<div data-relative-id="' 
				+ relativeId 
				+ '" hidden>' 
				+ data.id 
				+ '</div>'
	
				+ '<div class="pure-u-lg-1-3">' 
				+ '<span data-tweet="' + relativeId + '" class="input" contenteditable="true">'
				+ data.tweet 
				+ '</span>'
				+ '</div>'
	
				+ '<div class="pure-u-lg-1-3">'
				+ '<span data-time="' + relativeId + '" class="input" contenteditable="true">' 
				+ data.time 
				+ '</span>'
				+ '</div>'
	
				+ '<div class="pure-u-lg-1-3">'
					+ '<a class="pure-button pure-button-warning button-delete pure-button-delete" data-update-button="' 
					+	relativeId 
					+ '" href="#">Delete</a>'
	
					+ '<a class="pure-button button-update" data-update-button="' 
					+	relativeId 
					+ '" href="#">Update</a>'
				+ '</div>'
			).hide().fadeIn("slow");

			$("[data-update-button=" + relativeId + "]").on("click", function () {
				var relativeId = $(this).attr("data-update-button");
				var realId = $("[data-relative-id=" + relativeId + "]").html();
				var tweet = $("[data-tweet=" + relativeId + "]").html();
				var time = $("[data-time=" + relativeId + "]").html();

				updateTweet(realId, relativeId, tweet, time);
			});

			$(".button-delete").on("click", function () {
				var relativeId = $(this).attr("data-update-button");
				var realId = $("[data-relative-id=" + relativeId + "]").html();

				deleteTweet(realId);
			});
	   }

	});
}

//Modify a Tweet
function updateTweet(realId, relativeId, tweet, time) {
	var id = realId.replace(/<(?:.|\n)*?>/gm, '');;
	var relativeId = relativeId.replace(/<(?:.|\n)*?>/gm, '');;
	var tweet = tweet.replace(/<(?:.|\n)*?>/gm, '');;
	var time = time.replace(/<(?:.|\n)*?>/gm, '');;

	$.ajax({ 
		type: "PUT",
			url: "http://localhost:8000/tweet/" + id + "?tweet=" + tweet + "&time=" + time,
			success: function(data) {

				$("[data-update-button=" + relativeId + "]").css('border', '1px solid rgb(28, 184, 65)');

				window.setTimeout(function () {
					$("[data-update-button=" + relativeId + "]").css('border', '0');
				}, 500);
			}
	});
}

//Delete a Tweet
function deleteTweet(realId) {
	var id = realId.replace(/<(?:.|\n)*?>/gm, '');;

	$.ajax({ 
		type: "DELETE",
			url: "http://localhost:8000/tweet/" + id,
			success: function(data) {
				console.log(data);

				$("[data-hide-id=" + id + "]").fadeOut("slow");
			}
	});
}

getTweets();
</script>
</body>
</html>
