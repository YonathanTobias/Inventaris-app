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

// ==========================================
// 1. AUTO-FILL IDENTITAS & LOCALSTORAGE
// ==========================================
function saveProfileToStorage() {
    let profile = {
        kategori: document.getElementById('inputKategoriPeminjam')?.value || document.getElementById('inputKategoriRuangan')?.value || '',
        nama: document.getElementById('inputNamaPeminjam')?.value || document.getElementById('inputNamaRuangan')?.value || '',
        identitas: document.getElementById('inputNomorIdentitas')?.value || document.getElementById('inputNomorRuangan')?.value || '',
        prodi: document.getElementById('inputProdiUnit')?.value || document.getElementById('inputProdiRuangan')?.value || '',
        kontak: document.getElementById('inputKontakPeminjam')?.value || document.getElementById('inputKontakRuangan')?.value || ''
    };
    try {
        localStorage.setItem('sparta_applicant_profile', JSON.stringify(profile));
    } catch (e) {}
}

function loadProfileFromStorage() {
    try {
        let saved = localStorage.getItem('sparta_applicant_profile');
        let profile = saved ? JSON.parse(saved) : {};
        let authUser = window.SPARTA_CONFIG?.authUser;

        // Field Aset
        let fKatAset = document.getElementById('inputKategoriPeminjam');
        let fNamaAset = document.getElementById('inputNamaPeminjam');
        let fIdAset = document.getElementById('inputNomorIdentitas');
        let fProdiAset = document.getElementById('inputProdiUnit');
        let fKontakAset = document.getElementById('inputKontakPeminjam');

        if (fNamaAset && !fNamaAset.value) {
            fNamaAset.value = authUser?.name || profile.nama || '';
        }
        if (fKatAset && profile.kategori) fKatAset.value = profile.kategori;
        if (fIdAset && !fIdAset.value && profile.identitas) fIdAset.value = profile.identitas;
        if (fProdiAset && !fProdiAset.value && profile.prodi) fProdiAset.value = profile.prodi;
        if (fKontakAset && !fKontakAset.value && profile.kontak) fKontakAset.value = profile.kontak;

        // Field Ruangan
        let fKatRuang = document.getElementById('inputKategoriRuangan');
        let fNamaRuang = document.getElementById('inputNamaRuangan');
        let fIdRuang = document.getElementById('inputNomorRuangan');
        let fProdiRuang = document.getElementById('inputProdiRuangan');
        let fKontakRuang = document.getElementById('inputKontakRuangan');

        if (fNamaRuang && !fNamaRuang.value) {
            fNamaRuang.value = authUser?.name || profile.nama || '';
        }
        if (fKatRuang && profile.kategori) fKatRuang.value = profile.kategori;
        if (fIdRuang && !fIdRuang.value && profile.identitas) fIdRuang.value = profile.identitas;
        if (fProdiRuang && !fProdiRuang.value && profile.prodi) fProdiRuang.value = profile.prodi;
        if (fKontakRuang && !fKontakRuang.value && profile.kontak) fKontakRuang.value = profile.kontak;
    } catch (e) {}
}

// ==========================================
// 2. UNIFIED SEARCH & CATEGORY FILTERING
// ==========================================
let currentSelectedCategory = 'all';

function filterByCategory(category, btnElement) {
    currentSelectedCategory = (category || 'all').toLowerCase();
    document.querySelectorAll('.category-chip-btn').forEach(btn => btn.classList.remove('active'));
    if (btnElement) btnElement.classList.add('active');
    handleUnifiedAssetFilter();
}

