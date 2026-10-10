(() => {
	'use strict';

	const initialiseRegistryFilters = () => {
		const params = new URLSearchParams(window.location.search);
		if ('ai-transparency' !== params.get('page')) {
			return;
		}

		const tableWrap = document.querySelector('.ai-transparency-admin .ai-transparency-table-wrap');
		const table = tableWrap ? tableWrap.querySelector('table.widefat') : null;
		if (!table || table.dataset.katFiltersReady) {
			return;
		}

		const headers = Array.from(table.querySelectorAll('thead th')).map((cell) => cell.textContent.trim());
		const rows = Array.from(table.querySelectorAll('tbody tr'));
		if (headers.length < 4 || !rows.length) {
			return;
		}

		const filters = document.createElement('div');
		filters.className = 'kat-registry-filters';
		filters.setAttribute('role', 'search');

		const searchField = createSearchField(headers[0]);
		const typeField = createSelectField(headers[1], uniqueColumnValues(rows, 1));
		const reviewField = createSelectField(headers[2], uniqueColumnValues(rows, 2));
		const statusField = createSelectField(headers[3], uniqueColumnValues(rows, 3));

		filters.append(searchField.wrapper, typeField.wrapper, reviewField.wrapper, statusField.wrapper);
		tableWrap.parentNode.insertBefore(filters, tableWrap);

		const applyFilters = () => {
			const query = normalise(searchField.control.value);
			const type = normalise(typeField.control.value);
			const review = normalise(reviewField.control.value);
			const status = normalise(statusField.control.value);

			rows.forEach((row) => {
				const cells = Array.from(row.cells);
				const searchableText = normalise(row.textContent);
				const matchesSearch = !query || searchableText.includes(query);
				const matchesType = !type || normalise(cells[1] ? cells[1].textContent : '') === type;
				const matchesReview = !review || normalise(cells[2] ? cells[2].textContent : '') === review;
				const matchesStatus = !status || normalise(cells[3] ? cells[3].textContent : '') === status;

				row.hidden = !(matchesSearch && matchesType && matchesReview && matchesStatus);
			});
		};

		[searchField.control, typeField.control, reviewField.control, statusField.control].forEach((control) => {
			control.addEventListener('input', applyFilters);
			control.addEventListener('change', applyFilters);
		});

		searchField.control.addEventListener('keydown', (event) => {
			if ('Escape' !== event.key || !searchField.control.value) {
				return;
			}
			searchField.control.value = '';
			applyFilters();
		});

		table.dataset.katFiltersReady = 'true';
	};

	const createSearchField = (labelText) => {
		const wrapper = createFieldWrapper(labelText);
		const control = document.createElement('input');
		control.type = 'search';
		control.className = 'regular-text kat-registry-filter-control';
		control.placeholder = labelText;
		wrapper.label.htmlFor = wrapper.id;
		control.id = wrapper.id;
		wrapper.wrapper.appendChild(control);
		return { wrapper: wrapper.wrapper, control };
	};

	const createSelectField = (labelText, values) => {
		const wrapper = createFieldWrapper(labelText);
		const control = document.createElement('select');
		control.className = 'kat-registry-filter-control';
		control.id = wrapper.id;
		wrapper.label.htmlFor = wrapper.id;

		const emptyOption = document.createElement('option');
		emptyOption.value = '';
		emptyOption.textContent = labelText;
		control.appendChild(emptyOption);

		values.forEach((value) => {
			const option = document.createElement('option');
			option.value = value;
			option.textContent = value;
			control.appendChild(option);
		});

		wrapper.wrapper.appendChild(control);
		return { wrapper: wrapper.wrapper, control };
	};

	const createFieldWrapper = (labelText) => {
		const wrapper = document.createElement('div');
		wrapper.className = 'kat-registry-filter-field';
		const label = document.createElement('label');
		label.textContent = labelText;
		const id = `kat-filter-${labelText.toLowerCase().replace(/[^a-z0-9]+/g, '-')}-${Math.random().toString(36).slice(2, 7)}`;
		wrapper.appendChild(label);
		return { wrapper, label, id };
	};

	const uniqueColumnValues = (rows, index) => Array.from(
		new Set(
			rows
				.map((row) => row.cells[index] ? row.cells[index].textContent.trim() : '')
				.filter(Boolean)
		)
	).sort((left, right) => left.localeCompare(right));

	const normalise = (value) => String(value || '').trim().toLocaleLowerCase();

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', initialiseRegistryFilters);
	} else {
		initialiseRegistryFilters();
	}
})();
