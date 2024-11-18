students = document.querySelectorAll("#student");
votes = document.querySelectorAll("#vote");
submit = document.getElementById("submit");

students.forEach(student => {
	student.addEventListener("click", (event) => {
		if (student.hasAttribute("data-selected")) {
			unSelected(student);
		} else {
			students.forEach(student => {
				unSelected(student);
			})
			event.target.setAttribute("data-selected", true);
			event.target.style.border = "2px solid var(--box-border-color-selected)";
		}
	})
})

function unSelected(element) {
	element.removeAttribute("data-selected");
	element.style.border = "2px solid var(--box-border-color)";
}

if (isElementExist(submit)) {
	submit.addEventListener("click", () => {
		let selected = false
		students.forEach(student => {
			if (student.hasAttribute("data-selected")) {
				selected = true;
				uidCandidat = student.getAttribute("data-uid");
			}
		})

		let message = "Etes-vous sur de votre choix ?";
		if (selected == false) {
			message = "Etes-vous sur de vouloir voter blanc ?";
			uidCandidat = null;
		}

		if (confirm(message)) {
			callApi({
				"ask": "vote",
				"uid": uid,
				"candidat": uidCandidat
			})

			confetti({
				particleCount: 50,
				origin: { y: 0 },
				spread: 1000,
				scalar: 3,
				shapes: ["emoji"],
				shapeOptions: {
					emoji: {
						value: ["🎉", "🎊", "✨"],
					},
				},
			});

			submit.remove();
			students.forEach(student => {
				unSelected(student);
			})
		}
	})
}

async function isVote() {
	let isVote = await callApi({
		"ask": "vote_exist",
		"uid": uid
	})
	return isVote.answer;
}

async function displayResult() {
	let url = new URLSearchParams(window.location.search);
	let uidGroup = url.get("group")

	let result = await callApi({
		"ask": "group_result",
		"uid": uidGroup,
		"round": round
	})

	let length = await callApi({
		"ask": "group_number",
		"uid": uidGroup
	})

	votes.forEach(vote => {
		vote.style.display = "none";
	});

	result.forEach(value => {
		votes.forEach(vote => {
			if (vote.getAttribute("data-uid") === value.candidat) {
				vote.style.display = "block";
				vote.textContent = ((value.number / length) *100).toFixed(2) + "%";
			}
		});
	});
}

if (round === "3") {
	round = 2;
	displayResult();
}

if (typeof uid != 'undefined') {
	if (isVote()) {
		displayResult()
		setInterval(() => {
			displayResult();
		}, 3000);
	}
}
