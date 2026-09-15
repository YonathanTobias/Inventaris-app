// State keranjang tersimpan di LocalStorage
let loanCart = [];

try {
    let saved = localStorage.getItem('stikes_loan_cart');
    if (saved) {
        loanCart = JSON.parse(saved);
    }
} catch (e) {
    loanCart = [];
}

function getAvailableItems() {
    return window.SPARTA_CONFIG?.items || [];
}

function getAvailableRuangans() {
    return window.SPARTA_CONFIG?.ruangans || [];
}

function handleLiveItemSearch(keyword) {
    let kw = (keyword || '').trim().toLowerCase();
    let resultsBox = document.getElementById('liveSearchResults');
    if (!resultsBox) return;

    const allAvailableItems = getAvailableItems();

    if (kw.length === 0) {
        renderLiveSearchResults(allAvailableItems.slice(0, 6), '');
        resultsBox.classList.remove('d-none');
        return;
    }

    let filtered = allAvailableItems.filter(item => {
        return item.nama.toLowerCase().includes(kw) || 
               item.kode.toLowerCase().includes(kw) || 
               item.ruangan.toLowerCase().includes(kw) ||
               item.kategori.toLowerCase().includes(kw);
    });

    renderLiveSearchResults(filtered, kw);
    resultsBox.classList.remove('d-none');
}

