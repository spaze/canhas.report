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
