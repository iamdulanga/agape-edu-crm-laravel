@extends('layouts.guest')

@section('title', 'Login - AGAPE EDU CRM')

@push('styles')
<style>
    :root {
        --bg1: #0ea5e9;
        --bg2: #8b5cf6;
        --card: #ffffff;
        --text: #0f172a;
        --muted: #64748b;
        --border: #e2e8f0;
        --error: #ef4444;
    }
    .auth-shell {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 32px 16px;
        background: radial-gradient(1200px 800px at 10% 10%, rgba(14,165,233,.25), transparent 60%),
                    radial-gradient(1000px 700px at 90% 30%, rgba(139,92,246,.25), transparent 60%),
                    linear-gradient(180deg, #f8fafc, #eef2ff 40%, #eff6ff);
    }
    .auth-card {
        width: min(420px, 92vw);
        background: var(--card);
        color: var(--text);
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 20px 40px rgba(2,6,23,.08), 0 8px 16px rgba(2,6,23,.05);
        padding: 28px 24px;
        position: relative;
        overflow: hidden;
        isolation: isolate;
    }
    .auth-card::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(14,165,233,.2), rgba(139,92,246,.2));
        z-index: -1;
        filter: blur(12px);
        opacity: .6;
    }
    .brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        margin-bottom: 18px;
        text-align: center;
    }
    .brand h1 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: .4px;
        background: linear-gradient(135deg, var(--bg1), var(--bg2));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .subtitle {
        margin: 0;
        font-size: 14px;
        color: var(--muted);
    }
    .form {
        margin-top: 18px;
        display: grid;
        gap: 14px;
    }
    .field label {
        display: block;
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 6px;
    }
    .input-wrap {
        position: relative;
    }
    .input {
        width: 100%;
        height: 44px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #fff;
        padding: 0 12px 0 40px;
        font-size: 14px;
        color: var(--text);
        outline: none;
        transition: box-shadow .2s ease, border-color .2s ease, transform .06s ease;
    }
    .input:focus {
        border-color: rgba(14,165,233,.5);
        box-shadow: 0 0 0 6px rgba(14,165,233,.12);
    }
    .icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }
    .error {
        margin-top: 6px;
        font-size: 12px;
        color: var(--error);
    }
    .btn {
        height: 44px;
        border: 0;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        letter-spacing: .2px;
        background: linear-gradient(135deg, var(--bg1), var(--bg2));
        cursor: pointer;
        transition: transform .06s ease, box-shadow .2s ease, filter .2s ease;
        box-shadow: 0 10px 20px rgba(14,165,233,.25), 0 8px 16px rgba(139,92,246,.18);
        position: relative;
        overflow: hidden;
    }
    .btn:hover { filter: brightness(1.02); }
    .btn:active { transform: translateY(1px); }
    .btn[disabled] {
        cursor: not-allowed;
        filter: grayscale(.2) brightness(.96);
        box-shadow: none;
        opacity: .9;
    }
    .hint {
        margin-top: 14px;
        padding: 12px 12px;
        background: #f8fafc;
        border: 1px dashed #e2e8f0;
        border-radius: 10px;
        color: #334155;
    }
    .hint summary {
        cursor: pointer;
        list-style: none;
        font-weight: 600;
        user-select: none;
    }
    .hint[open] summary { margin-bottom: 6px; }
    .hint code {
        background: #e2e8f0;
        padding: 2px 6px;
        border-radius: 6px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
    <div class="auth-shell">
        <div class="auth-card" role="region" aria-label="Login form">
            <div class="brand">
                <h1>AGAPE EDU CRM</h1>
                <p class="subtitle">Sign in to continue</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="form" novalidate>
                @csrf

                <div class="field">
                    <label for="username">Username</label>
                    <div class="input-wrap">
                        <span class="icon" aria-hidden>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.33 0-8 2.17-8 5v1h16v-1c0-2.83-3.67-5-8-5Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <input
                            id="username"
                            class="input @error('username') border-red-500 @enderror"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            placeholder="Enter your username"
                            required
                            autofocus
                        />
                    </div>
                    @error('username')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <span class="icon" aria-hidden>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2Zm-7-2a2 2 0 0 1 4 0v2H10V7Zm7 11H7v-7h10v7Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <input
                            id="password"
                            class="input @error('password') border-red-500 @enderror"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            required
                        />
                    </div>
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn">
                    Sign in
                </button>
            </form>

            <details class="hint">
                <summary>Test credentials</summary>
                <div style="font-size: 13px; line-height: 1.6; margin-top: 4px">
                    <div><strong>Owner:</strong> username <code>owner</code> • password <code>password</code></div>
                    <div><strong>Manager:</strong> username <code>manager</code> • password <code>password</code></div>
                    <div><strong>Counselor:</strong> username <code>counselor</code> • password <code>password</code></div>
                </div>
            </details>
        </div>
    </div>
@endsection