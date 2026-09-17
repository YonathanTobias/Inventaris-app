/**
 * SPARTA-PW - Pusat Laporan & Rekapitulasi Controller
 */

let currentPrintTarget = 'kir';

function setLaporanTab(tabName) {
    currentPrintTarget = tabName;
    let url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.pushState({}, '', url);
}

function triggerPrintTab(target) {
    currentPrintTarget = target;
    
    document.body.classList.remove('printing-kir', 'printing-peminjaman-aset', 'printing-peminjaman-ruangan');
    
    if (target === 'kir') {
        document.body.classList.add('printing-kir');
    } else if (target === 'peminjaman-aset') {
        document.body.classList.add('printing-peminjaman-aset');
    } else if (target === 'peminjaman-ruangan') {
        document.body.classList.add('printing-peminjaman-ruangan');
    }

    setTimeout(function() {
        window.print();
    }, 150);
}

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing-kir', 'printing-peminjaman-aset', 'printing-peminjaman-ruangan');
});

// Sinkronisasi Live Preview Tanda Tangan
function syncKIRSignaturesLive() {
    let ketuaNamaEl = document.getElementById('inputNamaKetua');
    let ketuaNipEl  = document.getElementById('inputNipKetua');
    let kabagNamaEl = document.getElementById('inputNamaKabag');
    let kabagNipEl  = document.getElementById('inputNipKabag');
    let kotaEl      = document.getElementById('inputKotaDokumen');

    let ketuaNama = ketuaNamaEl ? ketuaNamaEl.value : '';
    let ketuaNip  = ketuaNipEl ? ketuaNipEl.value : '';
    let kabagNama = kabagNamaEl ? kabagNamaEl.value : '';
    let kabagNip  = kabagNipEl ? kabagNipEl.value : '';
    let kota      = kotaEl ? (kotaEl.value || 'Malang') : 'Malang';
    
    // Format date string from current date
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const dateFormatted = new Date().toLocaleDateString('id-ID', options);
    let formattedTgl = kota + ', ' + dateFormatted;

    let previewEl = document.getElementById('previewTglDokumen');
    if (previewEl) previewEl.innerText = formattedTgl;

    document.querySelectorAll('.print-nama-ketua').forEach(el => el.innerText = ketuaNama);
    document.querySelectorAll('.print-nip-ketua').forEach(el => el.innerText = ketuaNip);
    document.querySelectorAll('.print-nama-kabag').forEach(el => el.innerText = kabagNama);
    document.querySelectorAll('.print-nip-kabag').forEach(el => el.innerText = kabagNip);
    document.querySelectorAll('.print-tgl-dokumen').forEach(el => el.innerText = formattedTgl);
}

// Simpan ke Database via AJAX
function saveSignaturesToDB(e) {
    e.preventDefault();
    let form = document.getElementById('formSimpanTTD');
    if (!form) return;

    let formData = new FormData(form);
    let btn = document.getElementById('btnSimpanTTD');
    let statusBadge = document.getElementById('ttdStatusSave');

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Menyimpan...';
    }

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Simpan ke Database';
        }
        if (data.success) {
            if (statusBadge) {
                statusBadge.classList.remove('d-none');
                setTimeout(() => {
                    statusBadge.classList.add('d-none');
                }, 4000);
            }
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Simpan ke Database';
        }
        alert('Gagal menyimpan penandatangan ke database: ' + err);
    });
}

// =========================================================================
// INTERACTIVE CONTROLLER TABEL KIR: SEARCH, SORT & PAGINATION
// =========================================================================
let kirData = [];
let kirFilteredData = [];
let kirCurrentPage = 1;
let kirPageSize = 10;
let kirSortColumn = 'no';
let kirSortAsc = true;

function initKIRTable() {
    const rows = document.querySelectorAll('.kir-row-item');
    if (rows.length === 0) return;

    kirData = Array.from(rows).map(row => ({
        element: row,
        no: parseInt(row.getAttribute('data-no')) || 0,
        kode: row.getAttribute('data-kode') || '',
        nama: row.getAttribute('data-nama') || '',
        kategori: row.getAttribute('data-kategori') || '',
        ruangan: row.getAttribute('data-ruangan') || '',
        jumlah: parseInt(row.getAttribute('data-jumlah')) || 0,
        kondisi: row.getAttribute('data-kondisi') || '',
        tahun: parseInt(row.getAttribute('data-tahun')) || 0
    }));
    kirFilteredData = [...kirData];
    renderKIRTable();
}

function onKIRTableFilter() {
    const searchInput = document.getElementById('tableSearchKIR');
    const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
    const btnClear = document.getElementById('btnClearSearchKIR');
    if (btnClear) {
        btnClear.classList.toggle('d-none', query.length === 0);
    }

    if (!query) {
        kirFilteredData = [...kirData];
    } else {
        kirFilteredData = kirData.filter(item => {
            return item.kode.includes(query) ||
                   item.nama.includes(query) ||
                   item.kategori.includes(query) ||
                   item.ruangan.includes(query) ||
                   item.kondisi.toLowerCase().includes(query) ||
                   String(item.tahun).includes(query);
        });
    }
    kirCurrentPage = 1;
    renderKIRTable();
}

function clearKIRSearch() {
    const input = document.getElementById('tableSearchKIR');
    if (input) {
        input.value = '';
        onKIRTableFilter();
        input.focus();
    }
}

