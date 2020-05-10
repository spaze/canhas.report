try {
	new ReportingObserver(() => {});
} catch (e) {
	if (e instanceof ReferenceError) {
		document.addEventListener('DOMContentLoaded', function () {
			const list = document.getElementsByClassName('not-supported');
			for (let element of list) {
				element.classList.remove('hidden');
			}
		});
	}
}
document.addEventListener('DOMContentLoaded', function () {
	const list = document.getElementsByClassName('view-source');
	for (let element of list) {
		element.onclick = function (event) {
			event.preventDefault();
			const source = document.getElementById(this.getAttribute('href').slice(1));
			this.getElementsByClassName('text')[0].textContent = (source.hidden ? element.dataset.textHide : element.dataset.textShow);
			this.getElementsByClassName('arrow')[0].textContent = (source.hidden ? element.dataset.arrowHide : element.dataset.arrowShow);
			source.hidden = !source.hidden;
		}
	}
});
