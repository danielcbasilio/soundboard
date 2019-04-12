// A $( document ).ready() block.
$(document).ready(function () {
	console.log("ready!");

	$("body>div>form").submit(function (e) {
		e.preventDefault();
		console.log($("#url").val());
		$.ajax({
				type: "POST",
				url: "add-to-fifo.php",
				data: {
					vid_id: encodeURIComponent($("#url").val())
				},
				beforeSend: function (xhr) {
					xhr.overrideMimeType("text/plain; charset=x-user-defined");
				}
			});
	});
});
