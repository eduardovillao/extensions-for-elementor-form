window.addEventListener('load', () => {
	console.log('loaded');
	const selectOptions = document.querySelectorAll(
		'.custom-select-options > li'
	);
	selectOptions.forEach((option) => {
		option.addEventListener('click', () => {
			const container = option.closest(
				'.elementor-field-type-searchable-select.elementor-field-group'
			);
			const input = container.querySelector('input.searchable-select');
			input.value = option.dataset.value;
		});
	});

	const searchInput = document.querySelectorAll(
		'.elementor-field.elementor-field-textual.searchable-select'
	);
	searchInput.forEach((input) => {
		input.addEventListener('input', () => {
			const relatedOptions = input
				.closest(
					'.elementor-field-type-searchable-select.elementor-field-group'
				)
				.querySelectorAll('.custom-select-options > li');
			const searchTerm = input.value.toLowerCase();
			relatedOptions.forEach((option) => {
				const text = option.textContent.toLowerCase();

				if (text.includes(searchTerm)) {
					option.style.display = 'block';
				} else {
					option.style.display = 'none';
				}
			});
		});
	});

	const searchableSelect = document.querySelectorAll(
		'.elementor-field.elementor-field-textual.searchable-select'
	);
	searchableSelect.forEach((input) => {
		input.addEventListener('click', (e) => {
			const optionsContainer = input.nextElementSibling;
			optionsContainer.classList.toggle(
				'searchable-select-container--expanded'
			);
		});
	});
});
