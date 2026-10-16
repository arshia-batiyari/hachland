/*
 * ثبت سرویس‌ورکر + نوار «نصب اپلیکیشن».
 * سایت و هر سه پنل از همین فایل استفاده می‌کنند.
 * رویداد beforeinstallprompt را پارشیال pwa-prompt-capture زودتر ذخیره می‌کند.
 */
(function () {
  var deferredPrompt = window.__hachInstall || null;
  var banner = null;

  function ua() {
    return window.navigator.userAgent || '';
  }

  function inAppBrowser() {
    var agent = ua();
    if (/Instagram|FBAN|FBAV|FB_IAB|FB4A|Line\/|Twitter|Telegram|WhatsApp|Snapchat|MicroMessenger|TikTok|musical_ly|BytedanceWebview|GSA\//i.test(agent)) {
      return true;
    }
    if (/\bwv\b|; wv\)/.test(agent)) return true;
    if (/Android/i.test(agent) && /Version\/[\d.]+/.test(agent) && !/Chrome\/[\d.]+/.test(agent) && !/SamsungBrowser/i.test(agent) && !/Firefox/i.test(agent)) {
      return true;
    }
    return false;
  }

  function browserKind() {
    var agent = ua();
    if (inAppBrowser()) return 'webview';
    if (/SamsungBrowser/i.test(agent)) return 'samsung';
    if (/Firefox\//i.test(agent) && !/Seamonkey/i.test(agent)) return 'firefox';
    return 'chrome';
  }

  function isStandalone() {
    return window.matchMedia('(display-mode: standalone)').matches
      || window.matchMedia('(display-mode: fullscreen)').matches
      || window.matchMedia('(display-mode: minimal-ui)').matches
      || window.navigator.standalone === true;
  }

  function chromeIntent() {
    var scheme = window.location.protocol === 'https:' ? 'https' : 'http';
    var fallback = encodeURIComponent(window.location.href);
    var path = window.location.host + window.location.pathname + window.location.search;
    return 'intent://' + path + '#Intent;scheme=' + scheme
      + ';package=com.android.chrome;S.browser_fallback_url=' + fallback + ';end';
  }

  function applyManualCopy() {
    if (!banner) return;
    var el = banner.querySelector('.pwa-banner-android-text');
    if (!el) return;
    var kind = browserKind();
    var text = el.getAttribute('data-' + kind) || el.getAttribute('data-chrome');
    if (text) el.textContent = text;
  }

  function showManual() {
    if (!banner) return;
    banner.classList.add('is-manual');
    banner.classList.add('is-visible');
    applyManualCopy();
  }

  function armInstall() {
    if (!banner || !deferredPrompt) return;
    if (banner.classList.contains('is-webview')) return;
    banner.classList.remove('is-manual');
    banner.classList.add('is-visible');
  }

  window.addEventListener('hach-install-ready', function () {
    deferredPrompt = window.__hachInstall;
    armInstall();
  });

  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(function () {});
  }

  document.addEventListener('DOMContentLoaded', function () {
    banner = document.querySelector('.js-pwa-banner');
    if (!banner) return;

    var storageKey = banner.dataset.dismissKey || 'pwa_banner_dismissed_at';
    if (isStandalone()) return;

    var dismissedAt = parseInt(localStorage.getItem(storageKey) || '0', 10);
    var fourteenDays = 14 * 24 * 60 * 60 * 1000;
    if (dismissedAt && (Date.now() - dismissedAt) < fourteenDays) return;

    var agent = ua();
    var isIos = /iphone|ipad|ipod/i.test(agent) && !window.MSStream;
    var isAndroid = /android/i.test(agent);
    var kind = browserKind();

    function hideBanner(remember) {
      banner.classList.remove('is-visible');
      if (remember) localStorage.setItem(storageKey, String(Date.now()));
    }

    if (kind === 'webview') banner.classList.add('is-webview');
    if (kind === 'firefox') banner.classList.add('is-firefox');
    if (kind === 'samsung') banner.classList.add('is-samsung');

    var openBrowser = banner.querySelector('.js-pwa-open-external');
    if (openBrowser && isAndroid && kind === 'webview') {
      openBrowser.href = chromeIntent();
    }

    if (isIos) {
      banner.classList.add('is-ios');
      if (kind === 'webview') applyManualCopy();
      banner.classList.add('is-visible');
    } else if (isAndroid) {
      banner.classList.add('is-android');
      if (kind === 'webview' || !deferredPrompt) {
        showManual();
      } else {
        armInstall();
      }
    } else if (deferredPrompt) {
      armInstall();
    }

    window.addEventListener('appinstalled', function () {
      hideBanner(true);
    });

    var installBtn = banner.querySelector('.js-pwa-install');
    if (installBtn) {
      installBtn.addEventListener('click', function () {
        if (!deferredPrompt || typeof deferredPrompt.prompt !== 'function') {
          showManual();
          return;
        }
        var waiting = deferredPrompt;
        deferredPrompt = null;
        var choicePromise = waiting.userChoice;
        try {
          Promise.resolve(waiting.prompt()).catch(function () {
            showManual();
          });
        } catch (err) {
          showManual();
          return;
        }
        Promise.resolve(choicePromise).then(function (choice) {
          if (choice && choice.outcome === 'accepted') {
            hideBanner(true);
            return;
          }
          showManual();
        }).catch(function () {
          showManual();
        });
      });
    }

    var dismissBtn = banner.querySelector('.js-pwa-dismiss');
    if (dismissBtn) {
      dismissBtn.addEventListener('click', function () { hideBanner(true); });
    }
  });
})();
