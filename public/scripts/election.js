students = document.querySelectorAll("#student");
votes = document.querySelectorAll("#vote");
submit = document.getElementById("submit");

const url = new URLSearchParams(window.location.search);
const groupUid = url.get("group")

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

/**
 * Remove selected tag from element
 *
 * @param {*} element
 *
 * @returns
 */
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
			callApi("/api/user/vote", "put", {
				"uid": uid,
				"groupUid": groupUid,
				"candidatUid": uidCandidat
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

/**
 * Get user vote status
 *
 * @returns
 */
async function isVote() {
	let isVote = await callApi("/api/user/vote", "post", {
		"uid": uid
	})
	return isVote.answer;
}

/**
 * Display result
 *
 * @returns
 */
async function displayResult() {
	let url = new URLSearchParams(window.location.search);
	let uidGroup = url.get("group")

	let result = await callApi("/api/election/state", "post", {
		"uid": uidGroup,
		"round": round
	})

	let length = await callApi("/api/group", "post", {
		"uid": uidGroup
	});

	votes.forEach(vote => {
		vote.style.display = "none";
	});

	result.forEach(value => {
		votes.forEach(vote => {
			if (vote.getAttribute("data-uid") === value.candidat) {
				vote.style.display = "block";
				vote.textContent = ((value.number / length.length) *100).toFixed(2) + "%";
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
