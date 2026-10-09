@php
  $activeAnnouncement = \App\Models\Announcement::current();
@endphp

@if ($activeAnnouncement)
  <div class="announce-bar type-{{ $activeAnnouncement->type }}" data-announce-id="{{ $activeAnnouncement->id }}" id="announceBar">
    <span class="announce-title">{{ $activeAnnouncement->localized_title }}</span>
    @if ($activeAnnouncement->localized_message)
      <span class="announce-msg">{{ $activeAnnouncement->localized_message }}</span>
    @endif
    @if ($activeAnnouncement->link_url)
      <a href="{{ $activeAnnouncement->link_url }}" class="announce-cta">{{ $activeAnnouncement->link_text ?: __('site.announcements.default_link_text') }} ←</a>
    @endif
    <button type="button" class="announce-close" onclick="hachlandDismissAnnouncement({{ $activeAnnouncement->id }})" aria-label="close">×</button>
  </div>

  <script>
    (function () {
      var id = '{{ $activeAnnouncement->id }}';
      // اگه قبلاً همین اگهی رو کاربر بسته، دیگه نشونش نده — ولی به محض اینکه
      // ادمین یه اگهی جدید (id متفاوت) فعال کنه، دوباره از اول نمایش داده می‌شه
      if (localStorage.getItem('hachland_dismissed_announcement') === id) {
        var bar = document.getElementById('announceBar');
        if (bar) bar.style.display = 'none';
      }
    })();

    function hachlandDismissAnnouncement(id) {
      localStorage.setItem('hachland_dismissed_announcement', String(id));
      var bar = document.getElementById('announceBar');
      if (bar) bar.style.display = 'none';
    }
  </script>
@endif