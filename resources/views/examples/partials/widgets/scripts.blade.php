<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    (function () {
        function $(id) { return document.getElementById(id); }
        function clamp(n, min, max) { return Math.min(max, Math.max(min, n)); }
        function toNum(v) {
            var n = Number(v);
            return Number.isFinite(n) ? n : 0;
        }
        function money(n, currency) {
            var v = toNum(n);
            var sign = v < 0 ? '-' : '';
            v = Math.abs(v);
            return sign + currency + v.toLocaleString(undefined, { maximumFractionDigits: 2, minimumFractionDigits: 2 });
        }

        // ROI
        function calcRoi() {
            var initial = toNum($('td-roi-invest').value);
            var finalValue = toNum($('td-roi-final').value);
            var years = toNum($('td-roi-years').value);

            if (initial <= 0) {
                $('td-roi-roi').textContent = 'ROI: —';
                $('td-roi-profit').textContent = 'Profit: —';
                $('td-roi-cagr').textContent = 'CAGR: —';
                return;
            }

            var profit = finalValue - initial;
            var roi = (profit / initial) * 100;

            var cagr = null;
            if (years > 0 && finalValue > 0) {
                cagr = (Math.pow(finalValue / initial, 1 / years) - 1) * 100;
            }

            $('td-roi-roi').textContent = 'ROI: ' + roi.toFixed(2) + '%';
            $('td-roi-profit').textContent = 'Profit: ' + money(profit, '');
            $('td-roi-cagr').textContent = 'CAGR: ' + (cagr === null ? '—' : cagr.toFixed(2) + '%');
        }

        // EMI
        function calcEmi() {
            var principal = toNum($('td-emi-principal').value);
            var annualRate = toNum($('td-emi-rate').value);
            var months = Math.max(1, Math.floor(toNum($('td-emi-months').value)));

            if (principal <= 0) {
                $('td-emi-monthly').textContent = 'Monthly EMI: —';
                $('td-emi-total').textContent = 'Total pay: —';
                $('td-emi-interest').textContent = 'Total interest: —';
                return;
            }

            var r = (annualRate / 100) / 12;
            var emi = 0;
            if (r === 0) {
                emi = principal / months;
            } else {
                var pow = Math.pow(1 + r, months);
                emi = (principal * r * pow) / (pow - 1);
            }

            var totalPay = emi * months;
            var totalInterest = totalPay - principal;

            $('td-emi-monthly').textContent = 'Monthly EMI: ' + money(emi, '');
            $('td-emi-total').textContent = 'Total pay: ' + money(totalPay, '');
            $('td-emi-interest').textContent = 'Total interest: ' + money(totalInterest, '');
        }

        // XKCD (via same-origin proxy)
        async function loadXkcd(id) {
            $('td-xkcd-meta').textContent = 'Loading…';
            $('td-xkcd-title').textContent = '—';
            $('td-xkcd-alt').textContent = '—';
            $('td-xkcd-img').style.display = 'none';
            $('td-xkcd-empty').style.display = '';

            var url = id ? ('{{ route('tyro-dashboard.examples.widgets.xkcd', ['id' => 1]) }}'.replace('/1', '/' + encodeURIComponent(String(id))))
                         : '{{ route('tyro-dashboard.examples.widgets.xkcd') }}';

            try {
                var res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                var data = await res.json();
                if (!res.ok) throw new Error(data && data.error ? data.error : 'Failed');

                $('td-xkcd-meta').textContent = '#' + data.num + ' • ' + [data.year, data.month, data.day].join('-');
                $('td-xkcd-title').textContent = data.safe_title || data.title || 'XKCD';
                $('td-xkcd-alt').textContent = data.alt || '';

                if (data.img) {
                    $('td-xkcd-img').src = data.img;
                    $('td-xkcd-img').alt = data.alt || $('td-xkcd-title').textContent;
                    $('td-xkcd-img').style.display = '';
                    $('td-xkcd-empty').style.display = 'none';
                }
            } catch (e) {
                $('td-xkcd-meta').textContent = 'Failed to load';
                $('td-xkcd-title').textContent = 'XKCD';
                $('td-xkcd-alt').textContent = String(e && e.message ? e.message : e);
            }
        }

        // Open-Meteo helpers
        async function geocodeByName(name) {
            var url = 'https://geocoding-api.open-meteo.com/v1/search?name=' + encodeURIComponent(name) + '&count=1&language=en&format=json';
            var res = await fetch(url);
            var data = await res.json();
            if (!data || !data.results || !data.results[0]) throw new Error('Location not found');
            return data.results[0];
        }

        async function reverseGeocode(lat, lon) {
            var url = 'https://geocoding-api.open-meteo.com/v1/reverse?latitude=' + encodeURIComponent(lat) + '&longitude=' + encodeURIComponent(lon) + '&count=1&language=en&format=json';
            var res = await fetch(url);
            var data = await res.json();
            if (data && data.results && data.results[0]) return data.results[0];
            return { name: lat.toFixed(4) + ',' + lon.toFixed(4), latitude: lat, longitude: lon };
        }

        async function fetchForecast(lat, lon) {
            var url = 'https://api.open-meteo.com/v1/forecast?latitude=' + encodeURIComponent(lat) + '&longitude=' + encodeURIComponent(lon)
                + '&current=temperature_2m,relative_humidity_2m,wind_speed_10m'
                + '&daily=temperature_2m_max,temperature_2m_min,precipitation_sum'
                + '&forecast_days=7&timezone=auto';
            var res = await fetch(url);
            if (!res.ok) throw new Error('Weather API error');
            return await res.json();
        }

        async function checkWeatherNow(place) {
            $('td-weather-temp').textContent = 'Temp: Loading…';
            $('td-weather-humidity').textContent = 'Humidity: —';
            $('td-weather-wind').textContent = 'Wind: —';
            $('td-weather-place').textContent = '—';

            try {
                var forecast = await fetchForecast(place.latitude, place.longitude);
                var c = forecast.current || {};
                $('td-weather-temp').textContent = 'Temp: ' + (c.temperature_2m != null ? (c.temperature_2m + '°C') : '—');
                $('td-weather-humidity').textContent = 'Humidity: ' + (c.relative_humidity_2m != null ? (c.relative_humidity_2m + '%') : '—');
                $('td-weather-wind').textContent = 'Wind: ' + (c.wind_speed_10m != null ? (c.wind_speed_10m + ' km/h') : '—');
                $('td-weather-place').textContent = (place.name || '—') + (place.country ? (', ' + place.country) : '');
            } catch (e) {
                $('td-weather-temp').textContent = 'Temp: —';
                $('td-weather-humidity').textContent = 'Humidity: —';
                $('td-weather-wind').textContent = 'Wind: —';
                $('td-weather-place').textContent = String(e && e.message ? e.message : e);
            }
        }

        async function loadForecastTable(place) {
            $('td-forecast-place').textContent = 'Loading…';
            $('td-forecast-meta').textContent = '—';
            var tbody = $('td-forecast-table').querySelector('tbody');
            tbody.innerHTML = '<tr><td colspan="4" style="color: var(--muted-foreground);">Loading…</td></tr>';

            try {
                var forecast = await fetchForecast(place.latitude, place.longitude);
                var daily = forecast.daily || {};
                var time = daily.time || [];
                var tmin = daily.temperature_2m_min || [];
                var tmax = daily.temperature_2m_max || [];
                var precip = daily.precipitation_sum || [];

                $('td-forecast-place').textContent = (place.name || '—') + (place.country ? (', ' + place.country) : '');
                $('td-forecast-meta').textContent = (forecast.timezone || '—');

                tbody.innerHTML = '';
                for (var i = 0; i < time.length; i++) {
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td>' + String(time[i]) + '</td>' +
                        '<td>' + (tmin[i] != null ? (Number(tmin[i]).toFixed(1) + '°C') : '—') + '</td>' +
                        '<td>' + (tmax[i] != null ? (Number(tmax[i]).toFixed(1) + '°C') : '—') + '</td>' +
                        '<td>' + (precip[i] != null ? (Number(precip[i]).toFixed(1) + ' mm') : '—') + '</td>';
                    tbody.appendChild(tr);
                }
                if (!time.length) {
                    tbody.innerHTML = '<tr><td colspan="4" style="color: var(--muted-foreground);">No data returned.</td></tr>';
                }
            } catch (e) {
                $('td-forecast-place').textContent = '—';
                $('td-forecast-meta').textContent = 'Failed';
                tbody.innerHTML = '<tr><td colspan="4" style="color: var(--muted-foreground);">' + String(e && e.message ? e.message : e) + '</td></tr>';
            }
        }

        function getBrowserLocation() {
            return new Promise(function (resolve, reject) {
                if (!navigator.geolocation) return reject(new Error('Geolocation is not supported'));
                navigator.geolocation.getCurrentPosition(function (pos) {
                    resolve({ lat: pos.coords.latitude, lon: pos.coords.longitude });
                }, function () {
                    reject(new Error('Location permission denied'));
                }, { enableHighAccuracy: true, timeout: 8000, maximumAge: 300000 });
            });
        }

        // Restaurants map
        function renderRestaurantMap(query) {
            var iframe = $('td-rest-map');
            var empty = $('td-rest-empty');
            var src = 'https://www.google.com/maps?q=' + encodeURIComponent(query) + '&output=embed';
            iframe.src = src;
            iframe.style.display = '';
            empty.style.display = 'none';
        }

        // Stocks
        async function loadStock(symbol) {
            $('td-stock-last').textContent = 'Last: Loading…';
            $('td-stock-range').textContent = 'Range: —';
            $('td-stock-volume').textContent = 'Vol: —';
            $('td-stock-meta').textContent = '—';

            var url = '{{ route('tyro-dashboard.examples.widgets.stocks', ['symbol' => 'aapl.us']) }}'.replace('aapl.us', encodeURIComponent(symbol));
            try {
                var res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                var data = await res.json();
                if (!res.ok) throw new Error(data && data.error ? data.error : 'Failed');

                $('td-stock-last').textContent = 'Last: ' + (data.close != null ? data.close : '—');
                $('td-stock-range').textContent = 'Range: ' + (data.low != null && data.high != null ? (data.low + ' – ' + data.high) : '—');
                $('td-stock-volume').textContent = 'Vol: ' + (data.volume != null ? String(data.volume) : '—');
                $('td-stock-meta').textContent = (data.symbol || symbol) + (data.date ? (' • ' + data.date) : '') + (data.time ? (' ' + data.time) : '');
            } catch (e) {
                $('td-stock-last').textContent = 'Last: —';
                $('td-stock-range').textContent = 'Range: —';
                $('td-stock-volume').textContent = 'Vol: —';
                $('td-stock-meta').textContent = String(e && e.message ? e.message : e);
            }
        }

        // Images
        var TD_IMG_KEYS = {
            unsplash: 'td-img-unsplash-key',
            pixabay: 'td-img-pixabay-key'
        };

        function getStoredKey(storageKey) {
            try {
                return String(localStorage.getItem(storageKey) || '').trim();
            } catch (e) {
                return '';
            }
        }

        function setStoredKey(storageKey, value) {
            try {
                var v = String(value || '').trim();
                if (!v) localStorage.removeItem(storageKey);
                else localStorage.setItem(storageKey, v);
            } catch (e) {
                // ignore
            }
        }

        function promptUnsplashKey() {
            var unsplash = prompt('Unsplash Access Key (optional):', getStoredKey(TD_IMG_KEYS.unsplash));
            if (unsplash !== null) setStoredKey(TD_IMG_KEYS.unsplash, unsplash);
        }

        function promptPixabayKey() {
            var pixabay = prompt('Pixabay API Key (optional):', getStoredKey(TD_IMG_KEYS.pixabay));
            if (pixabay !== null) setStoredKey(TD_IMG_KEYS.pixabay, pixabay);
        }

        function clearImages() {
            var grid = $('td-img-grid');
            if (grid) grid.innerHTML = '';
        }

        function addImage(url, alt, href) {
            if (!$('td-img-grid')) return;
            var wrap = document.createElement(href ? 'a' : 'div');
            if (href) {
                wrap.href = href;
                wrap.target = '_blank';
                wrap.rel = 'noopener';
            }
            wrap.style.border = '1px solid var(--border)';
            wrap.style.borderRadius = '10px';
            wrap.style.overflow = 'hidden';
            wrap.style.background = 'var(--muted)';

            var img = document.createElement('img');
            img.src = url;
            img.alt = alt || 'Result';
            img.loading = 'lazy';
            img.style.display = 'block';
            img.style.width = '100%';
            img.style.height = '160px';
            img.style.objectFit = 'cover';

            wrap.appendChild(img);
            $('td-img-grid').appendChild(wrap);
        }

        function addImageMessage(text) {
            if (!$('td-img-grid')) return;
            var note = document.createElement('div');
            note.style.gridColumn = '1 / -1';
            note.style.border = '1px solid var(--border)';
            note.style.borderRadius = '10px';
            note.style.padding = '0.875rem 1rem';
            note.style.background = 'var(--muted)';
            note.style.color = 'var(--muted-foreground)';
            note.textContent = text;
            $('td-img-grid').appendChild(note);
        }

        async function loadImages(query, provider) {
            clearImages();
            var openBtn = $('td-img-open');
            openBtn.style.display = 'none';

            query = (query || '').trim();
            if (!query) return;

            if (provider === 'pixabay') {
                openBtn.style.display = '';
                openBtn.href = 'https://pixabay.com/images/search/' + encodeURIComponent(query) + '/';
                openBtn.onclick = function () { window.open(openBtn.href, '_blank', 'noopener'); return false; };

                var apiKey = getStoredKey(TD_IMG_KEYS.pixabay);
                if (!apiKey) {
                    addImageMessage('Pixabay key not set. Click “Pixabay Key” to enable real search, or use “Open results”.');
                    return;
                }

                try {
                    var url = 'https://pixabay.com/api/?key=' + encodeURIComponent(apiKey)
                        + '&q=' + encodeURIComponent(query)
                        + '&image_type=photo&per_page=8&safesearch=true';
                    var res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    var data = await res.json();
                    if (!res.ok) throw new Error((data && data.message) ? data.message : 'Pixabay API error');

                    var hits = (data && data.hits) ? data.hits : [];
                    if (!hits.length) {
                        addImageMessage('No Pixabay results found.');
                        return;
                    }

                    hits.slice(0, 8).forEach(function (h) {
                        addImage(h.webformatURL || h.previewURL, h.tags || 'Pixabay image', h.pageURL || openBtn.href);
                    });
                } catch (e) {
                    addImageMessage('Pixabay search failed: ' + String(e && e.message ? e.message : e));
                }
                return;
            }

            // Unsplash
            openBtn.style.display = '';
            openBtn.href = 'https://unsplash.com/s/photos/' + encodeURIComponent(query);
            openBtn.onclick = function () { window.open(openBtn.href, '_blank', 'noopener'); return false; };

            var unsplashKey = getStoredKey(TD_IMG_KEYS.unsplash);
            if (unsplashKey) {
                try {
                    var u = 'https://api.unsplash.com/search/photos?query=' + encodeURIComponent(query)
                        + '&per_page=8&client_id=' + encodeURIComponent(unsplashKey);
                    var ures = await fetch(u, { headers: { 'Accept': 'application/json' } });
                    var udata = await ures.json();
                    if (!ures.ok) {
                        throw new Error((udata && (udata.errors || udata.error)) ? (udata.errors ? udata.errors.join(', ') : udata.error) : 'Unsplash API error');
                    }

                    var results = (udata && udata.results) ? udata.results : [];
                    if (!results.length) {
                        addImageMessage('No Unsplash results found.');
                        return;
                    }

                    results.slice(0, 8).forEach(function (r) {
                        var imgUrl = (r.urls && (r.urls.small || r.urls.regular)) ? (r.urls.small || r.urls.regular) : null;
                        if (!imgUrl) return;
                        var alt = (r.alt_description || r.description || 'Unsplash image');
                        var href = (r.links && r.links.html) ? r.links.html : openBtn.href;
                        addImage(imgUrl, alt, href);
                    });
                    return;
                } catch (e) {
                    addImageMessage('Unsplash API search failed, using fallback images.');
                }
            }

            for (var i = 0; i < 8; i++) {
                var fallbackUrl = 'https://source.unsplash.com/600x400/?' + encodeURIComponent(query) + '&sig=' + i;
                addImage(fallbackUrl, 'Unsplash image', openBtn.href);
            }
        }

        // Invoice
        function invoiceRowTemplate(row) {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td><input class="form-input" type="text" value="' + (row.item || '') + '" placeholder="Item description" data-inv="item" /></td>' +
                '<td><input class="form-input" type="number" min="0" step="1" value="' + (row.qty != null ? row.qty : 1) + '" data-inv="qty" /></td>' +
                '<td><input class="form-input" type="number" min="0" step="0.01" value="' + (row.unit != null ? row.unit : 0) + '" data-inv="unit" /></td>' +
                '<td style="text-align:right; font-weight: 700;" data-inv="total">—</td>' +
                '<td style="text-align:right;"><button type="button" class="btn btn-ghost btn-sm" data-inv="remove">×</button></td>';
            return tr;
        }

        function getInvoiceState() {
            var currency = $('td-inv-currency').value || '$';
            var taxRate = toNum($('td-inv-tax-rate').value);
            var discount = toNum($('td-inv-discount-amt').value);
            var rows = [];
            $('td-inv-table').querySelectorAll('tbody tr').forEach(function (tr) {
                rows.push({
                    item: (tr.querySelector('[data-inv="item"]').value || '').trim(),
                    qty: toNum(tr.querySelector('[data-inv="qty"]').value),
                    unit: toNum(tr.querySelector('[data-inv="unit"]').value)
                });
            });
            return {
                number: $('td-inv-number').value,
                date: $('td-inv-date').value,
                billTo: $('td-inv-billto').value,
                currency: currency,
                taxRate: taxRate,
                discount: discount,
                lines: rows
            };
        }

        function renderInvoice() {
            var state = getInvoiceState();
            var currency = state.currency;
            var subtotal = 0;

            $('td-inv-table').querySelectorAll('tbody tr').forEach(function (tr) {
                var qty = toNum(tr.querySelector('[data-inv="qty"]').value);
                var unit = toNum(tr.querySelector('[data-inv="unit"]').value);
                qty = clamp(qty, 0, 1e9);
                unit = clamp(unit, 0, 1e12);
                var lineTotal = qty * unit;
                subtotal += lineTotal;
                tr.querySelector('[data-inv="total"]').textContent = money(lineTotal, currency);
            });

            var tax = subtotal * (state.taxRate / 100);
            var discount = clamp(state.discount, 0, 1e12);
            var total = Math.max(0, subtotal + tax - discount);

            $('td-inv-subtotal').textContent = money(subtotal, currency);
            $('td-inv-tax').textContent = money(tax, currency);
            $('td-inv-discount').textContent = money(discount, currency);
            $('td-inv-total').textContent = money(total, currency);

            var exportData = {
                invoice: {
                    number: state.number,
                    date: state.date,
                    billTo: state.billTo,
                    currency: currency,
                    taxRate: state.taxRate,
                    discount: discount,
                    subtotal: subtotal,
                    tax: tax,
                    total: total,
                    lines: state.lines
                }
            };
            $('td-inv-json').value = JSON.stringify(exportData, null, 2);
        }

        function addInvoiceLine(row) {
            var tr = invoiceRowTemplate(row || { item: '', qty: 1, unit: 0 });
            $('td-inv-table').querySelector('tbody').appendChild(tr);
            renderInvoice();
        }

        // Password Generator
        function buildPasswordSets() {
            var sets = [];
            var upper = $('td-password-uppercase');
            var lower = $('td-password-lowercase');
            var nums = $('td-password-numbers');
            var syms = $('td-password-symbols');

            if (upper && upper.checked) sets.push('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            if (lower && lower.checked) sets.push('abcdefghijklmnopqrstuvwxyz');
            if (nums && nums.checked) sets.push('0123456789');
            if (syms && syms.checked) sets.push('!@#$%^&*()-_=+[]{};:,.?/');

            return sets;
        }

        function randomInt(maxExclusive) {
            maxExclusive = Math.floor(maxExclusive);
            if (!(maxExclusive > 0)) return 0;

            if (window.crypto && window.crypto.getRandomValues) {
                var range = 0x100000000;
                var limit = Math.floor(range / maxExclusive) * maxExclusive;
                var x = new Uint32Array(1);
                do {
                    window.crypto.getRandomValues(x);
                } while (x[0] >= limit);
                return x[0] % maxExclusive;
            }

            return Math.floor(Math.random() * maxExclusive);
        }

        function pickOne(str) {
            return str.charAt(randomInt(str.length));
        }

        function shuffleArray(arr) {
            for (var i = arr.length - 1; i > 0; i--) {
                var j = randomInt(i + 1);
                var tmp = arr[i];
                arr[i] = arr[j];
                arr[j] = tmp;
            }
            return arr;
        }

        function generatePassword(length, sets) {
            var all = sets.join('');
            if (!all) return '';

            var out = [];
            for (var i = 0; i < sets.length && out.length < length; i++) {
                out.push(pickOne(sets[i]));
            }
            while (out.length < length) {
                out.push(pickOne(all));
            }
            return shuffleArray(out).join('');
        }

        async function copyToClipboard(text) {
            if (!text) return false;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                try {
                    await navigator.clipboard.writeText(text);
                    return true;
                } catch (e) {
                    // fall through
                }
            }

            try {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.left = '-9999px';
                document.body.appendChild(ta);
                ta.focus();
                ta.select();
                var ok = document.execCommand('copy');
                document.body.removeChild(ta);
                return ok;
            } catch (e2) {
                return false;
            }
        }

        function initPasswordGenerator() {
            var gen = $('td-password-generate');
            var out = $('td-password-output');
            if (!gen || !out) return;

            gen.addEventListener('click', function () {
                var lengthEl = $('td-password-length');
                var qtyEl = $('td-password-quantity');

                var length = clamp(toNum(lengthEl ? lengthEl.value : 16), 4, 128);
                var quantity = clamp(Math.floor(toNum(qtyEl ? qtyEl.value : 1)), 1, 10);
                var sets = buildPasswordSets();

                if (sets.length === 0) {
                    out.value = '';
                    alert('Select at least one character type.');
                    return;
                }

                var lines = [];
                for (var i = 0; i < quantity; i++) {
                    lines.push(generatePassword(length, sets));
                }
                out.value = lines.join('\n');
            });

            var clearBtn = $('td-password-clear');
            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    out.value = '';
                });
            }

            var copyBtn = $('td-password-copy');
            if (copyBtn) {
                copyBtn.addEventListener('click', async function () {
                    var text = (out.value || '').trim();
                    if (!text) return;

                    var prev = copyBtn.textContent;
                    var ok = await copyToClipboard(text);
                    copyBtn.textContent = ok ? 'Copied' : 'Copy failed';
                    setTimeout(function () { copyBtn.textContent = prev; }, 1200);
                });
            }
        }

        // QR Generator
        function normalizeHexColor(value, fallback) {
            var v = String(value || '').trim();
            if (/^#[0-9a-fA-F]{6}$/.test(v)) return v;
            return fallback;
        }

        function initQrGenerator() {
            var gen = $('td-qr-generate');
            var outEl = $('td-qr-output');
            var placeholder = $('td-qr-placeholder');
            var download = $('td-qr-download');
            if (!gen || !outEl || !placeholder || !download) return;

            function setEmpty() {
                outEl.style.display = 'none';
                outEl.innerHTML = '';
                placeholder.style.display = '';
                download.disabled = true;
            }

            async function renderQr() {
                var contentEl = $('td-qr-content');
                var sizeEl = $('td-qr-size');
                var errEl = $('td-qr-error');
                var fgEl = $('td-qr-foreground');
                var bgEl = $('td-qr-background');

                var content = String(contentEl ? contentEl.value : '').trim();
                if (!content) {
                    setEmpty();
                    alert('Enter some content to encode.');
                    return;
                }

                if (!window.QRCode) {
                    alert('QR library failed to load.');
                    return;
                }

                var size = clamp(toNum(sizeEl ? sizeEl.value : 256), 96, 1024);
                var ec = String(errEl ? errEl.value : 'M');
                var fg = normalizeHexColor(fgEl ? fgEl.value : '#000000', '#000000');
                var bg = normalizeHexColor(bgEl ? bgEl.value : '#ffffff', '#ffffff');

                placeholder.style.display = 'none';
                outEl.style.display = '';

                try {
                    outEl.innerHTML = '';

                    new window.QRCode(outEl, {
                        text: content,
                        width: size,
                        height: size,
                        colorDark: fg,
                        colorLight: bg,
                        correctLevel: (window.QRCode.CorrectLevel && window.QRCode.CorrectLevel[ec]) ? window.QRCode.CorrectLevel[ec] : window.QRCode.CorrectLevel.M
                    });

                    var innerCanvas = outEl.querySelector('canvas');
                    if (innerCanvas) {
                        innerCanvas.style.maxWidth = '100%';
                        innerCanvas.style.height = 'auto';
                        innerCanvas.style.display = 'block';
                    }
                    var innerImg = outEl.querySelector('img');
                    if (innerImg) {
                        innerImg.style.maxWidth = '100%';
                        innerImg.style.height = 'auto';
                        innerImg.style.display = 'block';
                    }
                    
                    download.disabled = false;
                } catch (e) {
                    setEmpty();
                    alert('Could not generate QR for this content: ' + (e.message || e));
                }
            }

            gen.addEventListener('click', renderQr);

            var clearBtn = $('td-qr-clear');
            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    var contentEl = $('td-qr-content');
                    if (contentEl) contentEl.value = '';
                    setEmpty();
                });
            }

            download.addEventListener('click', function () {
                if (download.disabled) return;
                try {
                    var innerCanvas = outEl.querySelector('canvas');
                    var innerImg = outEl.querySelector('img');
                    var url = null;
                    if (innerCanvas && innerCanvas.toDataURL) {
                        url = innerCanvas.toDataURL('image/png');
                    } else if (innerImg && innerImg.src) {
                        url = innerImg.src;
                    }
                    if (!url) throw new Error('No QR image available');
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'qr-code.png';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } catch (e) {
                    alert('Download failed.');
                }
            });
        }

        function initInvoice() {
            var dateInput = $('td-inv-date');
            if (dateInput && !dateInput.value) {
                var d = new Date();
                var mm = String(d.getMonth() + 1).padStart(2, '0');
                var dd = String(d.getDate()).padStart(2, '0');
                dateInput.value = d.getFullYear() + '-' + mm + '-' + dd;
            }

            addInvoiceLine({ item: 'Dashboard setup', qty: 1, unit: 250 });
            addInvoiceLine({ item: 'Monthly maintenance', qty: 2, unit: 80 });

            $('td-inv-table').addEventListener('input', function (e) {
                if (!e.target || !e.target.matches('[data-inv]')) return;
                renderInvoice();
            });

            $('td-inv-table').addEventListener('click', function (e) {
                var btn = e.target && e.target.closest('[data-inv="remove"]');
                if (!btn) return;
                var tr = btn.closest('tr');
                if (tr) tr.remove();
                renderInvoice();
            });

            ['td-inv-tax-rate', 'td-inv-discount-amt', 'td-inv-currency', 'td-inv-number', 'td-inv-date', 'td-inv-billto'].forEach(function (id) {
                var el = $(id);
                if (el) el.addEventListener('input', renderInvoice);
                if (el) el.addEventListener('change', renderInvoice);
            });

            $('td-inv-add').addEventListener('click', function () { addInvoiceLine({ item: '', qty: 1, unit: 0 }); });
            $('td-inv-print').addEventListener('click', function () { window.print(); });
            $('td-inv-export').addEventListener('click', async function () {
                renderInvoice();
                try {
                    await navigator.clipboard.writeText($('td-inv-json').value);
                } catch (e) {
                    // ignore if clipboard isn't available
                }
            });

            renderInvoice();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // ROI
            $('td-roi-calc').addEventListener('click', calcRoi);
            $('td-roi-reset').addEventListener('click', function () {
                $('td-roi-invest').value = 1000;
                $('td-roi-final').value = 1450;
                $('td-roi-years').value = 2;
                calcRoi();
            });
            ['td-roi-invest', 'td-roi-final', 'td-roi-years'].forEach(function (id) {
                $(id).addEventListener('input', calcRoi);
            });
            calcRoi();

            // EMI
            $('td-emi-calc').addEventListener('click', calcEmi);
            $('td-emi-reset').addEventListener('click', function () {
                $('td-emi-principal').value = 500000;
                $('td-emi-rate').value = 10.5;
                $('td-emi-months').value = 60;
                calcEmi();
            });
            ['td-emi-principal', 'td-emi-rate', 'td-emi-months'].forEach(function (id) {
                $(id).addEventListener('input', calcEmi);
            });
            calcEmi();

            // QR + Password
            initQrGenerator();
            initPasswordGenerator();

            // BMI
            (function initBmi() {
                function bmiCategory(bmi) {
                    if (bmi < 18.5) return 'Underweight';
                    if (bmi < 25) return 'Normal';
                    if (bmi < 30) return 'Overweight';
                    return 'Obese';
                }

                function calcBmi() {
                    var hCm = toNum($('td-bmi-height').value);
                    var wKg = toNum($('td-bmi-weight').value);
                    if (hCm <= 0 || wKg <= 0) {
                        $('td-bmi-value').textContent = 'BMI: —';
                        $('td-bmi-category').textContent = 'Category: —';
                        return;
                    }

                    var hM = hCm / 100;
                    var bmi = wKg / (hM * hM);
                    if (!Number.isFinite(bmi)) {
                        $('td-bmi-value').textContent = 'BMI: —';
                        $('td-bmi-category').textContent = 'Category: —';
                        return;
                    }

                    $('td-bmi-value').textContent = 'BMI: ' + bmi.toFixed(1);
                    $('td-bmi-category').textContent = 'Category: ' + bmiCategory(bmi);
                }

                $('td-bmi-calc').addEventListener('click', calcBmi);
                $('td-bmi-reset').addEventListener('click', function () {
                    $('td-bmi-height').value = 175;
                    $('td-bmi-weight').value = 70;
                    calcBmi();
                });
                ['td-bmi-height', 'td-bmi-weight'].forEach(function (id) {
                    $(id).addEventListener('input', calcBmi);
                });
                calcBmi();
            })();

            // Flight tracker
            (function initFlightTracker() {
                var proxyBase = '{{ route('tyro-dashboard.examples.widgets.flights') }}';

                function setStatus(text) {
                    $('td-flight-status').textContent = text;
                }

                function fmt(num, digits) {
                    if (num === null || typeof num === 'undefined') return '—';
                    var n = Number(num);
                    if (!Number.isFinite(n)) return '—';
                    return (typeof digits === 'number') ? n.toFixed(digits) : String(n);
                }

                function haversineKm(lat1, lon1, lat2, lon2) {
                    var R = 6371;
                    var toRad = function (d) { return d * Math.PI / 180; };
                    var dLat = toRad(lat2 - lat1);
                    var dLon = toRad(lon2 - lon1);
                    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    return R * c;
                }

                function renderRows(states, opts) {
                    var tbody = $('td-flight-tbody');
                    tbody.innerHTML = '';

                    if (!states || !states.length) {
                        tbody.innerHTML = '<tr><td colspan="6" style="color: var(--muted-foreground);">No flights found.</td></tr>';
                        return;
                    }

                    states.forEach(function (s) {
                        var cs = (s.callsign || '').trim() || '—';
                        var country = s.origin_country || '—';
                        var lat = fmt(s.latitude, 4);
                        var lon = fmt(s.longitude, 4);
                        var alt = fmt(s.geo_altitude != null ? s.geo_altitude : s.baro_altitude, 0);
                        var speed = '—';
                        if (s.velocity != null && Number.isFinite(Number(s.velocity))) {
                            speed = fmt(Number(s.velocity) * 3.6, 0);
                        }

                        var tr = document.createElement('tr');
                        tr.innerHTML = '' +
                            '<td style="font-weight: 600;">' + escapeHtml(cs) + '</td>' +
                            '<td style="color: var(--muted-foreground);">' + escapeHtml(country) + '</td>' +
                            '<td>' + escapeHtml(lat) + '</td>' +
                            '<td>' + escapeHtml(lon) + '</td>' +
                            '<td style="text-align:right;">' + escapeHtml(alt) + '</td>' +
                            '<td style="text-align:right;">' + escapeHtml(speed) + '</td>';
                        tbody.appendChild(tr);
                    });
                }

                function escapeHtml(str) {
                    return String(str)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                async function fetchStates(params) {
                    var url = proxyBase + '?' + new URLSearchParams(params).toString();
                    setStatus('Loading…');
                    $('td-flight-count').textContent = 'Flights: —';
                    $('td-flight-updated').textContent = 'Updated: —';

                    var res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    var json = await res.json().catch(function () { return null; });
                    if (!res.ok || !json) {
                        throw new Error((json && json.error) ? json.error : 'Failed to load flights');
                    }
                    return json;
                }

                function bboxFromRadius(lat, lon, radiusKm) {
                    var latDelta = radiusKm / 111;
                    var cos = Math.cos((lat * Math.PI) / 180);
                    var denom = 111 * Math.max(0.2, Math.abs(cos));
                    var lonDelta = radiusKm / denom;
                    return {
                        lamin: lat - latDelta,
                        lamax: lat + latDelta,
                        lomin: lon - lonDelta,
                        lomax: lon + lonDelta,
                    };
                }

                async function trackByIcao24() {
                    var icao24 = ($('td-flight-icao24').value || '').trim().toLowerCase();
                    if (!/^[0-9a-f]{6}$/.test(icao24)) {
                        setStatus('Enter a valid ICAO24 (6 hex).');
                        return;
                    }

                    try {
                        var json = await fetchStates({ icao24: icao24 });
                        $('td-flight-count').textContent = 'Flights: ' + (json.count != null ? json.count : (json.states || []).length);
                        $('td-flight-updated').textContent = 'Updated: ' + (json.time ? new Date(Number(json.time) * 1000).toLocaleTimeString() : '—');
                        setStatus('OK');
                        renderRows((json.states || []).slice(0, 10));
                    } catch (e) {
                        setStatus(String(e && e.message ? e.message : e));
                        renderRows([]);
                    }
                }

                async function findNearby() {
                    var lat = toNum($('td-flight-lat').value);
                    var lon = toNum($('td-flight-lon').value);
                    var radiusKm = Math.max(1, toNum($('td-flight-radius').value) || 150);
                    if (!Number.isFinite(lat) || !Number.isFinite(lon)) {
                        setStatus('Enter lat/lon (or use my location).');
                        return;
                    }

                    var bb = bboxFromRadius(lat, lon, radiusKm);

                    try {
                        var json = await fetchStates({
                            lamin: bb.lamin,
                            lamax: bb.lamax,
                            lomin: bb.lomin,
                            lomax: bb.lomax,
                        });

                        var states = (json.states || []).filter(function (s) {
                            return Number.isFinite(Number(s.latitude)) && Number.isFinite(Number(s.longitude));
                        });

                        states.forEach(function (s) {
                            s._distKm = haversineKm(lat, lon, Number(s.latitude), Number(s.longitude));
                        });
                        states.sort(function (a, b) { return (a._distKm || 0) - (b._distKm || 0); });

                        $('td-flight-count').textContent = 'Flights: ' + states.length;
                        $('td-flight-updated').textContent = 'Updated: ' + (json.time ? new Date(Number(json.time) * 1000).toLocaleTimeString() : '—');
                        setStatus('OK');
                        renderRows(states.slice(0, 12));
                    } catch (e) {
                        setStatus(String(e && e.message ? e.message : e));
                        renderRows([]);
                    }
                }

                $('td-flight-track').addEventListener('click', trackByIcao24);
                $('td-flight-near').addEventListener('click', findNearby);
                $('td-flight-geo').addEventListener('click', async function () {
                    try {
                        var loc = await getBrowserLocation();
                        $('td-flight-lat').value = loc.lat.toFixed(4);
                        $('td-flight-lon').value = loc.lon.toFixed(4);
                        await findNearby();
                    } catch (e) {
                        setStatus(String(e && e.message ? e.message : e));
                    }
                });
                $('td-flight-reset').addEventListener('click', function () {
                    $('td-flight-icao24').value = '';
                    $('td-flight-lat').value = '';
                    $('td-flight-lon').value = '';
                    $('td-flight-radius').value = 150;
                    $('td-flight-count').textContent = 'Flights: —';
                    $('td-flight-updated').textContent = 'Updated: —';
                    $('td-flight-tbody').innerHTML = '<tr><td colspan="6" style="color: var(--muted-foreground);">Search by ICAO24 or find nearby flights.</td></tr>';
                    setStatus('—');
                });
            })();

            // XKCD
            $('td-xkcd-load').addEventListener('click', function () {
                var id = $('td-xkcd-id').value ? Number($('td-xkcd-id').value) : null;
                loadXkcd(id && Number.isFinite(id) ? id : null);
            });
            $('td-xkcd-latest').addEventListener('click', function () {
                $('td-xkcd-id').value = '';
                loadXkcd(null);
            });
            loadXkcd(null);

            // Weather now
            $('td-weather-now').addEventListener('click', async function () {
                try {
                    var place = await geocodeByName($('td-weather-location').value);
                    await checkWeatherNow(place);
                } catch (e) {
                    $('td-weather-place').textContent = String(e && e.message ? e.message : e);
                }
            });
            $('td-weather-geo').addEventListener('click', async function () {
                try {
                    var loc = await getBrowserLocation();
                    var place = await reverseGeocode(loc.lat, loc.lon);
                    $('td-weather-location').value = place.name || '';
                    await checkWeatherNow(place);
                } catch (e) {
                    $('td-weather-place').textContent = String(e && e.message ? e.message : e);
                }
            });
            $('td-weather-now').click();

            // Restaurants
            function searchRestaurantsWith(query) {
                $('td-rest-meta').textContent = query;
                renderRestaurantMap(query);
            }
            $('td-rest-search').addEventListener('click', function () {
                var loc = ($('td-rest-location').value || '').trim();
                var type = ($('td-rest-type').value || '').trim();
                var q = (type ? type : 'restaurants') + (loc ? (' near ' + loc) : '');
                searchRestaurantsWith(q);
            });
            $('td-rest-geo').addEventListener('click', async function () {
                try {
                    var loc = await getBrowserLocation();
                    var type = ($('td-rest-type').value || '').trim();
                    var q = (type ? type : 'restaurants') + ' near ' + loc.lat.toFixed(5) + ',' + loc.lon.toFixed(5);
                    $('td-rest-location').value = loc.lat.toFixed(5) + ',' + loc.lon.toFixed(5);
                    searchRestaurantsWith(q);
                } catch (e) {
                    $('td-rest-meta').textContent = String(e && e.message ? e.message : e);
                }
            });

            // Forecast
            $('td-forecast-load').addEventListener('click', async function () {
                try {
                    var place = await geocodeByName($('td-forecast-location').value);
                    await loadForecastTable(place);
                } catch (e) {
                    $('td-forecast-place').textContent = String(e && e.message ? e.message : e);
                }
            });
            $('td-forecast-geo').addEventListener('click', async function () {
                try {
                    var loc = await getBrowserLocation();
                    var place = await reverseGeocode(loc.lat, loc.lon);
                    $('td-forecast-location').value = place.name || '';
                    await loadForecastTable(place);
                } catch (e) {
                    $('td-forecast-place').textContent = String(e && e.message ? e.message : e);
                }
            });
            $('td-forecast-load').click();

            // Stocks
            $('td-stock-load').addEventListener('click', function () {
                var symbol = ($('td-stock-symbol').value || '').trim();
                if (!symbol) return;
                loadStock(symbol);
            });
            $('td-stock-load').click();

            // Images
            (function initImageFinder() {
                var loadBtn = $('td-img-load');
                var queryEl = $('td-img-query');
                var providerEl = $('td-img-provider');
                var unsplashBtn = $('td-img-unsplash-key-btn');
                var pixabayBtn = $('td-img-pixabay-key-btn');
                var grid = $('td-img-grid');

                if (!loadBtn || !queryEl || !providerEl || !unsplashBtn || !pixabayBtn || !grid) return;
                if (loadBtn.dataset && loadBtn.dataset.tdInit === '1') return;
                if (loadBtn.dataset) loadBtn.dataset.tdInit = '1';

                loadBtn.addEventListener('click', function () {
                    var q = queryEl.value;
                    var p = providerEl.value;
                    loadImages(q, p);
                });

                unsplashBtn.addEventListener('click', function () {
                    promptUnsplashKey();
                    loadBtn.click();
                });

                pixabayBtn.addEventListener('click', function () {
                    promptPixabayKey();
                    loadBtn.click();
                });

                loadBtn.click();
            })();

            // Unit converter
            (function initUnitConverter() {
                var unitCatalog = {
                    length: {
                        label: 'Length',
                        base: 'm',
                        units: {
                            m: { label: 'Meters (m)', factor: 1 },
                            km: { label: 'Kilometers (km)', factor: 1000 },
                            cm: { label: 'Centimeters (cm)', factor: 0.01 },
                            mm: { label: 'Millimeters (mm)', factor: 0.001 },
                            in: { label: 'Inches (in)', factor: 0.0254 },
                            ft: { label: 'Feet (ft)', factor: 0.3048 },
                            yd: { label: 'Yards (yd)', factor: 0.9144 },
                            mi: { label: 'Miles (mi)', factor: 1609.344 }
                        }
                    },
                    weight: {
                        label: 'Weight',
                        base: 'kg',
                        units: {
                            kg: { label: 'Kilograms (kg)', factor: 1 },
                            g: { label: 'Grams (g)', factor: 0.001 },
                            mg: { label: 'Milligrams (mg)', factor: 0.000001 },
                            lb: { label: 'Pounds (lb)', factor: 0.45359237 },
                            oz: { label: 'Ounces (oz)', factor: 0.028349523125 }
                        }
                    },
                    temperature: {
                        label: 'Temperature',
                        base: 'c',
                        units: {
                            c: { label: 'Celsius (°C)' },
                            f: { label: 'Fahrenheit (°F)' },
                            k: { label: 'Kelvin (K)' }
                        }
                    }
                };

                function setOptions(selectEl, units, selected) {
                    selectEl.innerHTML = '';
                    Object.keys(units).forEach(function (key) {
                        var opt = document.createElement('option');
                        opt.value = key;
                        opt.textContent = units[key].label;
                        if (key === selected) opt.selected = true;
                        selectEl.appendChild(opt);
                    });
                }

                function convertTemperature(value, from, to) {
                    var c;
                    if (from === 'c') c = value;
                    else if (from === 'f') c = (value - 32) * (5 / 9);
                    else if (from === 'k') c = value - 273.15;
                    else c = value;

                    if (to === 'c') return c;
                    if (to === 'f') return (c * (9 / 5)) + 32;
                    if (to === 'k') return c + 273.15;
                    return c;
                }

                function calcUnit() {
                    var cat = $('td-unit-category').value;
                    var amount = toNum($('td-unit-value').value);
                    var from = $('td-unit-from').value;
                    var to = $('td-unit-to').value;

                    if (!unitCatalog[cat]) return;
                    if (!from || !to) return;

                    var result;
                    if (cat === 'temperature') {
                        result = convertTemperature(amount, from, to);
                        $('td-unit-formula').textContent = 'Temp conversion';
                    } else {
                        var units = unitCatalog[cat].units;
                        var fromFactor = units[from].factor;
                        var toFactor = units[to].factor;
                        var base = amount * fromFactor;
                        result = base / toFactor;
                        $('td-unit-formula').textContent = 'Base: ' + unitCatalog[cat].base;
                    }

                    var rounded = Number.isFinite(result) ? result.toLocaleString(undefined, { maximumFractionDigits: 6 }) : '—';
                    $('td-unit-result').textContent = rounded;
                }

                function refreshUnitSelects() {
                    var cat = $('td-unit-category').value;
                    var def;
                    if (cat === 'length') def = { from: 'm', to: 'ft' };
                    else if (cat === 'weight') def = { from: 'kg', to: 'lb' };
                    else def = { from: 'c', to: 'f' };

                    setOptions($('td-unit-from'), unitCatalog[cat].units, def.from);
                    setOptions($('td-unit-to'), unitCatalog[cat].units, def.to);
                    calcUnit();
                }

                $('td-unit-category').addEventListener('change', refreshUnitSelects);
                $('td-unit-from').addEventListener('change', calcUnit);
                $('td-unit-to').addEventListener('change', calcUnit);
                $('td-unit-value').addEventListener('input', calcUnit);
                $('td-unit-convert').addEventListener('click', calcUnit);
                $('td-unit-swap').addEventListener('click', function () {
                    var a = $('td-unit-from').value;
                    var b = $('td-unit-to').value;
                    $('td-unit-from').value = b;
                    $('td-unit-to').value = a;
                    calcUnit();
                });

                refreshUnitSelects();
            })();

            // Currency converter
            (function initCurrencyConverter() {
                var common = ['USD', 'EUR', 'GBP', 'BDT', 'INR', 'JPY', 'AUD', 'CAD', 'SGD', 'AED'];
                var cached = { base: null, rates: null, updatedUnix: null };

                function setCurrencyOptions(selectEl, selected) {
                    selectEl.innerHTML = '';
                    common.forEach(function (c) {
                        var opt = document.createElement('option');
                        opt.value = c;
                        opt.textContent = c;
                        if (c === selected) opt.selected = true;
                        selectEl.appendChild(opt);
                    });
                }

                function fmtUpdated(unix) {
                    if (!unix) return '—';
                    try {
                        var d = new Date(unix * 1000);
                        return d.toLocaleString();
                    } catch (e) {
                        return '—';
                    }
                }

                async function loadRates(base) {
                    $('td-fx-status').textContent = 'Loading…';
                    var url = '{{ route('tyro-dashboard.examples.widgets.fx', ['base' => 'USD']) }}'.replace('USD', encodeURIComponent(base));
                    var res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    var data = await res.json();
                    if (!res.ok) throw new Error(data && data.error ? data.error : 'Failed to load rates');
                    cached.base = data.base;
                    cached.rates = data.rates;
                    cached.updatedUnix = data.time_last_update_unix || null;
                    $('td-fx-status').textContent = 'Ready (' + cached.base + ')';
                    $('td-fx-updated').textContent = 'Updated: ' + fmtUpdated(cached.updatedUnix);
                }

                function convertFx() {
                    var amount = toNum($('td-fx-amount').value);
                    var from = $('td-fx-from').value;
                    var to = $('td-fx-to').value;

                    if (!cached.rates || !cached.base) {
                        $('td-fx-result').textContent = 'Result: —';
                        $('td-fx-rate').textContent = 'Rate: —';
                        return;
                    }

                    // Rates are relative to cached.base
                    var rFrom = cached.rates[from];
                    var rTo = cached.rates[to];
                    if (!rFrom || !rTo) {
                        $('td-fx-result').textContent = 'Result: —';
                        $('td-fx-rate').textContent = 'Rate: —';
                        return;
                    }

                    var rate = rTo / rFrom;
                    var out = amount * rate;

                    $('td-fx-rate').textContent = 'Rate: 1 ' + from + ' = ' + rate.toFixed(6) + ' ' + to;
                    $('td-fx-result').textContent = 'Result: ' + out.toLocaleString(undefined, { maximumFractionDigits: 2, minimumFractionDigits: 2 }) + ' ' + to;
                }

                setCurrencyOptions($('td-fx-from'), 'USD');
                setCurrencyOptions($('td-fx-to'), 'BDT');
                $('td-fx-status').textContent = 'Loading…';
                $('td-fx-updated').textContent = 'Updated: —';

                $('td-fx-convert').addEventListener('click', async function () {
                    try {
                        var base = $('td-fx-from').value;
                        if (!cached.rates || cached.base !== base) {
                            await loadRates(base);
                        }
                        convertFx();
                    } catch (e) {
                        $('td-fx-status').textContent = String(e && e.message ? e.message : e);
                    }
                });
                $('td-fx-refresh').addEventListener('click', async function () {
                    try {
                        await loadRates($('td-fx-from').value);
                        convertFx();
                    } catch (e) {
                        $('td-fx-status').textContent = String(e && e.message ? e.message : e);
                    }
                });
                $('td-fx-swap').addEventListener('click', function () {
                    var a = $('td-fx-from').value;
                    var b = $('td-fx-to').value;
                    $('td-fx-from').value = b;
                    $('td-fx-to').value = a;
                    cached.base = null;
                    cached.rates = null;
                    cached.updatedUnix = null;
                    $('td-fx-status').textContent = 'Swapped';
                    $('td-fx-updated').textContent = 'Updated: —';
                    $('td-fx-rate').textContent = 'Rate: —';
                    $('td-fx-result').textContent = 'Result: —';
                });
                $('td-fx-amount').addEventListener('input', convertFx);
                $('td-fx-from').addEventListener('change', function () {
                    cached.base = null;
                    cached.rates = null;
                    cached.updatedUnix = null;
                    $('td-fx-status').textContent = 'Base changed';
                    $('td-fx-updated').textContent = 'Updated: —';
                    $('td-fx-rate').textContent = 'Rate: —';
                    $('td-fx-result').textContent = 'Result: —';
                });
                $('td-fx-to').addEventListener('change', convertFx);

                // Auto-load once so it feels ready
                (async function () {
                    try {
                        await loadRates($('td-fx-from').value);
                        convertFx();
                    } catch (e) {
                        $('td-fx-status').textContent = 'FX unavailable';
                    }
                })();
            })();

            // Invoice
            initInvoice();
        });
    })();
</script>
