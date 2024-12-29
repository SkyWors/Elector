/**
 * Check if element already in DOM
 *
 * @param {*} element
 *
 * @return {boolean}
 */
function isElementExist(element) {
	return (typeof(element) != "undefined" && element != null) ? true : false;
}

/**
 * API POST request
 *
 * @param {string} path
 * @param {*} settings
 *
 * @returns
 */
async function callApi(path = "/api", type = "get", settings = null) {
	return await fetch(path, {
		method: type,
		headers: {
			"Accept": "application/json",
			"Content-Type": "application/json"
		},
		...(settings !== null && {
				body: JSON.stringify(settings)
			})
	}).then((response) => {
		return response.json();
	}).catch((error) => {
		console.log(error);
	})
}

/**
 * Return cookie value
 *
 * @param {string} name
 *
 * @return {string}
 */
function getCookie(name) {
	let cookieValue = document.cookie
		.split("; ")
		.find(
			row => row.startsWith(name + "=")
		)
		?.split("=")[1];

	return cookieValue;
}

/**
 * Lang function
 *
 * @param {string} key
 * @param {array} options
 *
 * @return {string}
 */
async function translate(key, options = null) {
	let data = await callApi("/langs/" + getCookie("LANG") + ".json");

	let result = data[key] || "Missing entry";

	if (options) {
		for (const [index, option] of Object.entries(options)) {
			result = result.replace(`$[${index}]`, option);
		}
	}

	return result;
}

/**
 * Display notification
 *
 * @param {string} message
 */
function setNotification(message) {
	let div = document.createElement("div");
	let p = document.createElement("p");

	div.className = "main_notification";
	div.id = "main_notification";
	p.textContent = message;
	div.append(p);

	document.body.appendChild(div);

	setCookie("NOTIFICATION", 60*60);
}

/**
 * Set cookie
 *
 * @param {string} name
 * @param {string} value
 * @param {number} time
 *
 * @returns
 */
function setCookie(name, time, value = "") {
	let date = new Date();
	date.setTime(date.getTime() + time);
	let expires = "expires=" + date.toUTCString();
	document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

// Events

// Lang selection
langSelection = document.getElementById("lang_selection");
if (isElementExist(langSelection)) {
	langSelection.addEventListener("change", () => {
		setCookie("LANG", 60*60*24*30, langSelection.value);
		window.location.reload();
	})
}

// Notifications
let notification = decodeURIComponent(getCookie("NOTIFICATION"));
if (notification) {
	if (notification !== "undefined") {
		setNotification(notification);
	}
}

setInterval(() => {
	let DOMnotification = document.getElementById("main_notification");
	if (isElementExist(DOMnotification)) {
		setTimeout(
			() => {
				DOMnotification.remove();
			}, 5000
		)
	}
}, 1000);

searchField = document.getElementById("searchField");
searchButton = document.getElementById("searchButton");

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

/**
 * Search group
 *
 * @param {string} uid
 *
 * @returns
 */
async function searchGroup(uid) {
	let groupExist = await callApi("api/group", "post", {
		"uid": uid
	});

	if (groupExist.message == 200) {
		url = new URL(window.location.origin);
		params = url.searchParams;

		params.set("group", uid);

		window.location.href = url;
	} else {
		setNotification("Group not found.");
	}
}

const passwordFields = document.querySelectorAll(".password i");

passwordFields.forEach(passwordField => {
	passwordField.addEventListener("click", function() {
		const passwordField = this.previousElementSibling;
		if (passwordField.type === "password") {
			passwordField.type = "text";
			this.className = "ri-eye-line";
		} else {
			passwordField.type = "password";
			this.className = "ri-eye-off-line";
		}
	})
})
