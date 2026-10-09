<div class="field-group">
  <label>{{ __('site.announcements.form_title_fa') }}</label>
  <input type="text" name="title_fa" class="field" required value="{{ old('title_fa', $announcement->title['fa'] ?? '') }}">
</div>
<div class="field-group">
  <label>{{ __('site.announcements.form_title_en') }}</label>
  <input type="text" name="title_en" class="field" value="{{ old('title_en', $announcement->title['en'] ?? '') }}">
</div>

<div class="field-group">
  <label>{{ __('site.announcements.form_message_fa') }}</label>
  <textarea name="message_fa" class="field" rows="3">{{ old('message_fa', $announcement->message['fa'] ?? '') }}</textarea>
</div>
<div class="field-group">
  <label>{{ __('site.announcements.form_message_en') }}</label>
  <textarea name="message_en" class="field" rows="3">{{ old('message_en', $announcement->message['en'] ?? '') }}</textarea>
</div>

<div class="field-group">
  <label>{{ __('site.announcements.form_type') }}</label>
  <select name="type" class="field">
    @foreach (['info', 'offer', 'urgent'] as $t)
      <option value="{{ $t }}" {{ old('type', $announcement->type ?? 'info') == $t ? 'selected' : '' }}>{{ __('site.announcements.type_'.$t) }}</option>
    @endforeach
  </select>
</div>

<div class="field-group">
  <label>{{ __('site.announcements.form_link_url') }}</label>
  <input type="url" name="link_url" class="field" placeholder="https://" value="{{ old('link_url', $announcement->link_url ?? '') }}">
</div>
<div class="field-group">
  <label>{{ __('site.announcements.form_link_text') }}</label>
  <input type="text" name="link_text" class="field" value="{{ old('link_text', $announcement->link_text ?? '') }}">
</div>

<div class="field-group">
  <label>{{ __('site.announcements.form_priority') }}</label>
  <input type="number" name="priority" class="field" min="0" max="255" style="max-width:120px;" value="{{ old('priority', $announcement->priority ?? 0) }}">
  <span style="font-size:11.5px; color:var(--ink-soft); display:block; margin-top:4px;">{{ __('site.announcements.form_priority_note') }}</span>
</div>

<div style="display:flex; gap:16px;">
  <div class="field-group" style="flex:1;">
    <label>{{ __('site.announcements.form_starts_at') }}</label>
    <input type="datetime-local" name="starts_at" class="field" value="{{ old('starts_at', optional($announcement->starts_at ?? null)->format('Y-m-d\TH:i')) }}">
  </div>
  <div class="field-group" style="flex:1;">
    <label>{{ __('site.announcements.form_ends_at') }}</label>
    <input type="datetime-local" name="ends_at" class="field" value="{{ old('ends_at', optional($announcement->ends_at ?? null)->format('Y-m-d\TH:i')) }}">
  </div>
</div>
<span style="font-size:11.5px; color:var(--ink-soft); display:block; margin-top:-10px; margin-bottom:20px;">{{ __('site.announcements.form_period_note') }}</span>

<label class="filter-opt" style="margin-bottom:20px;">
  <input type="hidden" name="is_active" value="0">
  <input type="checkbox" name="is_active" value="1" {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}>
  {{ __('site.announcements.form_is_active') }}
</label>