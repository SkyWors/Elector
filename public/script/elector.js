searchField = document.getElementById("searchField");
searchButton = document.getElementById("searchButton");

navbar = document.getElementById("navbar");

if (isElementExist(searchField)) {
	searchField.addEventListener("keydown", (event) => {
		if (event.target.value !== "") {
			if (event.key === "Enter") {
				searchGroup(event.target.value);
			}
		}
	})
}

if (isElementExist(searchButton)) {
	searchButton.addEventListener("click", () => {
		if (searchField.value !== "") {
			searchGroup(searchField.value);
		}
	})
}

async function searchGroup(uid) {
	let groupExist = await callApi({
		"ask": "group_exist",
		"uid": uid
	});

	if (groupExist.answer == true) {
		url = new URL(window.location.origin);
		params = url.searchParams;

		params.set("group", uid);

		window.location.href = url;
	} else {
		createNotification("Group not found.");
	}
}

setInterval(() => {
	let notification = document.getElementById("notification");
	if (isElementExist(notification)) {
		setTimeout(
			() => {
				notification.remove();
			}, 5000
		)
	}
}, 1000);

function isElementExist(element) {
	return (typeof(element) != "undefined" && element != null) ? true : false;
}

async function callApi(settings) {
	return await fetch("/connect", {
		method: 'post',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(settings)
	}).then((response) => {
		return response.json();
	}).catch((error) => {
		console.log(error);
	})
}

function createNotification(message) {
	let notification = document.createElement("div");
	notification.classList.add("box", "notification", "error");
	notification.id = "notification";

	let messageContainer = document.createElement("p");
	messageContainer.textContent = message;

	notification.appendChild(messageContainer);
	navbar.parentNode.insertBefore(notification, navbar.nextSibling);
}
