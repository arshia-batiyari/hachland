/* Hachland PWA — native install prompt + install banner */
(function () {
  'use strict';

  var deferredPrompt = null;
  var banner = null;
  var installBtn = null;
  var dismissBtn = null;
  var initialized = false;
  var isIos = /iphone|ipad|ipod/i.test(window.navigator.userAgent) && !window.MSStream;

  function isStandalone() {
    return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
  }

  function showBanner() {
    if (!banner || isStandalone()) return;
    banner.classList.add('is-visible');
  }

  function hideBanner(remember) {
    if (!banner) return;
    banner.classList.remove('is-visible');
    if (remember) {
      localStorage.setItem(
        banner.dataset.dismissKey || 'pwa_site_dismissed_at_v4',
        String(Date.now())
      );
    }
  }

  function hasRecentDismissal() {
    if (!banner) return false;
    var key = banner.dataset.dismissKey || 'pwa_site_dismissed_at_v4';
    var dismissedAt = parseInt(localStorage.getItem(key) || '0', 10);
    return dismissedAt > 0 && (Date.now() - dismissedAt) < (14 * 24 * 60 * 60 * 1000);
  }

  function showManualInstructions() {
    if (!banner) return;
    banner.classList.add('pwa-manual-install');
    var manualText = banner.querySelector('.pwa-banner-manual-text');
    if (manualText) manualText.hidden = false;
  }

  // IMPORTANT: listen immediately. Chromium can dispatch beforeinstallprompt
  // before DOMContentLoaded on some navigations.
  window.addEventListener('beforeinstallprompt', function (e) {
    e.preventDefault();
    deferredPrompt = e;

    if (initialized && banner && !isStandalone() && !hasRecentDismissal()) {
      banner.classList.remove('is-ios');
      showBanner();
    }
  });

  window.addEventListener('appinstalled', function () {
    deferredPrompt = null;
    hideBanner(false);
  });

  // Register SW independently of the banner UI.
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register('/sw.js', { updateViaCache: 'none' })
        .then(function (registration) {
          registration.update().catch(function () {});
        })
        .catch(function (err) {
          console.warn('PWA service worker registration failed:', err);
        });
    });
  }

  function init() {
    if (initialized) return;
    initialized = true;

    banner = document.querySelector('.js-pwa-banner');
    if (!banner || isStandalone()) return;

    installBtn = banner.querySelector('.js-pwa-install');
    dismissBtn = banner.querySelector('.js-pwa-dismiss');

    // New versioned key: old dismissals cannot hide this corrected UI.
    banner.dataset.dismissKey = (banner.dataset.dismissKey || 'pwa_site_dismissed_at') + '_v4';

    if (hasRecentDismissal()) return;

    if (isIos) {
      banner.classList.add('is-ios');
      showBanner();
    } else if (deferredPrompt) {
      // Native Chrome/Edge install is available right now.
      showBanner();
    } else {
      // Chromium does not always expose beforeinstallprompt immediately.
      // Show the banner after a short delay. If the event never arrives,
      // the button explains the browser-menu installation path instead of
      // pretending that a native prompt can be opened programmatically.
      window.setTimeout(function () {
        if (!deferredPrompt && !isStandalone() && !hasRecentDismissal()) {
          showBanner();
        }
      }, 2500);
    }

    if (installBtn) {
      installBtn.addEventListener('click', async function () {
        if (deferredPrompt) {
          var promptEvent = deferredPrompt;
          deferredPrompt = null;

          try {
            await promptEvent.prompt();
            var choice = await promptEvent.userChoice;
            if (choice && choice.outcome === 'accepted') {
              hideBanner(false);
            } else {
              // User dismissed the native dialog; leave the banner available.
              showBanner();
            }
          } catch (err) {
            console.warn('PWA install prompt failed:', err);
            showManualInstructions();
          }
          return;
        }

        showManualInstructions();
      });
    }

    if (dismissBtn) {
      dismissBtn.addEventListener('click', function () {
        hideBanner(true);
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
})();
