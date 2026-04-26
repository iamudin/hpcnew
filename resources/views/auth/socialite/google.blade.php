<div style="margin-top: 20px;">

    <!-- Keterangan -->
    <div style="text-align: center; margin-bottom: 10px;">
        <div style="font-size: 14px; color: #374151;">
            Login dengan Google
        </div>
        <div style="font-size: 12px; color: #dc2626; font-weight: 500;">
            * Khusus mahasiswa
        </div>
    </div>

    <!-- Tombol -->
    <button
        type="button"
        onclick="window.location.href='{{ route('socialite.redirect', 'google') }}'"
        style="
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #ffffff;
            color: #111827;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        "
        onmouseover="this.style.background='#f9fafb'"
        onmouseout="this.style.background='#ffffff'"
    >

        <!-- Icon Google -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="18">
            <path fill="#EA4335" d="M24 9.5c3.1 0 5.9 1.1 8.1 3.1l6-6C34.2 2.2 29.4 0 24 0 14.6 0 6.5 5.4 2.5 13.3l7.4 5.7C11.8 13.3 17.4 9.5 24 9.5z"/>
            <path fill="#4285F4" d="M46.1 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.5c-.5 2.7-2 5-4.3 6.6l6.7 5.2c3.9-3.6 6.2-8.9 6.2-16.3z"/>
            <path fill="#FBBC05" d="M9.9 28.9c-1-2.7-1-5.6 0-8.3l-7.4-5.7C.9 18.3 0 21.1 0 24s.9 5.7 2.5 9.1l7.4-4.2z"/>
            <path fill="#34A853" d="M24 48c6.4 0 11.8-2.1 15.7-5.7l-6.7-5.2c-1.9 1.3-4.3 2.1-9 2.1-6.6 0-12.2-3.8-14.1-9.5l-7.4 5.7C6.5 42.6 14.6 48 24 48z"/>
        </svg>

        <span>Masuk dengan Google</span>
    </button>

</div>