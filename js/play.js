// A $( document ).ready() block.
$(document).ready(function () {
	console.log("ready!");
	console.log("it is actually ready now!")
	$("body>div>button").click(function () {
		$.ajax({
				url: "play.php?file=" + encodeURIComponent($(this).text()) + ".mp3",
				beforeSend: function (xhr) {
					xhr.overrideMimeType("text/plain; charset=x-user-defined");
				}
			})
			.always(function (data) {
//				data = data.responseText;
				console.log(data);
				if (data === "die, you jerk") {
					swal("The specified file no longer exists!", "Please refresh the page!", "error", {
						buttons: false,
						timer: 5000,
					});
				} else if (data === "badIp") {
					swal("Access denied!", "This button is only available from certain networks, and you are not in one of them!", "error", {
						buttons: false,
						timer: 5000,
					});
				} else if (data === "too long, you fool") {
					swal("Too long!", "The selected file is too long and cannot be played!", "error", {
						buttons: false,
						timer: 5000,
					});
				} else if (data === "forbidden") {
					swal("Estou farto desta merda!", "Deixem de ser chatos.", "error", {
						buttons: false,
						timer: 5000,
					});
				} else if (data === "success") {
					swal("Sound Played!", "Give it another try!", "success", {
						buttons: false,
						timer: 1500,
					});
				}
			});
	});
});
