document.addEventListener("DOMContentLoaded", function () {
	const form = document.querySelector(".comments");
	const commentParent = document.getElementById("comment-parent");

	form.addEventListener("submit", function (event) {
		event.preventDefault(); // Empêche le rechargement de la page

		// Récupérer les données du formulaire
		const formData = new FormData(form);

		// Envoyer les données au serveur via AJAX
		fetch(form.action, {
			method: "POST",
			body: formData,
		})
			.then((response) => response.json())
			.then((data) => {
				if (data.success === true) {
					// Modification ici
					// Réussite : Ajouter le commentaire à l'affichage
					const name = formData.get("commentaires[name]");
					const content = formData.get("commentaires[content]");
					const date = new Date();
					const commentElement = document.createElement("div");
					commentElement.innerHTML = `<h3 class="text-warning">Pseudo : ${name}</h3><p>Message : ${content}</p> <p>Date : ${date.toLocaleDateString()} ${date.toLocaleTimeString()}</p>`;
					commentParent.appendChild(commentElement);
					form.reset(); // Effacer le formulaire
				} else {
					// Échec : Afficher un message d'erreur
					console.error("Erreur lors de l'ajout du commentaire");
				}
			})
			.catch((error) => {
				console.error("Erreur lors de la requête AJAX :", error);
			});
	});
});
