/**
 * Booking Search & Filter Implementation
 * Handled via Vanilla JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search');
    if (searchInput) {
        searchInput.placeholder = "Search Trip ID, Passenger, Driver or Plate...";
    }
    const statusFilter = document.getElementById('statusFilter');

    const resetButton = document.getElementById('resetFilters');
    const tableRows = document.querySelectorAll('.premium-table tbody .table-row');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value.toLowerCase();

        tableRows.forEach(row => {
            // Get data from cells
            const tripId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const passengerInfo = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const vehicleDriverInfo = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const routeInfo = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const statusBadge = row.querySelector('.status-badge');
            const currentStatus = statusBadge ? statusBadge.textContent.trim().toLowerCase() : '';

            // Matching logic
            const matchesSearch = tripId.includes(searchTerm) || 
                                passengerInfo.includes(searchTerm) || 
                                vehicleDriverInfo.includes(searchTerm) ||
                                routeInfo.includes(searchTerm);

            
            const matchesStatus = (selectedStatus === 'all') || (currentStatus === selectedStatus);

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                row.classList.add('animate-fade'); // Re-trigger animation if possible
            } else {
                row.style.display = 'none';
            }
        });

        // Check if all rows are hidden (show "No results" if needed)
        const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
        const emptyMessage = document.getElementById('noBookingsMessage');
        const paginationWrapper = document.querySelector('.small-pagination');
        const paginationNav = paginationWrapper ? paginationWrapper.querySelector('nav > div:last-child') : null;
        const resultText = paginationWrapper ? paginationWrapper.querySelector('p') : null;
        
        // Handle pagination visibility and text
        if (searchTerm !== '' || selectedStatus !== 'all') {
            if (paginationNav) paginationNav.style.visibility = 'hidden';
            if (resultText) resultText.textContent = `Found ${visibleRows.length} results matching your search`;
        } else {
            if (paginationNav) paginationNav.style.visibility = 'visible';
        }

        if (visibleRows.length === 0) {
            if (!emptyMessage) {
                const tbody = document.querySelector('.premium-table tbody');
                const tr = document.createElement('tr');
                tr.id = 'noResultsMessage';
                tr.innerHTML = `<td colspan="7" style="padding: 4rem; text-align: center; color: var(--text-dim);">No bookings match your search filters.</td>`;
                tbody.appendChild(tr);
            }
        } else {
            if (emptyMessage) emptyMessage.remove();
        }
    }

    // Event Listeners
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    resetButton.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = 'all';
        filterTable();
    });
});