function handleUnifiedAssetFilter() {
    let searchInput = document.getElementById('unifiedAssetSearchInput');
    let kw = (searchInput?.value || '').trim().toLowerCase();
    let cards = document.querySelectorAll('.catalog-item-card');
    let matchedCount = 0;

    cards.forEach(card => {
        let name = card.getAttribute('data-name') || '';
        let code = card.getAttribute('data-code') || '';
        let room = card.getAttribute('data-room') || '';
        let cat = card.getAttribute('data-cat') || '';

        let matchesKw = !kw || name.includes(kw) || code.includes(kw) || room.includes(kw) || cat.includes(kw);
        let matchesCat = currentSelectedCategory === 'all' || cat.includes(currentSelectedCategory);

        if (matchesKw && matchesCat) {
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

    let countBadge = document.getElementById('unifiedAssetCountBadge');
    if (countBadge) {
        countBadge.innerText = `${matchedCount} Jenis Aset Ditemukan`;
    }
}

function clearUnifiedAssetFilter() {
    let searchInput = document.getElementById('unifiedAssetSearchInput');
    if (searchInput) searchInput.value = '';
    filterByCategory('all', document.querySelector('.category-chip-btn[data-category="all"]'));
}

// ==========================================
// 3. 2-STEP VIEW NAVIGATION & STEPPER
// ==========================================
function proceedToStep2Form() {
    if (loanCart.length === 0) {
        alert('Keranjang masih kosong! Silakan pilih minimal 1 aset pada katalog di bawah sebelum melanjutkan pengisian formulir.');
        return;
    }
    let step1 = document.getElementById('viewStep1Aset');
    let step2 = document.getElementById('viewStep2Aset');
    if (step1 && step2) {
        step1.classList.add('d-none');
        step2.classList.remove('d-none');
        updateStepperActive(2);
        updateFloatingActionBar();
        step2.scrollIntoView({ behavior: 'smooth' });
    }
}

function backToStep1Catalog() {
    let step1 = document.getElementById('viewStep1Aset');
    let step2 = document.getElementById('viewStep2Aset');
    if (step1 && step2) {
        step2.classList.add('d-none');
        step1.classList.remove('d-none');
        updateStepperActive(1);
        updateFloatingActionBar();
        step1.scrollIntoView({ behavior: 'smooth' });
    }
}

function updateStepperActive(stepNumber) {
    let node1 = document.getElementById('stepperNode1');
    let node2 = document.getElementById('stepperNode2');
    let node3 = document.getElementById('stepperNode3');
    let node4 = document.getElementById('stepperNode4');

    if (node1 && node2 && node3 && node4) {
        [node1, node2, node3, node4].forEach((node, index) => {
            let num = index + 1;
            if (num < stepNumber) {
                node.className = 'step-node completed';
            } else if (num === stepNumber) {
                node.className = 'step-node active';
            } else {
                node.className = 'step-node';
            }
        });
    }
}

function handleTabSwitch(tabName) {
    let floatingBar = document.getElementById('floatingBottomActionBar');
    if (tabName === 'ruangan') {
        if (floatingBar) floatingBar.classList.remove('show');
        let step2Ruangan = document.getElementById('viewStep2RuanganForm');
        if (step2Ruangan && !step2Ruangan.classList.contains('d-none')) {
            updateStepperActive(2);
        } else {
            updateStepperActive(1);
        }
    } else {
        let step2Aset = document.getElementById('viewStep2Aset');
        if (step2Aset && !step2Aset.classList.contains('d-none')) {
            updateStepperActive(2);
        } else {
            updateStepperActive(1);
        }
        updateFloatingActionBar();
    }
}

// ==========================================
// 4. KERANJANG PINJAM ASET & FLOATING BAR
// ==========================================
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
            alert('Total di keranjang (' + newQty + ') melebihi batas stok (' + maxStok + ' unit)!');
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
        alert('Maksimal unit yang tersedia: ' + item.max);
        return;
    }
    item.qty = next;
    saveCart();
}

function removeFromCart(id) {
    loanCart = loanCart.filter(item => item.id != id);
    saveCart();
}

function updateFloatingActionBar() {
    let bar = document.getElementById('floatingBottomActionBar');
    let isAsetTab = document.getElementById('pills-aset-tab')?.classList.contains('active');
    let isStep1 = !document.getElementById('viewStep1Aset')?.classList.contains('d-none');
    let totalJenis = loanCart.length;
    let totalUnit = loanCart.reduce((sum, item) => sum + item.qty, 0);

    let txtItems = document.getElementById('floatingBarItemsText');
    let txtQty = document.getElementById('floatingBarQtyText');
    let btnCartBadge = document.getElementById('btnCartCountBadge');

    if (txtItems) txtItems.innerText = `${totalJenis} Jenis Aset Dipilih`;
    if (txtQty) txtQty.innerText = `${totalUnit} Unit total permohonan`;
    if (btnCartBadge) btnCartBadge.innerText = `${totalJenis} Item (${totalUnit} Unit)`;

    if (bar) {
        if (totalJenis > 0 && isAsetTab && isStep1) {
            bar.classList.add('show');
        } else {
            bar.classList.remove('show');
        }
    }
}

function renderCart() {
    let totalJenis = loanCart.length;
    let totalUnit = loanCart.reduce((sum, item) => sum + item.qty, 0);

    // Update badge counts
    let navCount = document.getElementById('navCartCount');
    if (navCount) navCount.innerText = totalJenis;

    let sumText = document.getElementById('cartSummaryText');
    if (sumText) sumText.innerText = totalJenis + ' jenis aset dipilih';

    let totalQty = document.getElementById('cartTotalQty');
    if (totalQty) totalQty.innerText = totalUnit + ' Unit';

    // Update Floating Action Bar
    updateFloatingActionBar();

    // Render Offcanvas Drawer
    let offcanvasContainer = document.getElementById('cartItemsContainer');
    if (offcanvasContainer) {
        if (loanCart.length === 0) {
            offcanvasContainer.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-clipboard-list fa-3x opacity-25 mb-3"></i>
                    <h6 class="fw-bold">Keranjang Masih Kosong</h6>
                    <p class="small">Pilih aset dari katalog untuk ditambahkan ke daftar peminjaman.</p>
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
                            <button type="button" class="btn btn-link text-danger p-0" onclick="removeFromCart('${item.id}')" title="Hapus aset">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
                            <small class="text-muted">Jumlah (Maks: ${item.max}):</small>
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

    // Render Preview Table di Step 2 Formulir
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

// ==========================================
// 5. DATA RUANGAN & LIVE SEARCH RUANGAN
// ==========================================
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

    // Sync selected date from catalog to form
    let catDate = document.getElementById('catalogRuanganDateFilter');
    let formDate = document.getElementById('inputTanggalRuangan');
    if (catDate && formDate && catDate.value) {
        formDate.value = catDate.value;
    }

    // Cek jadwal bentrok langsung
    cekJadwalRuangan(id);
}

function proceedToStep2RuanganForm() {
    let rId = document.getElementById('selectRuanganPublik')?.value;
    if (!rId) {
        alert('Silakan pilih salah satu ruangan terlebih dahulu.');
        return;
    }

    let step1 = document.getElementById('viewStep1Ruangan');
    let step2 = document.getElementById('viewStep2RuanganForm');

    if (step1 && step2) {
        step1.classList.add('d-none');
        step2.classList.remove('d-none');
        updateStepperActive(2);
        step2.scrollIntoView({ behavior: 'smooth' });
    }
}

function backToStep1Ruangan() {
    let step1 = document.getElementById('viewStep1Ruangan');
    let step2 = document.getElementById('viewStep2RuanganForm');

    if (step1 && step2) {
        step2.classList.add('d-none');
        step1.classList.remove('d-none');
        updateStepperActive(1);
        step1.scrollIntoView({ behavior: 'smooth' });
    }
}

let currentSelectedRuanganCategory = 'all';

function filterRuanganByCategory(category, btnElement) {
    currentSelectedRuanganCategory = (category || 'all').toLowerCase();
    document.querySelectorAll('.category-chip-btn[data-room-cat]').forEach(btn => btn.classList.remove('active'));
    if (btnElement) btnElement.classList.add('active');
    
    let searchInput = document.getElementById('catalogRuanganSearchInput');
    filterRuanganCards(searchInput ? searchInput.value : '');
}

function filterRuanganCards(kw) {
    let q = (kw || '').trim().toLowerCase();
    let cards = document.querySelectorAll('.catalog-ruangan-card');
    let matchedCount = 0;

    cards.forEach(card => {
        let name = card.getAttribute('data-name') || '';
        let code = card.getAttribute('data-code') || '';
        let cat = card.getAttribute('data-cat') || '';

        let matchesKw = !q || name.includes(q) || code.includes(q);
        let matchesCat = currentSelectedRuanganCategory === 'all' || cat === currentSelectedRuanganCategory;

        if (matchesKw && matchesCat) {
            card.style.display = 'block';
            matchedCount++;
        } else {
            card.style.display = 'none';
        }
    });

    let noMatch = document.getElementById('catalogRuanganNoMatchMessage');
    if (noMatch) {
        noMatch.classList.toggle('d-none', matchedCount > 0);
    }

    let countBadge = document.getElementById('catalogRuanganCountBadge');
    if (countBadge) {
        countBadge.innerText = `${matchedCount} Ruangan`;
    }
}

function handleRoomCatalogDateChange(newDate) {
    if (!newDate) return;
    
    let formDateInput = document.getElementById('inputTanggalRuangan');
    if (formDateInput) {
        formDateInput.value = newDate;
    }
    
    fetchAllRoomSchedulesForDate(newDate);
}

function fetchAllRoomSchedulesForDate(targetDate) {
    if (!targetDate) return;
    
    let endpoint = window.SPARTA_CONFIG?.jadwalUrl || '/ruangan/jadwal-terisi';
    
    let dObj = new Date(targetDate + 'T00:00:00');
    let monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    let formattedDate = !isNaN(dObj) ? `${dObj.getDate()} ${monthNames[dObj.getMonth()]}` : targetDate;
    
    document.querySelectorAll('.catalog-ruangan-card').forEach(card => {
        let rId = card.getAttribute('data-room-id');
        let dateLabel = document.getElementById('roomDateLabel' + rId);
        if (dateLabel) dateLabel.innerText = formattedDate;
    });

    fetch(`${endpoint}?tanggal=${targetDate}`)
        .then(res => res.json())
        .then(data => {
            let scheduleMap = {};
            if (Array.isArray(data)) {
                data.forEach(item => {
                    let rId = item.ruangan_id;
                    if (!scheduleMap[rId]) scheduleMap[rId] = [];
                    scheduleMap[rId].push(item);
                });
            }

            document.querySelectorAll('.catalog-ruangan-card').forEach(card => {
                let rId = card.getAttribute('data-room-id');
                let statusEl = document.getElementById('roomStatusText' + rId);
                let chipsEl = document.getElementById('roomSlotChips' + rId);
                let bookings = scheduleMap[rId] || [];

                if (bookings.length === 0) {
                    if (statusEl) {
                        statusEl.className = 'slot-status-free';
                        statusEl.innerHTML = '<i class="fa-solid fa-circle-check"></i> Kosong (Siap Booking)';
                    }
                    if (chipsEl) {
                        chipsEl.innerHTML = '';
                        chipsEl.classList.add('d-none');
                    }
                } else {
                    if (statusEl) {
                        statusEl.className = 'slot-status-partial';
                        statusEl.innerHTML = `<i class="fa-solid fa-clock"></i> Terpakai ${bookings.length} Slot`;
                    }
                    if (chipsEl) {
                        let chipsHtml = '';
                        bookings.forEach(b => {
                            let start = (b.jam_mulai || '').substring(0, 5);
                            let end = (b.jam_selesai || '').substring(0, 5);
                            chipsHtml += `<span class="slot-time-chip text-muted" title="${b.nama_peminjam} - ${b.keperluan}"><i class="fa-solid fa-lock text-warning me-1"></i>${start}-${end}</span>`;
                        });
                        chipsEl.innerHTML = chipsHtml;
                        chipsEl.classList.remove('d-none');
                    }
                }
            });
        })
        .catch(err => {
            console.error('Error fetching schedules:', err);
        });
}

function handleTimeSlotChange() {
    let startSelect = document.getElementById('selectJamMulai');
    let endSelect = document.getElementById('selectJamSelesai');
    if (!startSelect || !endSelect) return;

    let startVal = startSelect.value;
    let endVal = endSelect.value;

    let allSlots = [
        '07:30', '08:00', '08:30', '09:00', '09:30',
        '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
        '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
        '16:00', '16:30', '17:00', '17:30', '18:00'
    ];

    let validEndSlots = allSlots.filter(s => s > startVal);

    let html = '';
    validEndSlots.forEach((s, idx) => {
        let isSelected = (s === endVal || (!validEndSlots.includes(endVal) && idx === Math.min(2, validEndSlots.length - 1))) ? 'selected' : '';
        html += `<option value="${s}" ${isSelected}>${s} WIB</option>`;
    });

    endSelect.innerHTML = html;
}

function resetSelectedRuangan() {
    let selectPublik = document.getElementById('selectRuanganPublik');
    if (selectPublik) selectPublik.value = '';
    
    let cardSel = document.getElementById('selectedRuanganCard');
    if (cardSel) cardSel.classList.add('d-none');
    
    let boxJadwal = document.getElementById('boxJadwalTerisi');
    if (boxJadwal) boxJadwal.style.display = 'none';

    backToStep1Ruangan();
}

function scrollToFormRuangan() {
    proceedToStep2RuanganForm();
}

// ==========================================
// 6. REAL-TIME JADWAL RUANGAN (AJAX)
// ==========================================
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
                list.innerHTML = '<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Belum ada jadwal pemakaian pada tanggal ini (Ruangan Siap Digunakan).</span>';
                box.style.display = 'block';
            }
        })
        .catch(err => {
            if (box) box.style.display = 'none';
        });
}