function renderLiveSearchResults(items, kw) {
    let resultsBox = document.getElementById('liveSearchResults');
    if (!resultsBox) return;

    if (items.length === 0) {
        resultsBox.innerHTML = `
            <div class="p-3 text-center text-muted small">
                <i class="fa-solid fa-circle-question me-1"></i> Tidak ditemukan aset dengan kata kunci "<strong>${kw}</strong>".
            </div>
        `;
        return;
    }

    let html = '<div class="list-group list-group-flush small">';
    items.forEach(item => {
        let isAlreadyInCart = loanCart.some(c => c.id == item.id);
        let safeNama = item.nama.replace(/'/g, "\\'");
        let safeRuangan = item.ruangan.replace(/'/g, "\\'");
        html += `
            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-2.5 rounded-2 mb-1">
                <div>
                    <strong class="text-dark d-block">${item.nama}</strong>
                    <div class="d-flex align-items-center gap-1.5 mt-0.5">
                        <span class="badge-code py-0" style="font-size: 0.68rem;">${item.kode}</span>
                        <small class="text-muted" style="font-size: 0.72rem;">${item.ruangan}</small>
                        <span class="badge bg-success-subtle text-success py-0" style="font-size: 0.68rem;">Sisa ${item.stok} unit</span>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-sm ${isAlreadyInCart ? 'btn-outline-primary' : 'btn-primary'} fw-bold rounded-pill px-2.5 py-1" onclick="quickAddFromSearch('${item.id}', '${safeNama}', '${item.kode}', '${safeRuangan}', ${item.stok})">
                        ${isAlreadyInCart ? '+ Tambah' : '+ Pilih'}
                    </button>
                </div>
            </div>
        `;
    });
    html += '</div>';
    resultsBox.innerHTML = html;
}

function quickAddFromSearch(id, nama, kode, ruangan, maxStok) {
    addToCart(id, nama, kode, ruangan, maxStok);
    clearLiveSearch();
}

function clearLiveSearch() {
    let input = document.getElementById('liveSearchInput');
    if (input) input.value = '';
    let resultsBox = document.getElementById('liveSearchResults');
    if (resultsBox) resultsBox.classList.add('d-none');
}

// Filter Live Kartu Katalog di Bawah
function filterCatalogCards(keyword) {
    let kw = (keyword || '').trim().toLowerCase();
    let cards = document.querySelectorAll('.catalog-item-card');
    let matchedCount = 0;

    cards.forEach(card => {
        let name = card.getAttribute('data-name') || '';
        let code = card.getAttribute('data-code') || '';
        let room = card.getAttribute('data-room') || '';
        let cat = card.getAttribute('data-cat') || '';

        if (!kw || name.includes(kw) || code.includes(kw) || room.includes(kw) || cat.includes(kw)) {
            card.style.display = 'block';
            matchedCount++;
        } else {
            card.style.display = 'none';
        }
    });

    let noMatch = document.getElementById('catalogNoMatchMessage');
    if (noMatch) {
        noMatch.classList.toggle('d-none', matchedCount > 0);
    }

    let countBadge = document.getElementById('catalogCountBadge');
    if (countBadge) {
        countBadge.innerText = `${matchedCount} Jenis Aset Ditemukan`;
    }
}

// Data Ruangan Tersedia untuk Pencarian Cepat
function handleSearchRuanganLive(keyword) {
    let kw = (keyword || '').trim().toLowerCase();
    let resultsBox = document.getElementById('dropdownRuanganResults');
    if (!resultsBox) return;

    const allAvailableRuangans = getAvailableRuangans();

    if (kw.length === 0) {
        renderRuanganSearchResults(allAvailableRuangans, '');
        resultsBox.classList.remove('d-none');
        return;
    }

    let filtered = allAvailableRuangans.filter(r => {
        return r.nama.toLowerCase().includes(kw) || r.kode.toLowerCase().includes(kw);
    });

    renderRuanganSearchResults(filtered, kw);
    resultsBox.classList.remove('d-none');
}

function renderRuanganSearchResults(items, kw) {
    let resultsBox = document.getElementById('dropdownRuanganResults');
    if (!resultsBox) return;

    if (items.length === 0) {
        resultsBox.innerHTML = `
            <div class="p-3 text-center text-muted small">
                <i class="fa-solid fa-circle-question me-1"></i> Tidak ditemukan ruangan dengan kata kunci "<strong>${kw}</strong>".
            </div>
        `;
        return;
    }

    let html = '<div class="list-group list-group-flush small">';
    items.forEach(r => {
        let safeNama = r.nama.replace(/'/g, "\\'");
        html += `
            <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-2.5 rounded-2 mb-1 border-0" onclick="chooseRuangan('${r.id}', '${safeNama}', '${r.kode}')">
                <div class="text-start">
                    <strong class="text-dark d-block">${r.nama}</strong>
                    <span class="badge-code py-0" style="font-size: 0.68rem;">${r.kode}</span>
                </div>
                <span class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-0.5 fw-bold" style="font-size: 0.72rem;">
                    Pilih &rarr;
                </span>
            </button>
        `;
    });
    html += '</div>';
    resultsBox.innerHTML = html;
}

function chooseRuangan(id, nama, kode) {
    let selectPublik = document.getElementById('selectRuanganPublik');
    if (selectPublik) selectPublik.value = id;
    
    let txtNama = document.getElementById('selectedRuanganNamaText');
    if (txtNama) txtNama.innerText = nama;
    
    let txtKode = document.getElementById('selectedRuanganKodeText');
    if (txtKode) txtKode.innerText = kode;

    let cardSel = document.getElementById('selectedRuanganCard');
    if (cardSel) cardSel.classList.remove('d-none');
    
    let searchWrap = document.getElementById('searchRuanganWrapper');
    if (searchWrap) searchWrap.classList.add('d-none');
    
    let dropRes = document.getElementById('dropdownRuanganResults');
    if (dropRes) dropRes.classList.add('d-none');

    // Cek jadwal langsung
    cekJadwalRuangan(id);
}

function resetSelectedRuangan() {
    let selectPublik = document.getElementById('selectRuanganPublik');
    if (selectPublik) selectPublik.value = '';
    
    let cardSel = document.getElementById('selectedRuanganCard');
    if (cardSel) cardSel.classList.add('d-none');
    
    let searchWrap = document.getElementById('searchRuanganWrapper');
    if (searchWrap) searchWrap.classList.remove('d-none');
    
    let inputSearch = document.getElementById('inputSearchRuangan');
    if (inputSearch) {
        inputSearch.value = '';
        inputSearch.focus();
    }
    
    let boxJadwal = document.getElementById('boxJadwalTerisi');
    if (boxJadwal) boxJadwal.style.display = 'none';
}

function clearSearchRuanganInput() {
    let input = document.getElementById('inputSearchRuangan');
    if (input) input.value = '';
    let resultsBox = document.getElementById('dropdownRuanganResults');
    if (resultsBox) resultsBox.classList.add('d-none');
}

function saveCart() {
    try {
        localStorage.setItem('stikes_loan_cart', JSON.stringify(loanCart));
    } catch (e) {}
    renderCart();
}

function stepItemQty(itemId, step) {
    let input = document.getElementById('catalogQty' + itemId);
    if (!input) return;
    let current = parseInt(input.value) || 1;
    let min = parseInt(input.min) || 1;
    let max = parseInt(input.max) || 999;
    let nextVal = current + step;
    if (nextVal >= min && nextVal <= max) {
        input.value = nextVal;
    }
}

function addToCart(id, nama, kode, ruangan, maxStok) {
    let qtyInput = document.getElementById('catalogQty' + id);
    let qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;

    if (qty < 1) qty = 1;
    if (qty > maxStok) {
        alert('Jumlah unit melebihi stok yang tersedia (' + maxStok + ' unit)!');
        return;
    }

    let existing = loanCart.find(item => item.id == id);
    if (existing) {
        let newQty = existing.qty + qty;
        if (newQty > maxStok) {
            alert('Total di daftar pinjam (' + newQty + ') melebihi batas stok yang ada (' + maxStok + ' unit)!');
            existing.qty = maxStok;
        } else {
            existing.qty = newQty;
        }
    } else {
        loanCart.push({
            id: id,
            nama: nama,
            kode: kode,
            ruangan: ruangan,
            max: maxStok,
            qty: qty
        });
    }

    saveCart();
    showToastSuccess(nama + ' (' + qty + ' unit) ditambahkan ke daftar pinjam!');
}

function updateCartItemQty(id, delta) {
    let item = loanCart.find(i => i.id == id);
    if (!item) return;
    let next = item.qty + delta;
    if (next <= 0) {
        removeFromCart(id);
        return;
    }
    if (next > item.max) {
        alert('Maksimal unit tersedia: ' + item.max);
        return;
    }
    item.qty = next;
    saveCart();
}

function removeFromCart(id) {
    loanCart = loanCart.filter(item => item.id != id);
    saveCart();
}

function renderCart() {
    let totalJenis = loanCart.length;
    let totalUnit = loanCart.reduce((sum, item) => sum + item.qty, 0);

    // Update badge counts
    let navCount = document.getElementById('navCartCount');
    if (navCount) navCount.innerText = totalJenis;

    let floatingCount = document.getElementById('floatingCartCount');
    if (floatingCount) floatingCount.innerText = totalJenis;

    let formBadge = document.getElementById('formCartCountBadge');
    if (formBadge) formBadge.innerText = totalJenis;

    let sumText = document.getElementById('cartSummaryText');
    if (sumText) sumText.innerText = totalJenis + ' jenis aset dipilih';

    let totalQty = document.getElementById('cartTotalQty');
    if (totalQty) totalQty.innerText = totalUnit + ' Unit';

    // Floating button visibility (Hanya jika di Tab Aset)
    let floatingBtn = document.getElementById('btnFloatingCart');
    let isAsetTab = document.getElementById('pills-aset-tab')?.classList.contains('active');
    if (floatingBtn) {
        if (totalJenis > 0 && isAsetTab) {
            floatingBtn.style.display = 'flex';
        } else {
            floatingBtn.style.display = 'none';
        }
    }

    // Render Offcanvas Drawer
    let offcanvasContainer = document.getElementById('cartItemsContainer');
    if (offcanvasContainer) {
        if (loanCart.length === 0) {
            offcanvasContainer.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-clipboard-list fa-3x opacity-25 mb-3"></i>
                    <h6 class="fw-bold">Daftar Pinjam Masih Kosong</h6>
                    <p class="small">Pilih aset praktikum dari katalog di halaman utama untuk ditambahkan ke daftar pinjam.</p>
                </div>
            `;
        } else {
            let html = '';
            loanCart.forEach(item => {
                html += `
                    <div class="cart-item-row">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <strong class="d-block text-dark small">${item.nama}</strong>
                                <span class="badge-code" style="font-size: 0.72rem;">${item.kode}</span>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">${item.ruangan}</small>
                            </div>
                            <button type="button" class="btn btn-link text-danger p-0" onclick="removeFromCart('${item.id}')" title="Hapus dari daftar pinjam">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
                            <small class="text-muted">Jumlah: (Maks: ${item.max})</small>
                            <div class="d-flex align-items-center gap-1.5">
                                <button type="button" class="qty-stepper-btn" onclick="updateCartItemQty('${item.id}', -1)">-</button>
                                <span class="fw-bold font-monospace px-2">${item.qty}</span>
                                <button type="button" class="qty-stepper-btn" onclick="updateCartItemQty('${item.id}', 1)">+</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            offcanvasContainer.innerHTML = html;
        }
    }

    // Render Preview Table di Form Pengajuan
    let tbodyPreview = document.getElementById('tbodyPreviewCart');
    let emptyAlert = document.getElementById('emptyCartAlert');
    let tablePreview = document.getElementById('tablePreviewCart');
    let btnSubmit = document.getElementById('btnSubmitPengajuan');
    let hiddenCartData = document.getElementById('hiddenCartData');

    if (hiddenCartData) {
        hiddenCartData.value = JSON.stringify(loanCart.map(i => ({ barang_id: i.id, jumlah: i.qty })));
    }

    if (loanCart.length === 0) {
        if (tbodyPreview) tbodyPreview.innerHTML = '';
        if (tablePreview) tablePreview.style.display = 'none';
        if (emptyAlert) emptyAlert.style.display = 'flex';
        if (btnSubmit) btnSubmit.disabled = true;
    } else {
        if (emptyAlert) emptyAlert.style.display = 'none';
        if (tablePreview) tablePreview.style.display = 'table';
        if (btnSubmit) btnSubmit.disabled = false;

        if (tbodyPreview) {
            let tableHtml = '';
            loanCart.forEach((item, index) => {
                tableHtml += `
                    <tr>
                        <td class="ps-3 fw-bold text-muted">${index + 1}</td>
                        <td>
                            <div class="fw-bold text-dark">${item.nama}</div>
                            <div class="d-flex align-items-center gap-1.5 mt-0.5">
                                <span class="badge-code py-0">${item.kode}</span>
                                <small class="text-muted">${item.ruangan}</small>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1">
                                <button type="button" class="qty-stepper-btn" onclick="updateCartItemQty('${item.id}', -1)">-</button>
                                <span class="fw-bold font-monospace px-2">${item.qty}</span>
                                <button type="button" class="qty-stepper-btn" onclick="updateCartItemQty('${item.id}', 1)">+</button>
                            </div>
                        </td>
                        <td class="text-center pe-3">
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1" onclick="removeFromCart('${item.id}')" title="Hapus Aset">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            tbodyPreview.innerHTML = tableHtml;
        }
    }
}

function scrollToFormAset() {
    let tabBtn = document.getElementById('pills-aset-tab');
    if (tabBtn) tabBtn.click();
    let el = document.getElementById('sectionFormPortal');
    if (el) el.scrollIntoView({ behavior: 'smooth' });
}

function scrollToFormRuangan() {
    let tabBtn = document.getElementById('pills-ruangan-tab');
    if (tabBtn) tabBtn.click();
    let el = document.getElementById('sectionFormPortal');
    if (el) el.scrollIntoView({ behavior: 'smooth' });
}

function showToastSuccess(msg) {
    let floatingBtn = document.getElementById('btnFloatingCart');
    if (floatingBtn && floatingBtn.style.display !== 'none') {
        floatingBtn.classList.add('animate__animated', 'animate__bounce');
        setTimeout(() => {
            floatingBtn.classList.remove('animate__animated', 'animate__bounce');
        }, 1000);
    }
}

// Filter Kartu Ruangan di Katalog Ruangan
function filterRuanganCards(keyword) {
    let kw = (keyword || '').trim().toLowerCase();
    let cards = document.querySelectorAll('.catalog-ruangan-card');
    let matched = 0;

    cards.forEach(card => {
        let name = card.getAttribute('data-name') || '';
        let code = card.getAttribute('data-code') || '';
        if (name.includes(kw) || code.includes(kw)) {
            card.style.display = '';
            matched++;
        } else {
            card.style.display = 'none';
        }
    });

    let noMatch = document.getElementById('catalogRuanganNoMatchMessage');
    if (noMatch) {
        noMatch.classList.toggle('d-none', matched > 0);
    }
}

// Sinkronisasi Katalog Berdasarkan Tab yang Aktif
function handleTabChange(targetTabId) {
    let sectionAset = document.getElementById('sectionKatalogAset');
    let sectionRuangan = document.getElementById('sectionKatalogRuangan');
    let floatingBtn = document.getElementById('btnFloatingCart');

    if (targetTabId === 'pills-aset') {
        if (sectionAset) sectionAset.style.display = 'block';
        if (sectionRuangan) sectionRuangan.style.display = 'none';
        if (floatingBtn && loanCart.length > 0) floatingBtn.style.display = 'flex';
    } else if (targetTabId === 'pills-ruangan') {
        if (sectionAset) sectionAset.style.display = 'none';
        if (sectionRuangan) sectionRuangan.style.display = 'block';
        if (floatingBtn) floatingBtn.style.display = 'none';
    }
}

// AJAX Cek Jadwal Ruangan
function triggerCekJadwal() {
    let sel = document.getElementById('selectRuanganPublik');
    if (sel && sel.value) {
        cekJadwalRuangan(sel.value);
    }
}

function cekJadwalRuangan(ruanganId) {
    let tglEl = document.getElementById('inputTanggalRuangan');
    let tgl = tglEl ? tglEl.value : '';
    let box = document.getElementById('boxJadwalTerisi');
    let list = document.getElementById('listJadwalRuangan');

    if (!ruanganId || !tgl || !box || !list) {
        if (box) box.style.display = 'none';
        return;
    }

    let endpoint = window.SPARTA_CONFIG?.jadwalUrl || '/ruangan/jadwal-terisi';

    fetch(`${endpoint}?ruangan_id=${ruanganId}&tanggal=${tgl}`)
        .then(res => res.json())
        .then(data => {
            if (data && data.length > 0) {
                let html = '<ul class="mb-0 ps-3">';
                data.forEach(item => {
                    html += `<li><strong>${item.jam_mulai.substring(0,5)} - ${item.jam_selesai.substring(0,5)} WIB</strong>: ${item.nama_peminjam} (${item.keperluan}) - <span class="badge bg-secondary py-0">${item.status}</span></li>`;
                });
                html += '</ul>';
                list.innerHTML = html;
                box.style.display = 'block';
            } else {
                list.innerHTML = '<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Belum ada jadwal pemakaian di tanggal ini (Ruangan Masih Kosong).</span>';
                box.style.display = 'block';
            }
        })
        .catch(err => {
            if (box) box.style.display = 'none';
        });
}

// Theme Switcher Logic for Portal Publik
function updatePublicThemeUI(theme) {
    const icon = document.getElementById('themeIconPublic');
    if (!icon) return;
    if (theme === 'dark') {
        icon.className = 'fa-solid fa-sun text-warning';
    } else {
        icon.className = 'fa-solid fa-moon text-dark';
    }
}

function togglePublicTheme() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('sparta_theme', newTheme);
    updatePublicThemeUI(newTheme);
}

// Global Event Listeners
document.addEventListener('click', function(e) {
    let searchBox = document.getElementById('liveSearchResults');
    let searchInput = document.getElementById('liveSearchInput');
    if (searchBox && !searchBox.contains(e.target) && e.target !== searchInput) {
        searchBox.classList.add('d-none');
    }

    let boxRuangan = document.getElementById('dropdownRuanganResults');
    let inputRuangan = document.getElementById('inputSearchRuangan');
    if (boxRuangan && !boxRuangan.contains(e.target) && e.target !== inputRuangan) {
        boxRuangan.classList.add('d-none');
    }
});

// Inisialisasi saat load
document.addEventListener('DOMContentLoaded', function() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    updatePublicThemeUI(currentTheme);
    renderCart();

    // Hapus cart saat submit sukses dilakukan (di form aset)
    let formMandiri = document.getElementById('formPeminjamanMandiri');
    if (formMandiri) {
        formMandiri.addEventListener('submit', function(e) {
            if (loanCart.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal 1 aset ke daftar pinjam terlebih dahulu!');
                return;
            }
            setTimeout(() => {
                localStorage.removeItem('stikes_loan_cart');
            }, 500);
        });
    }

    // Pasang event listener saat tab berpindah
    document.querySelectorAll('#pills-tab button[data-bs-toggle="pill"]').forEach(tabBtn => {
        tabBtn.addEventListener('shown.bs.tab', function(e) {
            let targetId = e.target.getAttribute('data-bs-target')?.replace('#', '') || '';
            handleTabChange(targetId);
        });
    });

    // Restore old selected ruangan jika ada
    let oldRuanganId = window.SPARTA_CONFIG?.oldRuanganId;
    if (oldRuanganId) {
        let allAvailableRuangans = getAvailableRuangans();
        let found = allAvailableRuangans.find(r => r.id == oldRuanganId);
        if (found) {
            chooseRuangan(found.id, found.nama, found.kode);
        }
    }

    // Hash navigation tab support (misal: /#tab-ruangan)
    if (window.location.hash === '#tab-ruangan') {
        let tabRuangan = document.getElementById('pills-ruangan-tab');
        if (tabRuangan) {
            tabRuangan.click();
            handleTabChange('pills-ruangan');
        }
    }
});
