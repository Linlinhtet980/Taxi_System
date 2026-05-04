/**
 * Driver Search & Filter Implementation
 */

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search');
    if (searchInput) {
        searchInput.placeholder = "Search driver name, phone, or license...";
    }
    const statusFilter = document.getElementById('statusFilter');

    const resetButton = document.getElementById('resetFilters');
    const tableRows = document.querySelectorAll('.premium-table tbody .table-row');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value.toLowerCase();

        tableRows.forEach(row => {
            const driverInfo = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const vehicleInfo = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const licenseInfo = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const statusBadge = row.querySelector('.status-badge');
            const currentStatus = statusBadge ? statusBadge.textContent.trim().toLowerCase() : '';

            const matchesSearch = driverInfo.includes(searchTerm) || 
                                vehicleInfo.includes(searchTerm) || 
                                licenseInfo.includes(searchTerm);
            
            const matchesStatus = (selectedStatus === 'all') || (currentStatus === selectedStatus);

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });

        const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
        const emptyMessage = document.getElementById('noDriversMessage');
        const paginationWrapper = document.querySelector('.small-pagination');
        const paginationNav = paginationWrapper ? paginationWrapper.querySelector('nav > div:last-child') : null;
        const resultText = paginationWrapper ? paginationWrapper.querySelector('p') : null;

        if (searchTerm !== '' || selectedStatus !== 'all') {
            if (paginationNav) paginationNav.style.visibility = 'hidden';
            if (resultText) resultText.textContent = `Found ${visibleRows.length} results matching your search`;
        } else {
            if (paginationNav) paginationNav.style.visibility = 'visible';
            // Laravel handles the default text
        }

        if (visibleRows.length === 0) {
            if (!emptyMessage) {
                const tbody = document.querySelector('.premium-table tbody');
                const tr = document.createElement('tr');
                tr.id = 'noDriversMessage';
                tr.innerHTML = `<td colspan="6" style="padding: 4rem; text-align: center; color: var(--text-dim);">No drivers found matching your search.</td>`;
                tbody.appendChild(tr);
            }
        } else {
            if (emptyMessage) emptyMessage.remove();
        }
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    resetButton.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = 'all';
        filterTable();
    });
});
