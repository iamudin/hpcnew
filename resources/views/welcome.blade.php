<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lab TI Polbeng - Sistem Peminjaman Laboratorium</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    
    body {
      font-family: 'Inter', system-ui, sans-serif;
    }
    
    .hero-bg {
      background: linear-gradient(135deg, #0f172a 0%, #1e2937 100%);
    }
    
    .nav-link {
      transition: all 0.3s ease;
    }
    
    .nav-link:hover {
      color: #22d3ee;
      transform: translateY(-2px);
    }
    
    .feature-card {
      transition: all 0.3s ease;
    }
    
    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }
    
    .step {
      transition: all 0.3s ease;
    }
    
    .cta-gradient {
      background: linear-gradient(90deg, #1e40af, #22d3ee);
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-200">

  <!-- Navbar -->
  <nav class="bg-slate-950 border-b border-slate-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-5">
      <div class="flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center gap-3">
   
          <div>
            <img src="/dark.png" class="h-12">
       
          </div>
        </div>

      

        <!-- Auth Buttons -->
        <div class="flex items-center gap-3">
          <a href="{{ route('filament.admin.auth.login') }}" 
                  class="px-6 py-2.5 text-sm font-semibold border border-slate-400 hover:border-cyan-400 hover:text-cyan-400 rounded-2xl transition-all duration-300">
            Login
          </a>
          
       
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-bg min-h-screen flex items-center">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
      <div class="space-y-8">
        <div class="inline-flex items-center gap-2 bg-slate-800 text-cyan-400 text-sm font-medium px-4 py-2 rounded-full">
          <div class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></div>
          Sistem Peminjaman Lab Real-time
        </div>
        
        <h1 class="text-5xl md:text-6xl font-bold leading-tight text-white">
          Sistem Peminjaman<br>
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Laboratorium TI</span>
        </h1>
        
        <p class="text-xl text-slate-400 max-w-lg">
          Kelola dan ajukan peminjaman laboratorium Teknik Informatika Politeknik Negeri Bengkalis dengan mudah, cepat, dan transparan.
        </p>
        
        <div class="flex flex-wrap gap-4">
          <button onclick="document.getElementById('alur').scrollIntoView({ behavior: 'smooth' })" 
                  class="px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-semibold text-lg rounded-3xl transition-all duration-300 flex items-center gap-3 shadow-lg shadow-cyan-500/30">
            Mulai Sekarang
            <i class="fa-solid fa-arrow-right"></i>
          </button>
          
          <button onclick="alert('Login dengan Google...')" 
                  class="flex items-center gap-3 border border-slate-400 hover:border-white px-8 py-4 rounded-3xl font-semibold text-lg transition-all duration-300">
            <i class="fa-brands fa-google text-red-500"></i>
            Login dengan Google
          </button>
        </div>
        
        <div class="flex items-center gap-8 text-sm text-slate-400">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-check text-emerald-400"></i>
            <span>Real-time</span>
          </div>
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-check text-emerald-400"></i>
            <span>Transparan</span>
          </div>
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-check text-emerald-400"></i>
            <span>Aman</span>
          </div>
        </div>
      </div>
      
      <!-- Hero Illustration -->
      <div class="relative hidden md:block">
        <div class="bg-slate-900/70 backdrop-blur-xl border border-slate-700 rounded-3xl p-6 shadow-2xl">
          <img src="/lab.jpeg" 
               alt="Laboratorium Komputer TI Polbeng" 
               class="rounded-2xl shadow-xl w-full object-cover">
          {{-- <div class="absolute -bottom-4 -right-4 bg-slate-800 border border-slate-700 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-3xl"></i>
              </div>
              <div>
                <p class="font-semibold text-white">Lab sedang tersedia</p>
                <p class="text-sm text-slate-400">3 lab kosong hari ini</p>
              </div>
            </div>
          </div> --}}
        </div>
      </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-2 text-slate-500">
      <span class="text-xs tracking-widest">SCROLL</span>
      <i class="fa-solid fa-chevron-down animate-bounce"></i>
    </div>
  </section>

  <!-- Fitur Section -->
  <section id="fitur" class="py-24 bg-slate-900">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="text-cyan-400 font-medium">Fitur Unggulan</span>
        <h2 class="text-4xl font-bold mt-3 text-white">Semua yang Anda Butuhkan</h2>
        <p class="mt-4 text-slate-400 max-w-md mx-auto">
          Platform modern untuk mengelola peminjaman laboratorium dengan efisien
        </p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="feature-card bg-slate-800 border border-slate-700 rounded-3xl p-8 hover:border-cyan-400 group">
          <div class="w-14 h-14 bg-cyan-500/10 text-cyan-400 rounded-2xl flex items-center justify-center mb-6 text-3xl">
            <i class="fa-solid fa-calendar-check"></i>
          </div>
          <h3 class="text-xl font-semibold mb-3">Booking Real-time</h3>
          <p class="text-slate-400">Lihat jadwal kosong secara langsung dan pesan dalam hitungan detik.</p>
          <div class="mt-6 text-cyan-400 text-sm font-medium flex items-center gap-2 group-hover:gap-3 transition-all">
            Pelajari lebih lanjut <span class="text-xl">→</span>
          </div>
        </div>
        
        <!-- Card 2 -->
        <div class="feature-card bg-slate-800 border border-slate-700 rounded-3xl p-8 hover:border-cyan-400 group">
          <div class="w-14 h-14 bg-blue-500/10 text-blue-400 rounded-2xl flex items-center justify-center mb-6 text-3xl">
            <i class="fa-solid fa-eye"></i>
          </div>
          <h3 class="text-xl font-semibold mb-3">Monitoring Status</h3>
          <p class="text-slate-400">Pantau status peminjaman Anda dan lab yang sedang digunakan secara real-time.</p>
          <div class="mt-6 text-cyan-400 text-sm font-medium flex items-center gap-2 group-hover:gap-3 transition-all">
            Pelajari lebih lanjut <span class="text-xl">→</span>
          </div>
        </div>
        
        <!-- Card 3 -->
        <div class="feature-card bg-slate-800 border border-slate-700 rounded-3xl p-8 hover:border-cyan-400 group">
          <div class="w-14 h-14 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center mb-6 text-3xl">
            <i class="fa-solid fa-bell"></i>
          </div>
          <h3 class="text-xl font-semibold mb-3">Notifikasi Otomatis</h3>
          <p class="text-slate-400">Dapatkan pengingat dan notifikasi persetujuan melalui email dan WhatsApp.</p>
          <div class="mt-6 text-cyan-400 text-sm font-medium flex items-center gap-2 group-hover:gap-3 transition-all">
            Pelajari lebih lanjut <span class="text-xl">→</span>
          </div>
        </div>
        
        <!-- Card 4 -->
        <div class="feature-card bg-slate-800 border border-slate-700 rounded-3xl p-8 hover:border-cyan-400 group">
          <div class="w-14 h-14 bg-purple-500/10 text-purple-400 rounded-2xl flex items-center justify-center mb-6 text-3xl">
            <i class="fa-solid fa-users-gear"></i>
          </div>
          <h3 class="text-xl font-semibold mb-3">Manajemen Admin</h3>
          <p class="text-slate-400">Laboran dan admin dapat mengelola jadwal, persetujuan, dan laporan dengan mudah.</p>
          <div class="mt-6 text-cyan-400 text-sm font-medium flex items-center gap-2 group-hover:gap-3 transition-all">
            Pelajari lebih lanjut <span class="text-xl">→</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Alur Section -->
  <section id="alur" class="py-24 bg-slate-950">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="text-cyan-400 font-medium">Proses Mudah</span>
        <h2 class="text-4xl font-bold mt-3 text-white">Alur Peminjaman</h2>
      </div>
      
      <div class="grid md:grid-cols-5 gap-8 relative">
        <!-- Connector line (hidden on mobile) -->
        <div class="hidden md:block absolute top-12 left-1/2 w-full h-0.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent -translate-x-1/2"></div>
        
        <!-- Step 1 -->
        <div class="step text-center relative">
          <div class="mx-auto w-16 h-16 bg-slate-800 border-4 border-cyan-400 rounded-full flex items-center justify-center text-2xl font-bold text-cyan-400 mb-6">1</div>
          <div class="text-4xl mb-4 text-slate-400"><i class="fa-solid fa-right-to-bracket"></i></div>
          <h3 class="font-semibold text-lg">Login dengan Google</h3>
          <p class="text-slate-400 text-sm mt-2">Masuk menggunakan akun Google kampus Anda</p>
        </div>
        
        <!-- Step 2 -->
        <div class="step text-center relative">
          <div class="mx-auto w-16 h-16 bg-slate-800 border-4 border-cyan-400 rounded-full flex items-center justify-center text-2xl font-bold text-cyan-400 mb-6">2</div>
          <div class="text-4xl mb-4 text-slate-400"><i class="fa-solid fa-door-open"></i></div>
          <h3 class="font-semibold text-lg">Pilih Laboratorium</h3>
          <p class="text-slate-400 text-sm mt-2">Pilih lab yang tersedia sesuai kebutuhan</p>
        </div>
        
        <!-- Step 3 -->
        <div class="step text-center relative">
          <div class="mx-auto w-16 h-16 bg-slate-800 border-4 border-cyan-400 rounded-full flex items-center justify-center text-2xl font-bold text-cyan-400 mb-6">3</div>
          <div class="text-4xl mb-4 text-slate-400"><i class="fa-solid fa-clock"></i></div>
          <h3 class="font-semibold text-lg">Pilih Jadwal Kosong</h3>
          <p class="text-slate-400 text-sm mt-2">Lihat dan pilih slot waktu yang tersedia</p>
        </div>
        
        <!-- Step 4 -->
        <div class="step text-center relative">
          <div class="mx-auto w-16 h-16 bg-slate-800 border-4 border-cyan-400 rounded-full flex items-center justify-center text-2xl font-bold text-cyan-400 mb-6">4</div>
          <div class="text-4xl mb-4 text-slate-400"><i class="fa-solid fa-paper-plane"></i></div>
          <h3 class="font-semibold text-lg">Ajukan Peminjaman</h3>
          <p class="text-slate-400 text-sm mt-2">Isi keperluan dan kirim permohonan</p>
        </div>
        
        <!-- Step 5 -->
        <div class="step text-center relative">
          <div class="mx-auto w-16 h-16 bg-slate-800 border-4 border-cyan-400 rounded-full flex items-center justify-center text-2xl font-bold text-cyan-400 mb-6">5</div>
          <div class="text-4xl mb-4 text-slate-400"><i class="fa-solid fa-check-circle"></i></div>
          <h3 class="font-semibold text-lg">Tunggu Persetujuan</h3>
          <p class="text-slate-400 text-sm mt-2">Dapatkan notifikasi saat disetujui</p>
        </div>
      </div>
    </div>
  </section>




  <!-- Footer -->
  <footer class="bg-slate-950  border-slate-800 py-16 pt-0">
    <div class="max-w-7xl mx-auto px-6">
  
      
      <div class="border-slate-800 mt-16 pt-8 text-center text-slate-500 text-sm">
        © 2026 Teknik Informatika - Politeknik Negeri Bengkalis. All rights reserved.
      </div>
    </div>
  </footer>

  <script>
    // Tailwind script already included via CDN
    console.log('%cLab TI Polbeng Landing Page loaded successfully ✅', 'color: #22d3ee; font-weight: 600;');
  </script>
</body>
</html>