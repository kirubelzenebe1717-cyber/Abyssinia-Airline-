/**
 * Abyssinia Airline - Main JavaScript Application
 * Slogan: Fly Beyond Borders (Proudly Based in Addis Ababa, Ethiopia)
 * Handles flight search, interactive seat selection, booking flow,
 * online check-in, digital boarding pass generation, live radar status,
 * loyalty calculator, and currency switching.
 */

document.addEventListener('DOMContentLoaded', () => {
    // State Store
    const state = {
        airports: [],
        currentCurrency: 'USD',
        currencyRates: {
            'USD': { rate: 1.0, symbol: '$', code: 'USD' },
            'EUR': { rate: 0.92, symbol: '€', code: 'EUR' },
            'GBP': { rate: 0.78, symbol: '£', code: 'GBP' },
            'ETB': { rate: 125.0, symbol: 'ETB ', code: 'ETB' },
            'AED': { rate: 3.67, symbol: 'AED ', code: 'AED' }
        },
        selectedFlight: null,
        selectedSeat: '12A',
        selectedClass: 'Economy Class',
        currentStep: 1,
        activeBooking: null,
        seatMapLayout: [
            // Business (Cloud Nine) Rows 1-4, 1-2-1 layout
            { row: '01', type: 'business', seats: ['A', '', 'D', 'G', '', 'K'] },
            { row: '02', type: 'business', seats: ['A', '', 'D', 'G', '', 'K'] },
            { row: '03', type: 'business', seats: ['A', '', 'D', 'G', '', 'K'] },
            { row: '04', type: 'business', seats: ['A', '', 'D', 'G', '', 'K'] },
            // Economy Rows 10-18, 3-3-3 layout
            { row: '10', type: 'economy', seats: ['A', 'B', 'C', '', 'D', 'E', 'F', '', 'H', 'J', 'K'] },
            { row: '11', type: 'economy', seats: ['A', 'B', 'C', '', 'D', 'E', 'F', '', 'H', 'J', 'K'] },
            { row: '12', type: 'economy', seats: ['A', 'B', 'C', '', 'D', 'E', 'F', '', 'H', 'J', 'K'] },
            { row: '14', type: 'economy', seats: ['A', 'B', 'C', '', 'D', 'E', 'F', '', 'H', 'J', 'K'] },
            { row: '15', type: 'economy', seats: ['A', 'B', 'C', '', 'D', 'E', 'F', '', 'H', 'J', 'K'] },
            { row: '16', type: 'economy', seats: ['A', 'B', 'C', '', 'D', 'E', 'F', '', 'H', 'J', 'K'] },
            { row: '17', type: 'economy', seats: ['A', 'B', 'C', '', 'D', 'E', 'F', '', 'H', 'J', 'K'] },
            { row: '18', type: 'economy', seats: ['A', 'B', 'C', '', 'D', 'E', 'F', '', 'H', 'J', 'K'] }
        ],
        occupiedSeats: ['01A', '02D', '10C', '11E', '12F', '14A', '15H', '16K']
    };

    // Initialize
    fetchAirports();
    setupConsoleTabs();
    setupAutocomplete();
    setupCurrencySwitcher();
    setupFlightSearch();
    setupBookingModal();
    setupQuickForms();
    setupLoyaltyCalculator();
    setupNavbarScroll();

    // ==========================================
    // Fetch Airport Catalog
    // ==========================================
    async function fetchAirports() {
        try {
            const res = await fetch('api/airports.php');
            const data = await res.json();
            if (data.status === 'success') {
                state.airports = data.data;
                populateLoyaltyDropdowns();
            }
        } catch (err) {
            console.error('Failed to load airports', err);
        }
    }

    // ==========================================
    // Console Tabs Switching
    // ==========================================
    function setupConsoleTabs() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const panels = document.querySelectorAll('.tab-panel');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;
                tabBtns.forEach(b => b.classList.remove('active'));
                panels.forEach(p => p.classList.remove('active'));

                btn.classList.add('active');
                const panel = document.getElementById(`tab-${target}`);
                if (panel) panel.classList.add('active');
            });
        });

        // Quick sample PNR fill triggers
        document.querySelectorAll('.sample-pnr-fill').forEach(el => {
            el.addEventListener('click', (e) => {
                const pnr = e.target.textContent.trim();
                const input = document.getElementById(e.target.dataset.targetInput);
                if (input) input.value = pnr;
            });
        });
    }

    // ==========================================
    // Autocomplete for Origins / Destinations
    // ==========================================
    function setupAutocomplete() {
        const originInput = document.getElementById('search-origin');
        const originDropdown = document.getElementById('origin-dropdown');
        const destInput = document.getElementById('search-dest');
        const destDropdown = document.getElementById('dest-dropdown');
        const swapBtn = document.getElementById('btn-swap-airports');

        function bindDropdown(input, dropdown) {
            input.addEventListener('focus', () => renderOptions('', dropdown, input));
            input.addEventListener('input', (e) => renderOptions(e.target.value, dropdown, input));

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
        }

        function renderOptions(query, dropdown, input) {
            const q = query.toLowerCase().trim();
            const filtered = state.airports.filter(a =>
                a.code.toLowerCase().includes(q) ||
                a.city.toLowerCase().includes(q) ||
                a.country.toLowerCase().includes(q)
            );

            if (filtered.length === 0) {
                dropdown.innerHTML = '<div style="padding:14px;color:#94a3b8;font-size:0.85rem;">No matching airports found</div>';
                dropdown.classList.add('open');
                return;
            }

            dropdown.innerHTML = filtered.map(a => `
                <div class="autocomplete-item" data-code="${a.code}" data-display="${a.city} (${a.code})">
                    <div>
                        <div class="auto-city">${a.city}</div>
                        <div class="auto-country">${a.name}, ${a.country}</div>
                    </div>
                    <span class="auto-code">${a.code}</span>
                </div>
            `).join('');

            dropdown.classList.add('open');

            dropdown.querySelectorAll('.autocomplete-item').forEach(item => {
                item.addEventListener('click', () => {
                    input.value = item.dataset.display;
                    input.dataset.code = item.dataset.code;
                    dropdown.classList.remove('open');
                });
            });
        }

        if (originInput && originDropdown) bindDropdown(originInput, originDropdown);
        if (destInput && destDropdown) bindDropdown(destInput, destDropdown);

        if (swapBtn && originInput && destInput) {
            swapBtn.addEventListener('click', () => {
                const tempVal = originInput.value;
                const tempCode = originInput.dataset.code;
                originInput.value = destInput.value;
                originInput.dataset.code = destInput.dataset.code;
                destInput.value = tempVal;
                destInput.dataset.code = tempCode;
            });
        }
    }

    // ==========================================
    // Currency Switcher
    // ==========================================
    function setupCurrencySwitcher() {
        const select = document.getElementById('currency-select');
        if (!select) return;

        select.addEventListener('change', (e) => {
            state.currentCurrency = e.target.value;
            updatePricesAcrossPage();
            showToast(`Currency changed to ${state.currentCurrency}`);
        });
    }

    function formatPrice(amountUSD) {
        const cur = state.currencyRates[state.currentCurrency] || state.currencyRates['USD'];
        const converted = Math.round(amountUSD * cur.rate);
        return `${cur.symbol}${converted.toLocaleString()}`;
    }

    function updatePricesAcrossPage() {
        document.querySelectorAll('[data-usd]').forEach(el => {
            const usd = parseFloat(el.dataset.usd);
            if (!isNaN(usd)) {
                el.textContent = formatPrice(usd);
            }
        });
    }

    // ==========================================
    // Flight Search Execution
    // ==========================================
    function setupFlightSearch() {
        const form = document.getElementById('flight-search-form');
        const resultsSection = document.getElementById('results-section');
        const cardsList = document.getElementById('flight-cards-list');
        const resultsTitle = document.getElementById('results-route-title');

        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const originCode = document.getElementById('search-origin').dataset.code || 'ADD';
            const destCode = document.getElementById('search-dest').dataset.code || 'DXB';
            const depDate = document.getElementById('search-departure-date').value;
            const cabinClass = document.getElementById('search-cabin-class').value;

            cardsList.innerHTML = `
                <div style="padding: 40px; text-align: center; color: #cbd5e1;">
                    <div class="pulse" style="margin: 0 auto 16px; width: 14px; height: 14px;"></div>
                    <p style="font-size: 1.1rem; font-weight: 600;">Searching available Abyssinia Airline flights...</p>
                </div>
            `;
            resultsSection.classList.add('active');
            resultsSection.scrollIntoView({ behavior: 'smooth' });

            try {
                const res = await fetch(`api/flights.php?origin=${originCode}&destination=${destCode}&departure_date=${depDate}&cabin_class=${encodeURIComponent(cabinClass)}`);
                const json = await res.json();

                if (json.status === 'success' && json.data.length > 0) {
                    renderFlightCards(json.data, cabinClass);
                    resultsTitle.textContent = `${json.data[0].origin_city} (${originCode}) → ${json.data[0].destination_city} (${destCode})`;
                } else {
                    cardsList.innerHTML = `
                        <div style="padding: 40px; text-align: center; color: #cbd5e1; background: var(--navy-card); border-radius: 14px;">
                            <p style="font-size: 1.2rem; font-weight: 700; color: #f7d358;">No direct flights found on this date</p>
                            <p style="margin-top: 8px;">Try searching between Addis Ababa (ADD), Dubai (DXB), London (LHR), New York (JFK), or Paris (CDG).</p>
                        </div>
                    `;
                }
            } catch (err) {
                cardsList.innerHTML = `<div style="padding: 30px; text-align: center; color: #ef4444;">Error retrieving flight results. Please try again.</div>`;
            }
        });
    }

    function renderFlightCards(flights, cabinClass) {
        const cardsList = document.getElementById('flight-cards-list');
        cardsList.innerHTML = flights.map(flight => {
            let price = flight.price_economy;
            if (cabinClass.includes('Business')) price = flight.price_business;
            if (cabinClass.includes('First')) price = flight.price_first;

            return `
                <div class="flight-card" data-flight-id="${flight.id}">
                    <div class="flight-card-airline">
                        <div class="card-logo-mini">ABY</div>
                        <div>
                            <div class="card-flight-code">${flight.flight_number}</div>
                            <div class="card-aircraft-name">${flight.aircraft} • Gate ${flight.gate}</div>
                        </div>
                    </div>

                    <div class="flight-timeline">
                        <div class="time-box">
                            <div class="time-val">${flight.departure_time}</div>
                            <div class="time-city">${flight.origin_code}</div>
                        </div>

                        <div class="timeline-track">
                            <div class="track-duration">${flight.duration}</div>
                            <div class="track-line">
                                <span class="track-plane">✈</span>
                            </div>
                            <div class="track-stops">${flight.stops === 0 ? 'Non-Stop Direct' : flight.stops + ' Stop'}</div>
                        </div>

                        <div class="time-box">
                            <div class="time-val">${flight.arrival_time}</div>
                            <div class="time-city">${flight.destination_code}</div>
                        </div>
                    </div>

                    <div class="flight-card-fares">
                        <div class="fare-price-tag">
                            <div class="fare-from">One-Way from</div>
                            <div class="fare-amount" data-usd="${price}">${formatPrice(price)}</div>
                        </div>
                        <button class="btn-select-flight" onclick="window.startBooking(${JSON.stringify(flight).replace(/"/g, '&quot;')}, '${cabinClass}')">
                            Select Flight →
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // ==========================================
    // Interactive Seat Map & Booking Modal Flow
    // ==========================================
    window.startBooking = function(flight, cabinClass) {
        state.selectedFlight = flight;
        state.selectedClass = cabinClass || 'Economy Class';
        state.currentStep = 1;

        // Auto determine default seat
        state.selectedSeat = state.selectedClass.includes('Business') ? '02A' : '12A';

        updateBookingModalStep(1);
        populateFlightSummaryInModal();
        renderSeatMap();

        document.getElementById('booking-modal').classList.add('open');
    };

    function setupBookingModal() {
        const modal = document.getElementById('booking-modal');
        const closeBtn = document.getElementById('modal-close-btn');

        if (closeBtn) {
            closeBtn.addEventListener('click', () => modal.classList.remove('open'));
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('open');
        });

        // Step 1 -> Step 2
        document.getElementById('btn-to-step-2').addEventListener('click', () => {
            const name = document.getElementById('pass-name').value.trim();
            const email = document.getElementById('pass-email').value.trim();
            if (!name || !email) {
                showToast('Please enter your full name and email address.', 'error');
                return;
            }
            updateBookingModalStep(2);
        });

        // Step 2 -> Step 3
        document.getElementById('btn-to-step-3').addEventListener('click', () => {
            updateBookingModalStep(3);
        });

        // Step 3 -> Step 4 (Payment)
        document.getElementById('btn-to-step-4').addEventListener('click', () => {
            updateBookingModalStep(4);
            calculateBookingTotal();
        });

        // Back buttons
        document.querySelectorAll('.btn-step-back').forEach(btn => {
            btn.addEventListener('click', () => {
                const backTo = parseInt(btn.dataset.backStep, 10);
                updateBookingModalStep(backTo);
            });
        });

        // Confirm Payment & Book
        document.getElementById('btn-confirm-payment').addEventListener('click', async () => {
            await submitBookingForm();
        });

        // Payment method selection
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.payment-method-card').forEach(c => c.classList.remove('active'));
                card.classList.add('active');
            });
        });
    }

    function updateBookingModalStep(step) {
        state.currentStep = step;
        document.querySelectorAll('.booking-step-content').forEach(el => el.style.display = 'none');
        const currentContent = document.getElementById(`step-content-${step}`);
        if (currentContent) currentContent.style.display = 'block';

        // Update step badges
        document.querySelectorAll('.step-item').forEach(item => {
            const num = parseInt(item.dataset.step, 10);
            item.classList.remove('active', 'completed');
            if (num === step) item.classList.add('active');
            else if (num < step) item.classList.add('completed');
        });
    }

    function populateFlightSummaryInModal() {
        if (!state.selectedFlight) return;
        const fl = state.selectedFlight;
        document.getElementById('modal-flight-code').textContent = fl.flight_number;
        document.getElementById('modal-flight-route').textContent = `${fl.origin_city} (${fl.origin_code}) → ${fl.destination_city} (${fl.destination_code})`;
        document.getElementById('modal-cabin-class').textContent = state.selectedClass;
    }

    function renderSeatMap() {
        const container = document.getElementById('seatmap-fuselage-rows');
        if (!container) return;

        let html = '';
        state.seatMapLayout.forEach(rowInfo => {
            html += `<div class="cabin-section-title">${rowInfo.type === 'business' ? 'Cloud Nine Business Class' : 'Economy Class'} - Row ${rowInfo.row}</div>`;
            html += `<div class="seat-row"><span class="seat-row-label">${rowInfo.row}</span>`;

            rowInfo.seats.forEach(col => {
                if (col === '') {
                    html += `<span class="seat-aisle"></span>`;
                    return;
                }
                const seatCode = `${rowInfo.row}${col}`;
                const isOccupied = state.occupiedSeats.includes(seatCode);
                const isSelected = state.selectedSeat === seatCode;
                const isBiz = rowInfo.type === 'business';

                html += `
                    <button type="button" 
                            class="seat-btn ${isBiz ? 'business' : ''} ${isOccupied ? 'occupied' : ''} ${isSelected ? 'selected' : ''}" 
                            data-seat="${seatCode}" 
                            ${isOccupied ? 'disabled' : ''}
                            onclick="window.selectAircraftSeat('${seatCode}')">
                        ${col}
                    </button>
                `;
            });

            html += `</div>`;
        });

        container.innerHTML = html;
        document.getElementById('summary-selected-seat').textContent = state.selectedSeat;
    }

    window.selectAircraftSeat = function(seatCode) {
        state.selectedSeat = seatCode;
        renderSeatMap();
        showToast(`Seat ${seatCode} selected.`);
    };

    function calculateBookingTotal() {
        if (!state.selectedFlight) return;
        let baseFare = state.selectedFlight.price_economy;
        if (state.selectedClass.includes('Business')) baseFare = state.selectedFlight.price_business;
        if (state.selectedClass.includes('First')) baseFare = state.selectedFlight.price_first;

        const extraBags = parseInt(document.getElementById('extra-baggage-select').value, 10) || 0;
        const bagTotal = extraBags * 50;
        const taxes = Math.round(baseFare * 0.12);
        const grandTotal = baseFare + bagTotal + taxes;

        document.getElementById('pay-base-fare').textContent = formatPrice(baseFare);
        document.getElementById('pay-taxes').textContent = formatPrice(taxes);
        document.getElementById('pay-baggage').textContent = formatPrice(bagTotal);
        document.getElementById('pay-grand-total').textContent = formatPrice(grandTotal);
        document.getElementById('pay-grand-total').dataset.usd = grandTotal;
    }

    async function submitBookingForm() {
        const confirmBtn = document.getElementById('btn-confirm-payment');
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Processing Payment & Booking...';

        const payload = {
            flight_id: state.selectedFlight.id,
            passenger_name: document.getElementById('pass-name').value.trim(),
            passenger_email: document.getElementById('pass-email').value.trim(),
            passenger_phone: document.getElementById('pass-phone').value.trim(),
            passport_number: document.getElementById('pass-passport').value.trim(),
            cabin_class: state.selectedClass,
            seat_number: state.selectedSeat,
            meal_preference: document.getElementById('pass-meal').value,
            baggage_count: 2 + (parseInt(document.getElementById('extra-baggage-select').value, 10) || 0),
            total_amount: parseFloat(document.getElementById('pay-grand-total').dataset.usd || 450),
            currency: 'USD'
        };

        try {
            const res = await fetch('api/book.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();

            if (data.status === 'success') {
                state.activeBooking = data.data;
                showConfirmedETicket(data.data);
                showToast('Booking successfully confirmed!');
            } else {
                showToast(data.message || 'Booking failed.', 'error');
            }
        } catch (err) {
            showToast('Network error while booking. Please try again.', 'error');
        } finally {
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Pay & Confirm Reservation →';
        }
    }

    function showConfirmedETicket(booking) {
        updateBookingModalStep(5);
        document.getElementById('confirmed-pnr').textContent = booking.pnr;
        document.getElementById('confirmed-name').textContent = booking.passenger_name;
        document.getElementById('confirmed-flight').textContent = booking.flight_number;
        document.getElementById('confirmed-route').textContent = `${booking.origin_city} (${booking.origin_code}) → ${booking.destination_city} (${booking.destination_code})`;
        document.getElementById('confirmed-seat').textContent = booking.seat_number;
        document.getElementById('confirmed-time').textContent = `${booking.departure_time} Departs`;

        // Action to instant check-in
        const checkinNowBtn = document.getElementById('btn-eticket-checkin');
        if (checkinNowBtn) {
            checkinNowBtn.onclick = () => {
                document.getElementById('booking-modal').classList.remove('open');
                triggerOnlineCheckIn(booking.pnr, booking.passenger_name);
            };
        }
    }

    // ==========================================
    // Quick Forms: Manage Booking, Check-In, Status
    // ==========================================
    function setupQuickForms() {
        // Online Check-In Form
        const checkinForm = document.getElementById('checkin-form');
        if (checkinForm) {
            checkinForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const pnr = document.getElementById('checkin-pnr').value.trim();
                const lastName = document.getElementById('checkin-lastname').value.trim();
                triggerOnlineCheckIn(pnr, lastName);
            });
        }

        // Manage Booking Form
        const manageForm = document.getElementById('manage-booking-form');
        if (manageForm) {
            manageForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const pnr = document.getElementById('manage-pnr').value.trim();
                triggerManageBooking(pnr);
            });
        }

        // Flight Status Form
        const statusForm = document.getElementById('flight-status-form');
        if (statusForm) {
            statusForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const flightNum = document.getElementById('status-flight-number').value.trim();
                triggerFlightStatusSearch(flightNum);
            });
        }
    }

    // ==========================================
    // Online Check-In & Boarding Pass Generator
    // ==========================================
    async function triggerOnlineCheckIn(pnr, lastName = '') {
        showToast('Retrieving boarding details...');
        try {
            const res = await fetch(`api/checkin.php?pnr=${encodeURIComponent(pnr)}&last_name=${encodeURIComponent(lastName)}&action=complete_checkin`);
            const data = await res.json();

            if (data.status === 'success') {
                renderBoardingPass(data.data);
                document.getElementById('boarding-pass-modal').classList.add('open');
            } else {
                showToast(data.message || 'No booking found. Please check PNR.', 'error');
            }
        } catch (err) {
            showToast('Failed to retrieve boarding pass.', 'error');
        }
    }
    window.triggerOnlineCheckIn = triggerOnlineCheckIn;

    function renderBoardingPass(data) {
        const bp = data.boarding_pass || {};
        document.getElementById('bp-pnr').textContent = data.pnr;
        document.getElementById('bp-name').textContent = data.passenger_name;
        document.getElementById('bp-orig-code').textContent = data.origin_code;
        document.getElementById('bp-orig-city').textContent = data.origin_city;
        document.getElementById('bp-dest-code').textContent = data.destination_code;
        document.getElementById('bp-dest-city').textContent = data.destination_city;
        document.getElementById('bp-flight-num').textContent = data.flight_number;
        document.getElementById('bp-gate').textContent = bp.gate || data.gate || 'B14';
        document.getElementById('bp-terminal').textContent = bp.terminal || data.terminal || 'T2';
        document.getElementById('bp-seat').textContent = data.seat_number;
        document.getElementById('bp-boarding-time').textContent = bp.boarding_time || '07:45';
        document.getElementById('bp-class').textContent = data.cabin_class;
        document.getElementById('bp-group').textContent = bp.boarding_group || 'GROUP 1';
        document.getElementById('bp-eticket').textContent = bp.eticket_number || '071-29482103';

        // Draw Canvas Barcode
        drawBarcodeCanvas('bp-barcode-canvas', bp.barcode_payload || data.pnr);
        drawQRCodeCanvas('bp-qrcode-canvas', `${data.pnr}|${data.flight_number}|${data.seat_number}`);
    }

    function drawBarcodeCanvas(canvasId, text) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const width = canvas.width = 460;
        const height = canvas.height = 60;

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, width, height);
        ctx.fillStyle = '#051329';

        // Pseudo-random barcode pattern derived deterministically from text
        let x = 15;
        for (let i = 0; i < text.length * 4 && x < width - 20; i++) {
            const charCode = text.charCodeAt(i % text.length);
            const barW = ((charCode + i * 3) % 4) + 1;
            const spaceW = ((charCode + i * 7) % 3) + 2;
            ctx.fillRect(x, 6, barW, height - 12);
            x += barW + spaceW;
        }
    }

    function drawQRCodeCanvas(canvasId, text) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const size = canvas.width = canvas.height = 70;

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, size, size);
        ctx.fillStyle = '#051329';

        // QR corner targets
        function drawTarget(x, y) {
            ctx.fillRect(x, y, 16, 16);
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(x + 3, y + 3, 10, 10);
            ctx.fillStyle = '#051329';
            ctx.fillRect(x + 5, y + 5, 6, 6);
        }

        drawTarget(4, 4);
        drawTarget(size - 20, 4);
        drawTarget(4, size - 20);

        // Pattern dots
        for (let r = 0; r < 14; r++) {
            for (let c = 0; c < 14; c++) {
                if ((r < 5 && c < 5) || (r < 5 && c > 8) || (r > 8 && c < 5)) continue;
                if ((text.charCodeAt((r + c) % text.length) + r * 5 + c) % 3 === 0) {
                    ctx.fillRect(4 + c * 4, 4 + r * 4, 3, 3);
                }
            }
        }
    }

    // ==========================================
    // Manage Booking View & Actions
    // ==========================================
    async function triggerManageBooking(pnr) {
        showToast('Retrieving booking details...');
        try {
            const res = await fetch(`api/manage.php?pnr=${encodeURIComponent(pnr)}&action=view`);
            const data = await res.json();

            if (data.status === 'success') {
                renderManageBookingModal(data.data);
                document.getElementById('manage-modal').classList.add('open');
            } else {
                showToast(data.message || 'Booking not found.', 'error');
            }
        } catch (err) {
            showToast('Failed to load booking.', 'error');
        }
    }

    function renderManageBookingModal(b) {
        document.getElementById('mg-pnr').textContent = b.pnr;
        document.getElementById('mg-passenger').textContent = b.passenger_name;
        document.getElementById('mg-flight').textContent = b.flight_number;
        document.getElementById('mg-route').textContent = `${b.origin_city} (${b.origin_code}) → ${b.destination_city} (${b.destination_code})`;
        document.getElementById('mg-date').textContent = `${b.departure_time} Departs`;
        document.getElementById('mg-class').textContent = b.cabin_class;
        document.getElementById('mg-seat').textContent = b.seat_number;
        document.getElementById('mg-meal').textContent = b.meal_preference;
        document.getElementById('mg-baggage').textContent = `${b.baggage_count} Pieces (23kg standard)`;
        document.getElementById('mg-status').textContent = b.status;

        // Button action: Add Bag
        document.getElementById('btn-mg-add-bag').onclick = async () => {
            const res = await fetch('api/manage.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ pnr: b.pnr, action: 'add_baggage' })
            });
            const resJson = await res.json();
            if (resJson.status === 'success') {
                renderManageBookingModal(resJson.data);
                showToast(resJson.message);
            }
        };

        // Button action: Upgrade to Cloud Nine
        document.getElementById('btn-mg-upgrade').onclick = async () => {
            const res = await fetch('api/manage.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ pnr: b.pnr, action: 'upgrade_cabin' })
            });
            const resJson = await res.json();
            if (resJson.status === 'success') {
                renderManageBookingModal(resJson.data);
                showToast(resJson.message);
            }
        };

        // Button action: Cancel Booking
        document.getElementById('btn-mg-cancel').onclick = async () => {
            if (!confirm('Are you sure you want to cancel this booking?')) return;
            const res = await fetch('api/manage.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ pnr: b.pnr, action: 'cancel_booking' })
            });
            const resJson = await res.json();
            if (resJson.status === 'success') {
                renderManageBookingModal(resJson.data);
                showToast(resJson.message);
            }
        };
    }

    // ==========================================
    // Live Flight Status & Animated Radar
    // ==========================================
    async function triggerFlightStatusSearch(flightNumber) {
        showToast('Tracking flight telemetry...');
        try {
            const res = await fetch(`api/flight_status.php?flight_number=${encodeURIComponent(flightNumber)}`);
            const data = await res.json();

            if (data.status === 'success' && data.data.length > 0) {
                const fl = data.data[0];
                renderFlightRadar(fl);
                document.getElementById('flight-radar-section').scrollIntoView({ behavior: 'smooth' });
                showToast(`Flight ${fl.flight_number} located!`);
            } else {
                showToast('Flight not found in live system. Showing ET-101 flagship telemetry.', 'error');
                // Fetch default flight AB-101
                const defRes = await fetch(`api/flight_status.php?flight_number=AB-101`);
                const defData = await defRes.json();
                if (defData.data && defData.data.length > 0) renderFlightRadar(defData.data[0]);
            }
        } catch (err) {
            showToast('Flight tracking error.', 'error');
        }
    }

    function renderFlightRadar(flight) {
        const telem = flight.telemetry || {};
        document.getElementById('radar-flight-number').textContent = flight.flight_number;
        document.getElementById('radar-aircraft').textContent = flight.aircraft;
        document.getElementById('radar-route').textContent = `${flight.origin_city} (${flight.origin_code}) → ${flight.destination_city} (${flight.destination_code})`;
        document.getElementById('radar-status-badge').textContent = flight.status;
        document.getElementById('radar-status-badge').className = `radar-badge ${flight.status.toLowerCase().replace(' ', '-')}`;

        // Telemetry
        const progress = telem.progress_percent || 65;
        document.getElementById('radar-progress-fill').style.width = `${progress}%`;
        document.getElementById('radar-plane-pin').style.left = `${progress}%`;

        document.getElementById('radar-alt').textContent = telem.altitude || '38,000 ft';
        document.getElementById('radar-speed').textContent = telem.ground_speed || '895 km/h';
        document.getElementById('radar-gate').textContent = `Gate ${flight.gate} (${flight.terminal})`;
        document.getElementById('radar-weather').textContent = `${telem.weather_destination?.temp || '28°C'} ${telem.weather_destination?.condition || 'Clear'}`;
    }

    // ==========================================
    // Sheba Miles Loyalty Calculator
    // ==========================================
    function populateLoyaltyDropdowns() {
        const origSel = document.getElementById('calc-origin');
        const destSel = document.getElementById('calc-dest');
        if (!origSel || !destSel) return;

        const optionsHtml = state.airports.map(a => `<option value="${a.code}">${a.city} (${a.code})</option>`).join('');
        origSel.innerHTML = optionsHtml;
        destSel.innerHTML = optionsHtml;
        origSel.value = 'ADD';
        destSel.value = 'DXB';
    }

    function setupLoyaltyCalculator() {
        const calcBtn = document.getElementById('btn-calc-miles');
        if (!calcBtn) return;

        calcBtn.addEventListener('click', async () => {
            const orig = document.getElementById('calc-origin').value;
            const dest = document.getElementById('calc-dest').value;
            const cabin = document.getElementById('calc-class').value;

            try {
                const res = await fetch(`api/loyalty.php?origin=${orig}&destination=${dest}&cabin_class=${encodeURIComponent(cabin)}`);
                const data = await res.json();
                if (data.status === 'success') {
                    document.getElementById('calc-miles-val').textContent = data.data.miles_earned.toLocaleString();
                    document.getElementById('calc-tier-val').textContent = data.data.tier_points_earned.toLocaleString();
                    showToast(`Earn ${data.data.miles_earned} Sheba Miles on this journey!`);
                }
            } catch (err) {
                console.error(err);
            }
        });
    }

    // ==========================================
    // Modals Close setup
    // ==========================================
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        const closeBtn = overlay.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => overlay.classList.remove('open'));
        }
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) overlay.classList.remove('open');
        });
    });

    // ==========================================
    // Sticky Nav on Scroll
    // ==========================================
    function setupNavbarScroll() {
        const nav = document.querySelector('.main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        });
    }

    // ==========================================
    // Toast Notification System
    // ==========================================
    function showToast(message, type = 'info') {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <span>✈</span>
            <div>${message}</div>
        `;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
    window.showToast = showToast;
});