// ==========================================
// 7. THEME SWITCHER
// ==========================================
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

// ==========================================
// 8. GLOBAL EVENT LISTENERS & INIT
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    updatePublicThemeUI(currentTheme);
    loadProfileFromStorage();
    renderCart();

    // Inisialisasi status jadwal ruangan untuk tanggal awal
    let initialDate = document.getElementById('catalogRuanganDateFilter')?.value || new Date().toISOString().split('T')[0];
    fetchAllRoomSchedulesForDate(initialDate);

    // Hapus cart saat submit sukses dilakukan (di form aset)
    let formMandiri = document.getElementById('formPeminjamanMandiri');
    if (formMandiri) {
        formMandiri.addEventListener('submit', function(e) {
            if (loanCart.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal 1 aset ke keranjang terlebih dahulu!');
                return;
            }
            saveProfileToStorage();
            setTimeout(() => {
                localStorage.removeItem('stikes_loan_cart');
            }, 500);
        });
    }

    let formRuangan = document.getElementById('formBookingRuangan');
    if (formRuangan) {
        formRuangan.addEventListener('submit', function() {
            saveProfileToStorage();
        });
    }

    // Restore old selected ruangan jika ada
    let oldRuanganId = window.SPARTA_CONFIG?.oldRuanganId;
    if (oldRuanganId) {
        let allAvailableRuangans = getAvailableRuangans();
        let found = allAvailableRuangans.find(r => r.id == oldRuanganId);
        if (found) {
            chooseRuangan(found.id, found.nama, found.kode);
            proceedToStep2RuanganForm();
        }
    }

    // Hash navigation tab support (misal: /#tab-ruangan)
    if (window.location.hash === '#tab-ruangan') {
        let tabRuangan = document.getElementById('pills-ruangan-tab');
        if (tabRuangan) {
            tabRuangan.click();
            handleTabSwitch('ruangan');
        }
    }
});