function sortKIRTable(column) {
    if (kirSortColumn === column) {
        kirSortAsc = !kirSortAsc;
    } else {
        kirSortColumn = column;
        kirSortAsc = true;
    }

    // Update Sort Icons
    document.querySelectorAll('#tableKIR thead i[id^="sortIcon-"]').forEach(icon => {
        icon.className = 'fa-solid fa-sort small ms-0.5 text-muted';
    });
    const currentIcon = document.getElementById('sortIcon-' + column);
    if (currentIcon) {
        currentIcon.className = kirSortAsc 
            ? 'fa-solid fa-sort-up small ms-0.5 text-primary' 
            : 'fa-solid fa-sort-down small ms-0.5 text-primary';
    }

    kirFilteredData.sort((a, b) => {
        let valA = a[column];
        let valB = b[column];

        if (typeof valA === 'string') {
            return kirSortAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
        }
        return kirSortAsc ? (valA - valB) : (valB - valA);
    });

    renderKIRTable();
}

function changeKIRPageSize(size) {
    kirPageSize = parseInt(size);
    kirCurrentPage = 1;
    renderKIRTable();
}

function goToKIRPage(page) {
    kirCurrentPage = page;
    renderKIRTable();
}

function renderKIRTable() {
    const tbody = document.getElementById('tbodyKIR');
    if (!tbody) return;

    const totalItems = kirFilteredData.length;
    const pageSize = kirPageSize === -1 ? (totalItems || 1) : kirPageSize;
    const totalPages = Math.max(1, Math.ceil(totalItems / pageSize));

    if (kirCurrentPage > totalPages) kirCurrentPage = totalPages;

    const startIndex = (kirCurrentPage - 1) * pageSize;
    const endIndex = kirPageSize === -1 ? totalItems : Math.min(startIndex + pageSize, totalItems);

    // Hide all rows first
    kirData.forEach(item => {
        item.element.style.display = 'none';
    });

    // Show sliced rows & re-index row number
    const pageItems = kirFilteredData.slice(startIndex, endIndex);
    pageItems.forEach((item, idx) => {
        item.element.style.display = '';
        const noCell = item.element.querySelector('.col-no');
        if (noCell) {
            noCell.innerText = startIndex + idx + 1;
        }
        tbody.appendChild(item.element);
    });

    // Empty state row
    let emptyRow = document.getElementById('emptyRowKIR');
    if (totalItems === 0) {
        if (!emptyRow) {
            emptyRow = document.createElement('tr');
            emptyRow.id = 'emptyRowKIR';
            emptyRow.innerHTML = '<td colspan="8" class="text-center py-5 text-muted"><i class="fa-solid fa-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>Tidak ada data aset yang sesuai pencarian.</td>';
            tbody.appendChild(emptyRow);
        }
        emptyRow.style.display = '';
    } else if (emptyRow) {
        emptyRow.style.display = 'none';
    }

    // Update Info Text
    const startDisplay = totalItems === 0 ? 0 : startIndex + 1;
    const endDisplay = endIndex;
    const startEl = document.getElementById('kirShowingStart');
    const endEl = document.getElementById('kirShowingEnd');
    const totalEl = document.getElementById('kirTotalFiltered');
    if (startEl) startEl.innerText = startDisplay;
    if (endEl) endEl.innerText = endDisplay;
    if (totalEl) totalEl.innerText = totalItems;

    // Render Pagination Nav
    renderKIRPagination(totalPages);
}

function renderKIRPagination(totalPages) {
    const navList = document.getElementById('kirPaginationList');
    if (!navList) return;

    if (totalPages <= 1 || kirPageSize === -1) {
        navList.innerHTML = '';
        return;
    }

    let html = '';
    
    // Prev button
    html += `<li class="page-item ${kirCurrentPage === 1 ? 'disabled' : ''}">
                <button type="button" class="page-link rounded-2 px-2.5" onclick="goToKIRPage(${kirCurrentPage - 1})" aria-label="Previous">
                    <i class="fa-solid fa-chevron-left small"></i>
                </button>
             </li>`;

    // Page Numbers (Smart window)
    let startPage = Math.max(1, kirCurrentPage - 2);
    let endPage = Math.min(totalPages, startPage + 4);
    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }

    if (startPage > 1) {
        html += `<li class="page-item"><button type="button" class="page-link rounded-2" onclick="goToKIRPage(1)">1</button></li>`;
        if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link border-0">...</span></li>`;
    }

    for (let p = startPage; p <= endPage; p++) {
        html += `<li class="page-item ${p === kirCurrentPage ? 'active fw-bold' : ''}">
                    <button type="button" class="page-link rounded-2" onclick="goToKIRPage(${p})">${p}</button>
                 </li>`;
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link border-0">...</span></li>`;
        html += `<li class="page-item"><button type="button" class="page-link rounded-2" onclick="goToKIRPage(${totalPages})">${totalPages}</button></li>`;
    }

    // Next button
    html += `<li class="page-item ${kirCurrentPage === totalPages ? 'disabled' : ''}">
                <button type="button" class="page-link rounded-2 px-2.5" onclick="goToKIRPage(${kirCurrentPage + 1})" aria-label="Next">
                    <i class="fa-solid fa-chevron-right small"></i>
                </button>
             </li>`;

    navList.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');
    if (activeTab === 'peminjaman-aset') {
        let btn = document.getElementById('tab-pinjam-aset-btn');
        if (btn) btn.click();
    } else if (activeTab === 'peminjaman-ruangan') {
        let btn = document.getElementById('tab-pinjam-ruangan-btn');
        if (btn) btn.click();
    }

    // Init Interactive Table
    initKIRTable();
});
