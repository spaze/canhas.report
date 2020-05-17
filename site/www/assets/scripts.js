try {
	new ReportingObserver(() => {});
} catch (e) {
	if (e instanceof ReferenceError) {
		document.addEventListener('DOMContentLoaded', function () {
			const list = document.getElementsByClassName('reporting-api not-supported');
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
	const changeSubdomain = document.getElementById('subdomain');
	if (changeSubdomain) {
		changeSubdomain.onclick = function () {
			const subdomain = prompt('Set your reporting subdomain, empty for a new random one', this.dataset.subdomain);
			const cookie = encodeURIComponent(this.dataset.cookie);
			if (subdomain === '') {
				document.cookie = cookie + '=';
			} else if (subdomain !== null) {
				document.cookie = cookie + '=' + encodeURIComponent(subdomain);
			}
			if (subdomain !== null) {
				location.reload();
			}
		};
	}
});
