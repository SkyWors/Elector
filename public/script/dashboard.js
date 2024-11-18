createElection = document.getElementById("createElection");
stateElection = document.getElementById("stateElection");
groupName = document.getElementById("group_name");
fileLabel = document.getElementById("file_label");
file = document.getElementById("file");
form = document.getElementById("form");
add = document.getElementById("add_group_container");

const url = new URLSearchParams(window.location.search);
const uid = url.get("group")

if (isElementExist(createElection)) {
	createElection.addEventListener("click", () => {
		callApi({
			"ask": "create_election",
			"uid": uid
		});

		window.location.reload();
	})
}

if (isElementExist(stateElection)) {
	stateElection.addEventListener("click", (event) => {
		callApi({
			"ask": "state_election",
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

if (isElementExist(file)) {
	file.addEventListener("change", () => {
		form.submit();
	})
}

document.getElementById("add").addEventListener("click", () => {
	add.style.display = "flex";
})

document.addEventListener("keydown", function (event) {
	if (event.key === "Escape") {
		add.style.display = "none";
	}
})
