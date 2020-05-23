(function() {
	if (location.search.indexOf('fbclid=') !== -1) {
		try {
			const url = new URL(location);
			url.searchParams.delete('fbclid');
			history.replaceState(null, '', url.href);
		} catch (ex) {
			// Let'em track'em
		}
	}
	const showElements = function (classNames) {
		document.addEventListener('DOMContentLoaded', function () {
			const list = document.getElementsByClassName(classNames);
			for (let element of list) {
				element.classList.remove('hidden');
			}
		});
	}
	try {
		new ReportingObserver(() => {});
	} catch (e) {
		if (e instanceof ReferenceError) {
			showElements('reporting-api not-supported');
		}
	}
	if (!window.trustedTypes) {
		showElements('trusted-types not-supported');
	}
	if (typeof (document.permissionsPolicy ?? document.featurePolicy) === 'undefined') {
		showElements('permissions-policy not-supported');
	}
})();
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
	const toReportUri = document.getElementById('report-uri');
	if (toReportUri) {
		toReportUri.onclick = function () {
			const subdomain = prompt('Enter your Report URI subdomain', this.dataset.subdomain);
			const cookie = encodeURIComponent(this.dataset.cookie);
			const endpoint = encodeURIComponent(this.dataset.endpoint);
			if (subdomain === '') {
				document.cookie = cookie + '=';
				document.cookie = endpoint + '=';
			} else if (subdomain !== null) {
				document.cookie = cookie + '=' + encodeURIComponent(subdomain.trim().replace(/\.report-uri\.com$/, ''));
				document.cookie = endpoint + '=' + encodeURIComponent(this.dataset.endpointReportUri);
			}
			if (subdomain !== null) {
				location.reload();
			}
		};
	}
	const changeEndpoint = document.getElementById('change-endpoint');
	if (changeEndpoint) {
		changeEndpoint.onclick = function () {
			document.cookie = encodeURIComponent(this.dataset.endpoint) + '=';
			location.reload();
		}
	}

});
