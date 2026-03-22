<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Satu Dashboard, Ribuan Peluang" />

    <div class="font-headline bg-gradient-to-br from-primary-fixed to-secondary-fixed min-h-screen text-on-surface">
        <!-- Top Navigation Bar -->
        <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md shadow-sm shadow-[#FF6B6B]/5">
            <div class="flex justify-between items-center px-6 py-4 max-w-7xl mx-auto">
                <img src="/logo.png" alt="UmkmAI Logo" class="h-8 w-auto object-contain" />
                <div class="hidden md:flex items-center gap-8 font-medium text-sm tracking-tight">
                    <a class="text-primary font-bold hover:scale-105 transition-transform" href="#">Features</a>
                    <a class="text-slate-600 hover:text-primary hover:scale-105 transition-transform" href="#">Solutions</a>
                    <a class="text-slate-600 hover:text-primary hover:scale-105 transition-transform" href="#">Pricing</a>
                    <a class="text-slate-600 hover:text-primary hover:scale-105 transition-transform" href="#">Documentation</a>
                </div>
                <Link
                    :href="route('inbox.index')"
                    class="bg-primary text-white px-6 py-2.5 rounded-full font-bold text-sm hover:scale-105 active:scale-95 transition-all shadow-bolt"
                >
                    Get Started
                </Link>
            </div>
        </nav>

        <main class="pt-24">
            <!-- Hero Section -->
            <section class="max-w-7xl mx-auto px-6 pt-20 pb-32 grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-container text-on-primary-container rounded-full text-[10px] font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">bolt</span>
                        New: AI Smart Auto-Reply 2.0
                    </div>
                    <h1 class="text-[4rem] md:text-[5rem] font-extrabold leading-[0.9] tracking-tighter text-on-surface">
                        Satu Dashboard, <br/>
                        <span class="logo-gradient">Ribuan Peluang.</span>
                    </h1>
                    <p class="text-lg md:text-xl text-on-surface-variant max-w-xl font-medium">
                        Kelola seluruh chat WhatsApp dan Instagram dalam satu tempat. Tingkatkan penjualan dengan asisten AI pintar yang bekerja 24/7 untuk UMKM Anda.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <Link :href="route('inbox.index')" class="bg-primary text-white px-8 py-4 rounded-full font-bold text-lg hover:scale-105 active:scale-95 transition-all shadow-bolt flex items-center gap-2">
                            Mulai Gratis Sekarang
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </Link>
                        <a href="#" class="bg-white text-on-surface px-8 py-4 rounded-full font-bold text-lg hover:scale-105 active:scale-95 transition-all shadow-coral border border-outline flex items-center gap-2">
                            Lihat Demo
                            <span class="material-symbols-outlined">play_circle</span>
                        </a>
                    </div>
                    <!-- Social Proof Logos -->
                    <div class="pt-12 space-y-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Trusted by Modern Stacks</p>
                        <div class="flex flex-wrap gap-8 opacity-50 grayscale hover:grayscale-0 transition-all">
                            <div class="flex items-center gap-2 font-bold text-lg"><span class="material-symbols-outlined text-primary">dynamic_form</span> Laravel</div>
                            <div class="flex items-center gap-2 font-bold text-lg"><span class="material-symbols-outlined text-secondary">hexagon</span> Vue.js</div>
                            <div class="flex items-center gap-2 font-bold text-lg"><span class="material-symbols-outlined text-on-tertiary-container">waves</span> Tailwind</div>
                            <div class="flex items-center gap-2 font-bold text-lg"><span class="material-symbols-outlined text-primary">psychology</span> Claude</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-64 h-64 bg-secondary/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
                    <div class="relative bg-white p-4 rounded-[2.5rem] shadow-2xl border border-white/50 backdrop-blur-sm">
                        <img 
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_OaFEciNpz61ZEGnzTb1mUPGuMQ0vgfrQEFzqtq2bkbIRumQzwLQjCq8gW4f2uJ5zs_qRmbYk4wKKkwWnZeVF7VJFBwQDdvitZspRAzS2Aw7iTgvXuUTK4I2hQUBkmbpetF6ZY_KcWvOVBLzL8occES725d-Z6PL9Q5swBsqBfSV_NUDajz6fo9TBG8Z4iVdY_JNuxvcGDsvc5wa2VnLxkq3X47S1GK5k0DUwr9m9C4iMuM1p9zJcSWzIKMu4mQriE0vjBFGXKvw" 
                            alt="UmkmAI Dashboard Preview" 
                            class="rounded-[2rem] w-full h-auto shadow-inner" 
                        />
                        <!-- Floating UI Element -->
                        <div class="absolute -right-8 top-1/4 bg-white p-4 rounded-2xl shadow-xl border border-outline max-w-[200px] animate-bounce-slow">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-tertiary-container flex items-center justify-center">
                                    <span class="material-symbols-outlined text-on-tertiary-container text-sm">check_circle</span>
                                </div>
                                <div class="text-[10px] font-bold uppercase">Order Detected</div>
                            </div>
                            <div class="text-sm font-semibold">1x Kopi Gula Aren</div>
                            <div class="text-[10px] text-on-surface-variant">Dari WhatsApp - 2m ago</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Bento Grid -->
            <section class="max-w-7xl mx-auto px-6 py-32">
                <div class="text-center mb-20 space-y-4">
                    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight">Fasilitas Lengkap <span class="text-primary">Untuk Bisnis Anda</span></h2>
                    <p class="text-on-surface-variant font-medium text-lg">Semua yang Anda butuhkan untuk skala bisnis lebih cepat.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Large Feature Card -->
                    <div class="md:col-span-2 bg-white rounded-xl p-10 shadow-coral border border-outline-variant/10 flex flex-col justify-between overflow-hidden relative group">
                        <div class="relative z-10 max-w-md">
                            <span class="material-symbols-outlined text-primary text-4xl mb-6">all_inbox</span>
                            <h3 class="text-3xl font-extrabold mb-4">Unified Inbox</h3>
                            <p class="text-on-surface-variant font-medium">Balas semua pesan WhatsApp Business, Instagram DM, dan Facebook Messenger dalam satu layar. Tidak ada lagi pesan yang terlewat.</p>
                        </div>
                        <div class="mt-12 -mb-20 -mr-20">
                            <img 
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBk2X3NxoIwKYdmZCtjkX-NaT4zFUvQFkorgeEbBU82ddHgKNHVJyKpBetkyQUQuvRUHTBDIKF_JVU1CdaXCwrZFp6pWDjAI8Uc9So3Wx4WnlQTNTHAumV1JFe_LLculWGN_o3fDwPD6Ye2D58udbOztO87V8Om3-aPaHq_fUEiRwPEctmmMvVOTZ36GHNzxsA7sCvvL4jn498GiyMqrR7g49QeV-odwmC5-YPwkSd-nJQzyM88BRovs44ZxqCnA68FYIVfQCGOsmg" 
                                alt="Unified Inbox Interface" 
                                class="rounded-tl-3xl shadow-2xl rotate-2 group-hover:rotate-0 transition-transform duration-500" 
                            />
                        </div>
                    </div>

                    <!-- Small Feature Card 1 -->
                    <div class="bg-surface-container rounded-xl p-10 flex flex-col gap-6 border border-outline-variant/5">
                        <span class="material-symbols-outlined text-secondary text-4xl">smart_toy</span>
                        <div>
                            <h3 class="text-2xl font-extrabold mb-2">AI Auto-Reply</h3>
                            <p class="text-on-surface-variant text-sm font-medium">Asisten AI yang mengerti konteks pertanyaan pelanggan dan menjawab layaknya manusia berpengalaman.</p>
                        </div>
                        <div class="mt-auto pt-6 border-t border-secondary/20">
                            <span class="text-xs font-bold uppercase tracking-widest text-[#2C7A7B]">Powered by Gemini & Claude</span>
                        </div>
                    </div>

                    <!-- Small Feature Card 2 -->
                    <div class="bg-surface-container-high rounded-xl p-10 flex flex-col gap-6 border border-outline-variant/5">
                        <span class="material-symbols-outlined text-[#276749] text-4xl">shopping_cart_checkout</span>
                        <div>
                            <h3 class="text-2xl font-extrabold mb-2">Order Detection</h3>
                            <p class="text-on-surface-variant text-sm font-medium">Otomatis mendeteksi pesanan dari chat dan memasukkannya ke sistem inventori secara real-time.</p>
                        </div>
                    </div>

                    <!-- Medium Feature Card -->
                    <div class="md:col-span-2 bg-inverse-surface text-white rounded-xl p-10 flex flex-col md:flex-row gap-10 items-center overflow-hidden">
                        <div class="flex-1">
                            <span class="material-symbols-outlined text-primary text-4xl mb-6">bar_chart</span>
                            <h3 class="text-3xl font-extrabold mb-4">Sales Analytics</h3>
                            <p class="text-slate-300 font-medium">Pantau performa penjualan, kecepatan respon tim, dan produk terpopuler melalui dashboard analitik yang intuitif.</p>
                        </div>
                        <div class="flex-1 w-full">
                            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/10">
                                <div class="flex items-end gap-2 h-32">
                                    <div class="flex-1 bg-primary rounded-t-lg h-[40%]"></div>
                                    <div class="flex-1 bg-secondary rounded-t-lg h-[70%]"></div>
                                    <div class="flex-1 bg-primary rounded-t-lg h-[55%]"></div>
                                    <div class="flex-1 bg-secondary rounded-t-lg h-[90%]"></div>
                                    <div class="flex-1 bg-primary rounded-t-lg h-[65%]"></div>
                                </div>
                                <div class="flex justify-between mt-4 text-[10px] font-bold uppercase text-slate-400">
                                    <span>Mon</span><span>Wed</span><span>Fri</span><span>Sun</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="max-w-7xl mx-auto px-6 py-20">
                <div class="bg-gradient-to-br from-primary to-inverse-primary rounded-[3rem] p-16 text-center text-white relative overflow-hidden shadow-bolt">
                    <div class="absolute top-0 right-0 p-20 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
                    <div class="relative z-10 space-y-8 max-w-2xl mx-auto">
                        <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight">Siap Kembangkan Bisnis Anda?</h2>
                        <p class="text-xl opacity-90 font-medium">Dapatkan akses penuh gratis selama 14 hari. Tanpa kartu kredit. Batalkan kapan saja.</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                            <Link :href="route('inbox.index')" class="bg-white text-primary px-10 py-5 rounded-full font-bold text-xl hover:scale-105 active:scale-95 transition-all shadow-xl">
                                Daftar Sekarang
                            </Link>
                            <a href="#" class="bg-transparent border-2 border-white/50 text-white px-10 py-5 rounded-full font-bold text-xl hover:bg-white/10 transition-all">
                                Konsultasi Ahli
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="w-full rounded-t-[2.5rem] mt-20 bg-[#FFF9F0] bg-gradient-to-b from-white to-[#FFF9F0]">
            <div class="flex flex-col md:flex-row justify-between items-center px-10 py-12 gap-6 w-full max-w-7xl mx-auto">
                <div class="space-y-4 text-center md:text-left">
                    <img src="/logo.png" alt="UmkmAI Logo" class="h-10 w-auto object-contain mx-auto md:mx-0" />
                    <p class="font-['Plus_Jakarta_Sans'] text-xs font-medium uppercase tracking-wider text-slate-500">
                        © {{ new Date().getFullYear() }} UmkmAI. Empowering Indonesian SMEs.
                    </p>
                </div>
                <div class="flex flex-wrap justify-center gap-6 md:gap-8">
                    <a class="font-['Plus_Jakarta_Sans'] text-xs font-medium uppercase tracking-wider text-slate-500 hover:text-teal-500 transition-colors hover:underline underline-offset-4" href="#">Privacy Policy</a>
                    <a class="font-['Plus_Jakarta_Sans'] text-xs font-medium uppercase tracking-wider text-slate-500 hover:text-teal-500 transition-colors hover:underline underline-offset-4" href="#">Terms of Service</a>
                    <a class="font-['Plus_Jakarta_Sans'] text-xs font-medium uppercase tracking-wider text-slate-500 hover:text-teal-500 transition-colors hover:underline underline-offset-4" href="#">Contact Us</a>
                    <a class="font-['Plus_Jakarta_Sans'] text-xs font-medium uppercase tracking-wider text-slate-500 hover:text-teal-500 transition-colors hover:underline underline-offset-4" href="#">Blog</a>
                </div>
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-white border border-outline flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-lg">language</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white border border-outline flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-lg">hub</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
    .shadow-coral {
        box-shadow: 0 4px 20px rgba(255, 107, 107, 0.08);
    }
    .shadow-bolt {
        box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
    }
    .logo-gradient {
        background: linear-gradient(to right, var(--color-primary), var(--color-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .animate-bounce-slow {
        animation: bounce 3s infinite;
    }
</style>
