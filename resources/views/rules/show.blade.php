@extends('layouts.app')
@section('title', __('site.rules.title'))

@section('content')
<section class="section" style="padding-top:28px;">
  <div class="wrap">
    <article class="rules-sheet">
      <p class="rules-kicker">{{ __('site.brand') }}</p>
      <h1>{{ __('site.rules.title') }}</h1>
      <p class="rules-lead">{{ __('site.rules.lead') }}</p>

      @foreach ($scopes as $scope)
        <section class="rules-block">
          <h2>{{ __('site.rules.scopes.'.$scope.'.title') }}</h2>
          <ul>
            @foreach (__('site.rules.scopes.'.$scope.'.items') as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        </section>
      @endforeach

      <form method="POST" action="{{ route('rules.accept') }}" class="rules-accept">
        @csrf
        <label>
          <input type="checkbox" name="accepted" value="1" {{ old('accepted') ? 'checked' : '' }}>
          <span>{{ __('site.rules.accept') }}</span>
        </label>
        @error('accepted')
          <p class="rules-error">{{ __('site.rules.accept_required') }}</p>
        @enderror
        <button type="submit" class="btn btn-primary">{{ __('site.rules.submit') }}</button>
      </form>
    </article>
  </div>
</section>
@endsection
