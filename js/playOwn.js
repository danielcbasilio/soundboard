// A $( document ).ready() block.
$(document).ready(function () {
	console.log("ready!");
	$("body>div>button").click(function () {
		let track = new Audio("/sounds/" + encodeURIComponent($(this).text()) + ".mp3");
		track.play();
	});
});
