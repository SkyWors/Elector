createElection = document.getElementById("createElection");
stateElection = document.getElementById("stateElection");
groupName = document.getElementById("group_name");
fileLabel = document.getElementById("file_label");
file = document.getElementById("file");
form = document.getElementById("form");
add = document.getElementById("add_group_container");
refresh = document.getElementById("refresh");

const url = new URLSearchParams(window.location.search);
const uid = url.get("group")

if (isElementExist(createElection)) {
	createElection.addEventListener("click", () => {
		callApi("/api/election", "put", {
			"uid": uid
		});

		window.location.reload();
	})
}

if (isElementExist(stateElection)) {
	stateElection.addEventListener("click", (event) => {
		callApi("/api/election/state", "put", {
			"uid": uid,
			"state": event.target.value
		});

		window.location.reload();
	})
}

if (isElementExist(groupName)) {
	groupName.addEventListener("input", () => {
		if (groupName.value != "") {
			fileLabel.style.display = "block";
		} else {
			fileLabel.style.display = "none";
		}
	})
}

if (isElementExist(fileLabel)) {
	fileLabel.addEventListener("click", () => {
		add.style.display = "none";
		refresh.style.display = "flex";
	})
}

if (isElementExist(refresh)) {
	refresh.addEventListener("click", () => {
		window.location.reload();
	})
}

if (isElementExist(file)) {
	file.addEventListener("change", () => {
		form.submit();
	})
}

const addGroupButton = document.getElementById("add");
if (isElementExist(addGroupButton)) {
	addGroupButton.addEventListener("click", () => {
		add.style.display = "flex";
	})
}

document.addEventListener("keydown", function (event) {
	if (event.key === "Escape") {
		add.style.display = "none";
	}
})

