/* Yooo profile widget. No dependencies; all UI and styles live in Shadow DOM. */
(() => {
    'use strict';

    const API = 'https://api.yooo.app/api/v1/profiles';
    const PROFILE_SITE = 'https://www.yooo.app';
    const VERSION = '1';
    const requestKey = Symbol.for('yooo.profile-widget.requests');
    const requests = window[requestKey] || (window[requestKey] = new Map());
    const styles = `
      :host{all:initial;display:block;contain:content;font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;color:#172033;font-size:16px;line-height:1.5;--yooo-primary:#6d4aff}
      *,*::before,*::after{box-sizing:border-box}
      .shell{width:100%;padding:16px;background:var(--yooo-bg,#fff);color:var(--yooo-fg,#172033);border:1px solid var(--yooo-border,#e8eaf0);border-radius:18px}
      .shell.dark{--yooo-bg:#151821;--yooo-fg:#f3f4f6;--yooo-muted:#a5adbd;--yooo-border:#303542;--yooo-card:#202531;--yooo-soft:#292f3c}
      .heading{margin:0 0 14px;font-size:20px;line-height:1.25;font-weight:700;letter-spacing:-.02em}
      .grid{display:grid;grid-template-columns:1fr;gap:14px}
      .grid.list{grid-template-columns:1fr}
      .list .card{display:flex;align-items:stretch}
      .list .photo{flex:0 0 120px;width:120px;height:auto;min-height:150px}
      .list .body{flex:1;min-width:0}
      .card{min-width:0;overflow:hidden;border:1px solid var(--yooo-border,#e8eaf0);border-radius:14px;background:var(--yooo-card,#fff);box-shadow:0 2px 10px #121a2b0a}
      .photo{display:block;width:100%;height:210px;object-fit:cover;background:var(--yooo-soft,#f1f3f8)}
      .body{padding:14px}.top{display:flex;align-items:center;gap:7px;flex-wrap:wrap}.name{margin:0;font-size:17px;font-weight:700;line-height:1.3}
      .badge,.category{display:inline-flex;align-items:center;border-radius:999px;padding:3px 8px;font-size:11px;font-weight:650;white-space:nowrap}
      .badge{background:#e9f9ef;color:#14783e}.dark .badge{background:#153c2a;color:#9ae6b4}.category{background:var(--yooo-soft,#f1f3f8);color:var(--yooo-muted,#687386)}
      .description{margin:9px 0 0;color:var(--yooo-muted,#667085);font-size:13px;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
      .location{margin:9px 0 0;color:var(--yooo-muted,#667085);font-size:12px}.actions{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:13px}
      .cta{display:inline-flex;align-items:center;justify-content:center;padding:9px 13px;border-radius:9px;background:var(--yooo-primary);color:#fff!important;text-decoration:none!important;font-size:13px;font-weight:700;transition:filter .15s}
      .cta:hover{filter:brightness(.92)}.cta:focus-visible,.control:focus-visible,.retry:focus-visible{outline:3px solid color-mix(in srgb,var(--yooo-primary),white 50%);outline-offset:2px}
      .status{padding:28px 12px;text-align:center;color:var(--yooo-muted,#667085);font-size:14px}.status strong{display:block;margin-bottom:4px;color:var(--yooo-fg,#172033);font-size:15px}
      .spinner{display:inline-block;width:18px;height:18px;margin-bottom:8px;border:2px solid var(--yooo-border,#e8eaf0);border-top-color:var(--yooo-primary);border-radius:50%;animation:spin .7s linear infinite}@keyframes spin{to{transform:rotate(360deg)}}
      .retry,.control{border:1px solid var(--yooo-border,#e8eaf0);border-radius:8px;background:var(--yooo-card,#fff);color:var(--yooo-fg,#172033);font:inherit;font-size:13px;padding:7px 11px;cursor:pointer}.retry{margin-top:12px}.carousel-head{display:flex;align-items:center;justify-content:flex-end;gap:7px;margin:-5px 0 10px}.rail{display:grid;grid-auto-columns:minmax(235px,1fr);grid-auto-flow:column;gap:14px;overflow-x:auto;overscroll-behavior-x:contain;scroll-snap-type:x mandatory;padding:2px 1px 10px}.rail .card{scroll-snap-align:start}
      @media(min-width:560px){.shell{padding:20px}.grid{grid-template-columns:repeat(2,minmax(0,1fr))}.photo{height:190px}.grid.list{grid-template-columns:1fr}.list .photo{flex-basis:160px;width:160px;height:auto}}
      @media(min-width:920px){.grid{grid-template-columns:repeat(3,minmax(0,1fr))}.photo{height:205px}}
      @media(max-width:420px){.shell{padding:12px}.grid{grid-template-columns:1fr}.photo{height:220px}}
      @media(prefers-reduced-motion:reduce){*,*::before,*::after{scroll-behavior:auto!important;animation-duration:.01ms!important;transition-duration:.01ms!important}}
    `;

    const scripts = [...document.querySelectorAll('script[src]')].filter((node) => {
        try { return new URL(node.src, document.baseURI).pathname.endsWith('/widget.js'); } catch (_) { return false; }
    });
    const activeScript = document.currentScript;
    const targets = activeScript && scripts.includes(activeScript) ? [activeScript] : scripts;

    const text = (value, max = 300) => typeof value === 'string' ? value.trim().slice(0, max) : '';
    const parseBoolean = (value, fallback, label) => {
        if (value == null || value === '') return fallback;
        if (/^(true|1|yes)$/i.test(value)) return true;
        if (/^(false|0|no)$/i.test(value)) return false;
        throw new Error(`${label} must be true or false.`);
    };
    const readConfig = (script) => {
        const attr = (name) => script.getAttribute(`data-${name}`);
        const limitRaw = attr('limit');
        const limit = limitRaw == null || limitRaw === '' ? 12 : Number(limitRaw);
        if (!Number.isInteger(limit) || limit < 1 || limit > 50) throw new Error('data-limit must be a whole number from 1 to 50.');
        const layout = (attr('layout') || 'grid').toLowerCase();
        if (!['grid', 'list', 'carousel'].includes(layout)) throw new Error('data-layout must be grid, list, or carousel.');
        const theme = (attr('theme') || 'light').toLowerCase();
        if (!['light', 'dark'].includes(theme)) throw new Error('data-theme must be light or dark.');
        const gender = (attr('gender') || 'female').toLowerCase();
        if (!['female', 'male', 'trans'].includes(gender)) throw new Error('data-gender must be female, male, or trans.');
        const primary = attr('primary-color');
        if (primary && !/^#[\da-f]{3}(?:[\da-f]{3})?$/i.test(primary)) throw new Error('data-primary-color must be a 3 or 6 digit hex color.');
        return {
            city: text(attr('city'), 100), country: text(attr('country'), 100), gender, limit, layout, theme,
            verified: parseBoolean(attr('verified'), false, 'data-verified'),
            showLocation: parseBoolean(attr('show-location'), true, 'data-show-location'),
            showRating: parseBoolean(attr('show-rating'), true, 'data-show-rating'),
            heading: text(attr('heading'), 100) || 'Featured profiles', primary,
        };
    };
    const safeUrl = (value, fallback = '') => {
        try {
            const url = new URL(value, PROFILE_SITE);
            return url.protocol === 'https:' ? url.href : fallback;
        } catch (_) { return fallback; }
    };
    const slug = (value) => text(value, 100).normalize('NFKD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'profile';
    const parseImage = (raw) => {
        let images = raw;
        if (typeof images === 'string') { try { images = JSON.parse(images); } catch (_) { images = []; } }
        if (images && !Array.isArray(images) && typeof images === 'object') images = Object.values(images);
        const image = Array.isArray(images) ? images.find((item) => typeof item === 'string' && item.trim()) : '';
        if (!image) return 'https://www.yooo.app/images/yoooo-female.webp';
        let src = image.trim();
        if (!/^https:\/\//i.test(src)) src = `https://cdn.yooo.app//images/users/${src.replace(/^\/+/, '')}`;
        return safeUrl(src, 'https://www.yooo.app/images/yoooo-female.webp');
    };
    const getProfiles = (config, signal) => {
        const url = new URL(API);
        url.searchParams.set('gender', config.gender);
        url.searchParams.set('limit', String(config.limit));
        if (config.city) url.searchParams.set('city', config.city);
        if (config.country) url.searchParams.set('country', config.country);
        if (config.verified) url.searchParams.set('is_verified', 'true');
        const key = url.href;
        if (!requests.has(key)) {
            const requestController = new AbortController();
            const requestTimeout = window.setTimeout(() => requestController.abort(), 12_000);
            const request = fetch(key, { method: 'GET', mode: 'cors', credentials: 'omit', cache: 'default', signal: requestController.signal, headers: { Accept: 'application/json' } })
                .then(async (response) => {
                    if (!response.ok) throw new Error(`Listings could not be loaded (HTTP ${response.status}).`);
                    const payload = await response.json();
                    if (payload?.status === 'error') throw new Error('The listings service returned an error.');
                    const list = payload?.data?.profiles;
                    if (!Array.isArray(list)) throw new Error('The listings service returned an unexpected response.');
                    return list;
                }).catch((error) => {
                    if (error?.name === 'AbortError') throw new Error('The listings request timed out.');
                    throw error;
                }).finally(() => {
                    window.clearTimeout(requestTimeout);
                    if (requests.get(key) === request) requests.delete(key);
                });
            requests.set(key, request);
        }
        if (!signal) return requests.get(key);
        return Promise.race([requests.get(key), new Promise((_, reject) => signal.addEventListener('abort', () => reject(new Error('The listings request timed out.')), { once: true }))]);
    };

    const createWidget = (script) => {
        if (script.dataset.yoooWidgetInitialized === 'true') return;
        script.dataset.yoooWidgetInitialized = 'true';
        const mount = document.createElement('div');
        mount.setAttribute('data-yooo-widget-mount', '');
        script.insertAdjacentElement('afterend', mount);
        const shadow = mount.attachShadow({ mode: 'open' });
        const style = document.createElement('style');
        style.textContent = styles;
        shadow.append(style);

        let config;
        try { config = readConfig(script); } catch (error) { renderMessage(shadow, '', error.message, 'Configuration error'); return; }
        const shell = document.createElement('section');
        shell.className = `shell${config.theme === 'dark' ? ' dark' : ''}`;
        shell.setAttribute('aria-label', config.heading);
        if (config.primary) shell.style.setProperty('--yooo-primary', config.primary);
        const heading = document.createElement('h2');
        heading.className = 'heading'; heading.textContent = config.heading;
        shell.append(heading);
        shadow.append(shell);
        renderLoading(shell);

        const controller = new AbortController();
        const timeout = window.setTimeout(() => controller.abort(), 12_000);
        getProfiles(config, controller.signal).then((profiles) => {
            window.clearTimeout(timeout);
            renderProfiles(shell, profiles.slice(0, config.limit), config);
        }).catch((error) => {
            window.clearTimeout(timeout);
            renderError(shell, error?.message || 'Please try again in a moment.', () => createWidgetRetry(shell, config));
        });
    };

    function renderLoading(shell) {
        const status = document.createElement('div'); status.className = 'status'; status.setAttribute('role', 'status');
        const spinner = document.createElement('span'); spinner.className = 'spinner'; spinner.setAttribute('aria-hidden', 'true');
        const label = document.createElement('span'); label.textContent = 'Loading profiles…'; status.append(spinner, label); shell.append(status);
    }
    function renderMessage(root, theme, message, title) {
        const shell = document.createElement('section'); shell.className = `shell${theme === 'dark' ? ' dark' : ''}`;
        const status = document.createElement('div'); status.className = 'status';
        const strong = document.createElement('strong'); strong.textContent = title;
        const detail = document.createElement('span'); detail.textContent = message;
        status.append(strong, detail); shell.append(status); root.append(shell);
    }
    function renderError(shell, message, retry) {
        shell.querySelector('.status,.grid,.rail,.carousel-head')?.remove();
        const status = document.createElement('div'); status.className = 'status'; status.setAttribute('role', 'alert');
        const strong = document.createElement('strong'); strong.textContent = 'Profiles unavailable';
        const detail = document.createElement('span'); detail.textContent = message;
        const button = document.createElement('button'); button.className = 'retry'; button.type = 'button'; button.textContent = 'Try again'; button.addEventListener('click', retry);
        status.append(strong, detail, button); shell.append(status);
    }
    function createWidgetRetry(shell, config) {
        shell.querySelector('.status')?.remove(); renderLoading(shell);
        const controller = new AbortController(); const timeout = window.setTimeout(() => controller.abort(), 12_000);
        getProfiles(config, controller.signal).then((profiles) => { window.clearTimeout(timeout); renderProfiles(shell, profiles.slice(0, config.limit), config); })
            .catch((error) => { window.clearTimeout(timeout); renderError(shell, error?.message || 'Please try again in a moment.', () => createWidgetRetry(shell, config)); });
    }
    function renderProfiles(shell, profiles, config) {
        shell.querySelector('.status,.grid,.rail,.carousel-head')?.remove();
        if (!profiles.length) {
            const status = document.createElement('div'); status.className = 'status'; status.setAttribute('role', 'status');
            const strong = document.createElement('strong'); strong.textContent = 'No profiles found';
            const detail = document.createElement('span'); detail.textContent = 'Try another location or check back later.';
            status.append(strong, detail); shell.append(status); return;
        }
        const list = document.createElement('div'); list.className = config.layout === 'carousel' ? 'rail' : 'grid';
        if (config.layout === 'list') list.classList.add('list');
        profiles.forEach((profile) => list.append(createCard(profile, config)));
        if (config.layout === 'carousel') {
            const controls = document.createElement('div'); controls.className = 'carousel-head';
            const previous = document.createElement('button'); previous.className = 'control'; previous.type = 'button'; previous.setAttribute('aria-label', 'Scroll profiles backward'); previous.textContent = '←';
            const next = document.createElement('button'); next.className = 'control'; next.type = 'button'; next.setAttribute('aria-label', 'Scroll profiles forward'); next.textContent = '→';
            previous.addEventListener('click', () => list.scrollBy({ left: -list.clientWidth * .8, behavior: 'smooth' }));
            next.addEventListener('click', () => list.scrollBy({ left: list.clientWidth * .8, behavior: 'smooth' }));
            controls.append(previous, next); shell.append(controls);
        }
        shell.append(list);
    }
    function createCard(profile, config) {
        const card = document.createElement('article'); card.className = 'card';
        const image = document.createElement('img'); image.className = 'photo'; image.loading = 'lazy'; image.decoding = 'async';
        image.src = parseImage(profile?.images); image.alt = text(profile?.name, 120) || 'Profile photo';
        const body = document.createElement('div'); body.className = 'body';
        const top = document.createElement('div'); top.className = 'top';
        const name = document.createElement('h3'); name.className = 'name'; name.textContent = text(profile?.name, 120) || 'Profile'; top.append(name);
        const verified = profile?.is_verified === true || profile?.is_verified === 1 || profile?.is_verified === '1' || profile?.is_verified === 'true';
        if (verified) { const badge = document.createElement('span'); badge.className = 'badge'; badge.textContent = 'Verified'; top.append(badge); }
        const gender = text(profile?.gender, 30).toLowerCase();
        const category = document.createElement('span'); category.className = 'category';
        category.textContent = ({ female: 'Female', male: 'Male', trans: 'Trans' })[gender] || 'Profile'; top.append(category);
        body.append(top);
        const description = text(profile?.description, 360);
        if (description) { const p = document.createElement('p'); p.className = 'description'; p.textContent = description; body.append(p); }
        const location = text(profile?.location, 150);
        if (config.showLocation && location) { const p = document.createElement('p'); p.className = 'location'; p.textContent = `⌖ ${location}`; body.append(p); }
        const actions = document.createElement('div'); actions.className = 'actions';
        if (config.showRating) {
            const ratingValue = Number(profile?.rating ?? profile?.average_rating);
            const reviewValue = Number(profile?.review_count ?? profile?.reviews_count);
            if (Number.isFinite(ratingValue) && ratingValue > 0) {
                const rating = document.createElement('span'); rating.className = 'location';
                rating.textContent = `★ ${ratingValue.toFixed(1)}${Number.isFinite(reviewValue) && reviewValue > 0 ? ` (${Math.floor(reviewValue)} reviews)` : ''}`;
                actions.append(rating);
            }
        }
        const id = Number(profile?.id);
        const url = Number.isSafeInteger(id) && id > 0 ? `${PROFILE_SITE}/en/profile/${id}/${slug(profile?.name)}` : PROFILE_SITE;
        const link = document.createElement('a'); link.className = 'cta'; link.href = url; link.target = '_blank'; link.rel = 'noopener noreferrer'; link.textContent = 'View profile'; actions.append(link);
        body.append(actions); card.append(image, body); return card;
    }

    targets.forEach(createWidget);
})();
