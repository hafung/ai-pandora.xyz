@extends('layouts.tools-static')

@section('title', __('privacy_page.title'))

@section('header', __('privacy_page.header'))

@section('content')
    <div class="prose max-w-none">
        <p class="text-lg mb-4">{{ __('privacy_page.intro') }}</p>

        <h2 class="text-2xl font-semibold mt-6 mb-3">{{ __('privacy_page.info_collection.title') }}</h2>
        <p>{{ __('privacy_page.info_collection.content') }}</p>

        <h2 class="text-2xl font-semibold mt-6 mb-3">{{ __('privacy_page.cookie_usage.title') }}</h2>
        <p>{{ __('privacy_page.cookie_usage.content') }}</p>

        <h2 class="text-2xl font-semibold mt-6 mb-3">{{ __('privacy_page.data_security.title') }}</h2>
        <p>{{ __('privacy_page.data_security.content') }}</p>

        <h2 class="text-2xl font-semibold mt-6 mb-3">{{ __('privacy_page.third_party.title') }}</h2>
        <p>{{ __('privacy_page.third_party.content') }}</p>

        <h2 class="text-2xl font-semibold mt-6 mb-3">{{ __('privacy_page.policy_changes.title') }}</h2>
        <p>{{ __('privacy_page.policy_changes.content') }}</p>

        <p class="mt-6">{{ __('privacy_page.contact') }}</p>
    </div>
@endsection
