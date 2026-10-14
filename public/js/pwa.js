/*
 * ثبت سرویس‌ورکر + مدیریت نوار «نصب اپلیکیشن».
 * هم تو سایت اصلی و هم تو پنل مدیریت/فروشندگی/ملک استفاده می‌شه؛
 * کدوم بنر و کدوم متن نشون داده بشه از data-attribute های خود بنر خونده می‌شه.
 */
(function () {
  var deferredPrompt = null;
  var banner = null;

  // این رویداد گاهی قبل از آماده‌شدن صفحه می‌آید. اگر دیر گوش بدهیم،
  // کروم آن را مصرف می‌کند و نوار نصب دیگر دیده نمی‌شود.
  window.addEventListener('beforeinstallprompt', function (e) {
    e.preventDefault();
    deferredPrompt = e;
    if (!banner) return;
    banner.classList.remove('is-manual');
    banner.classList.add('is-visible');
  });

  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register('/sw.js').catch(function () {
        // اگه سرویس‌ورکر ثبت نشد (مثلاً HTTP بدون SSL)، سایت عادی کار می‌کنه
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    banner = document.querySelector('.js-pwa-banner');
    if (!banner) return;

    var storageKey = banner.dataset.dismissKey || 'pwa_banner_dismissed_at';
    var isStandalone = window.matchMedia('(display-mode: standalone)').matches
      || window.navigator.standalone === true;

    if (isStandalone) return; // از قبل به‌صورت اپ نصب شده، نیازی به بنر نیست

    var dismissedAt = parseInt(localStorage.getItem(storageKey) || '0', 10);
    var fourteenDays = 14 * 24 * 60 * 60 * 1000;
    if (dismissedAt && (Date.now() - dismissedAt) < fourteenDays) return;

    var isIos = /iphone|ipad|ipod/i.test(window.navigator.userAgent) && !window.MSStream;
    var isAndroid = /android/i.test(window.navigator.userAgent);

    function showBanner() {
      banner.classList.add('is-visible');
    }
    function hideBanner(remember) {
      banner.classList.remove('is-visible');
      if (remember) localStorage.setItem(storageKey, String(Date.now()));
    }

    if (isIos) {
      banner.classList.add('is-ios');
      showBanner();
    } else if (isAndroid) {
      banner.classList.add('is-android');
      if (!deferredPrompt) banner.classList.add('is-manual');
      showBanner();
    } else if (deferredPrompt) {
      showBanner();
    }

    window.addEventListener('appinstalled', function () {
      hideBanner(true);
    });

    var installBtn = banner.querySelector('.js-pwa-install');
    if (installBtn) {
      installBtn.addEventListener('click', function () {
        if (!deferredPrompt) {
          banner.classList.add('is-manual');
          return;
        }
        deferredPrompt.prompt();
        deferredPrompt.userChoice.finally(function () {
          deferredPrompt = null;
          hideBanner(true);
        });
      });
    }

    var dismissBtn = banner.querySelector('.js-pwa-dismiss');
    if (dismissBtn) {
      dismissBtn.addEventListener('click', function () { hideBanner(true); });
    }
  });
})();
