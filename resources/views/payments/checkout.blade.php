<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - InfiMal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .rainbow-text {
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1, #96CEB4, #FFEAA7, #FF6B6B);
            background-size: 400% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: rainbow 8s ease infinite;
        }
        @keyframes rainbow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.3);
            transform: translateY(-2px);
        }
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ mobileMenuOpen: false }">
    
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-md z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex items-center">
            <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
        </a>
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold rainbow-text">InfiMal</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-gray-900 font-medium transition">Features</a>
                    <a href="/dashboard" class="text-gray-600 hover:text-gray-900 font-medium transition">Dashboard</a>
                    <a href="/login" class="text-gray-600 hover:text-gray-900 font-medium transition">Login</a>
                </div>
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  :d="mobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t border-gray-200">
            <div class="px-4 py-4 space-y-4">
                <a href="#features" class="block text-gray-600 hover:text-gray-900 font-medium">Features</a>
                <a href="/dashboard" class="block text-gray-600 hover:text-gray-900 font-medium">Dashboard</a>
                <a href="/login" class="block text-gray-600 hover:text-gray-900 font-medium">Login</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="text-center fade-in mb-16">
                <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Upgrade to 
                    <span class="rainbow-text block">Agency Lifetime</span>
                </h1>
                <p class="text-xl lg:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Get unlimited email campaigns with professional warmup, real analytics, and lifetime access to all premium features.
                </p>
            </div>

            <!-- Payment Card -->
            <div class="max-w-4xl mx-auto">
                <div class="glass-card rounded-3xl shadow-2xl border-2 border-blue-500 relative overflow-hidden fade-in hover-glow">
                    <div class="absolute top-0 right-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-2 rounded-bl-2xl">
                        Best Value
                    </div>
                    
                    <div class="p-8 md:p-12">
                        <!-- Plan Info -->
                        <div class="text-center mb-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">Agency Lifetime Plan</h3>
                            <div class="flex items-baseline justify-center mb-6">
                                <span class="text-6xl font-bold text-gray-900">$299</span>
                                <span class="text-gray-600 text-xl ml-2">one-time payment</span>
                            </div>
                            <p class="text-gray-500 text-lg">No monthly fees • Lifetime updates • Priority support</p>
                        </div>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">Unlimited Email Campaigns</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">Professional Email Warmup</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">Real-time Advanced Analytics</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">All SMTP Providers Support</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">Campaign Management Tools</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">Enterprise Scale Delivery</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">Team Collaboration Features</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">Lifetime Updates & Support</span>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-8">
                            <div class="text-center mb-6">
                                <h4 class="text-xl font-bold text-gray-900 mb-2">Complete Your Purchase</h4>
                                <p class="text-gray-600">Secure payment processed by PayPal</p>
                            </div>
                            
                            <!-- PayPal Button -->
                            <form method="POST" action="{{ route('billing.checkout') }}">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-5 rounded-xl text-lg transition-all duration-200 hover-glow hover:shadow-xl mb-4">
                                    Pay $299 with PayPal
                                </button>
                            </form>
                            
                            <p class="text-center text-sm text-gray-500">
                                🔒 256-bit SSL Encryption • 30-Day Money Back Guarantee
                            </p>
                        </div>

                        <!-- Guarantee Badges -->
                        <div class="flex flex-wrap justify-center gap-6 mb-8">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-600">Lifetime Access</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-600">One-time Payment</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-600">Priority Support</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 text-center fade-in">
                    <p class="text-gray-500 mb-4">✅ 100% satisfaction guarantee or your money back within 30 days</p>
                    <p class="text-gray-500">Need help? <a href="mailto:support@infimal.com" class="text-blue-600 hover:underline">Contact our support team</a></p>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="mt-20 fade-in">
                <div class="glass-card rounded-3xl shadow-xl p-8 max-w-6xl mx-auto">
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-8">Why Thousands Trust InfiMal</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">10K+</div>
                            <div class="text-gray-600">Active Agencies</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">99.8%</div>
                            <div class="text-gray-600">Delivery Rate</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">24/7</div>
                            <div class="text-gray-600">Support</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">100%</div>
                            <div class="text-gray-600">Satisfaction</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold rainbow-text mb-4">InfiMal</h3>
                    <p class="text-gray-400">Professional email campaign management platform for agencies and businesses.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/dashboard" class="hover:text-white transition">Dashboard</a></li>
                        <li><a href="/features" class="hover:text-white transition">Features</a></li>
                        <li><a href="/support" class="hover:text-white transition">Support</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Legal</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/privacy" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="/terms" class="hover:text-white transition">Terms & Conditions</a></li>
                        <li><a href="/refund" class="hover:text-white transition">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 InfiMal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Fade in animation
        const fadeElements = document.querySelectorAll('.fade-in');
        const fadeInObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });
        fadeElements.forEach(el => fadeInObserver.observe(el));

        // Mobile menu links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
