// A $( document ).ready() block.
$(document).ready(function () {
	console.log("ready!");

	$("body>div>form").submit(function (e) {
		e.preventDefault();
		requestQueueJSON(encodeURIComponent($("#url").val()));
	});

	requestQueueJSON("");
});


function requestQueueJSON(vidId) {
	$.ajax({
		type: "POST",
		url: "add-to-fifo.php",
		data: {
			vid_id: vidId
		},
		beforeSend: function (xhr) {
			xhr.overrideMimeType("text/plain; charset=x-user-defined");
		}
	}).always(function (data) {
		refreshQueue(data);
	});
}

function refreshQueue(data) {
	let obj = JSON.parse(data);
	console.log("Response Code: " + obj.status);


	// Refreshing the queue on the user's screen
	let container = document.getElementById("queue");
	while (container.firstChild) {
		container.removeChild(container.firstChild);
	}
	let ol = document.createElement("ol");
	obj.items.forEach(element => {
		let li = document.createElement("li");
		li.innerText = element.title;
		ol.appendChild(li);
	});
	container.appendChild(ol);
	console.log("Queue Refreshed");
}
