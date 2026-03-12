@extends('layouts.web-site.web')

@section('title')
    @yield('title') - عائلة السريِّع
@endsection

@push('styles')
<style>
    .error-container {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: radial-gradient(circle at top right, rgba(55, 160, 92, 0.05) 0%, transparent 40%),
                    radial-gradient(circle at bottom left, rgba(20, 81, 71, 0.05) 0%, transparent 40%);
    }
    .error-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 32px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        padding: 3.5rem;
        max-width: 600px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .error-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #145147, #37a05c, #145147);
    }
    .error-code {
        font-size: 8rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: block;
        font-family: 'Alexandria', sans-serif;
    }
    .error-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
    }
    .error-message {
        font-size: 1.1rem;
        color: #4b5563;
        line-height: 1.8;
        margin-bottom: 2.5rem;
    }
    .btn-error {
        background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
        color: white !important;
        padding: 1rem 2.5rem;
        border-radius: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 20px -5px rgba(20, 81, 71, 0.3);
    }
    .btn-error:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -5px rgba(20, 81, 71, 0.4);
    }
    .error-icon-bg {
        position: absolute;
        font-size: 15rem;
        opacity: 0.03;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="error-container">
    <div class="error-card animate-fade-in-up">
        <span class="error-icon-bg"><i class="fas fa-exclamation-triangle"></i></span>
        
        <div class="relative z-10">
            <span class="error-code">@yield('code')</span>
            <h1 class="error-title">@yield('title')</h1>
            <p class="error-message">@yield('message')</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" class="btn-error">
                    <i class="fas fa-home"></i>
                    العودة للرئيسية
                </a>
                <button onclick="window.history.back()" class="px-8 py-4 bg-gray-100 text-gray-700 rounded-2xl font-semibold hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-right"></i>
                    الصفحة السابقة
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

