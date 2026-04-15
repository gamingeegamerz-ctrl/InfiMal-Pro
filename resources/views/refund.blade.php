<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Policy - Infimal</title>
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
<body class="bg-white" x-data="{ mobileMenuOpen: false }">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-md z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex items-center">
            <img src="{{ asset('logo.png') }}" alt="INFIMAL" class="h-12 w-auto">
        </a>
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold rainbow-text">Infimal</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/features" class="text-gray-600 hover:text-gray-900 font-medium transition">Features</a>
                    <a href="/pricing" class="text-gray-600 hover:text-gray-900 font-medium transition">Pricing</a>
                    <a href="/login" class="text-gray-600 hover:text-gray-900 font-medium transition">Login</a>
                    <a href="/register" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-medium hover-glow transition-all duration-300">
                        Get Started
                    </a>
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
                <a href="/features" class="block text-gray-600 hover:text-gray-900 font-medium">Features</a>
                <a href="/pricing" class="block text-gray-600 hover:text-gray-900 font-medium">Pricing</a>
                <a href="/login" class="block text-gray-600 hover:text-gray-900 font-medium">Login</a>
                <a href="/register" class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium text-center hover-glow">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Refund Policy Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12 fade-in">
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    Refund <span class="rainbow-text">Policy</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-3xl mx-auto">
                    Last Updated: January 2, 2026
                </p>
            </div>

            <!-- 30-Day Guarantee -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-8 fade-in hover-glow transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">30-Day Money-Back Guarantee</h2>
                </div>
                
                <div class="space-y-6 text-gray-700 leading-relaxed">
                    <p class="text-lg">
                        Infimal provides a <strong>30-day money-back guarantee</strong> for all purchases.
                    </p>
                    
                    <p>
                        If you are not satisfied with your purchase for any reason, you may request a full refund within 30 days of the original transaction date.
                    </p>
                    
                    <p>
                        No explanation or justification is required to request a refund during this period.
                    </p>
                    
                    <p>
                        All refund requests made within the 30-day period will be processed promptly and honored in full.
                    </p>
                </div>
            </div>

            <!-- Refund Process -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-8 fade-in hover-glow transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">How to Request a Refund</h2>
                </div>
                
                <div class="space-y-6">
                    <p class="text-gray-700 leading-relaxed">
                        To request a refund, please contact us at:
                    </p>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                        <a href="mailto:support@infimal.site" class="text-2xl font-semibold text-blue-600 hover:text-blue-700 transition">
                            support@infimal.site
                        </a>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6">
                        <p class="text-gray-700 mb-3"><strong>Please include in your email:</strong></p>
                        <ul class="list-disc pl-5 text-gray-700 space-y-2">
                            <li>Your purchase email address</li>
                            <li>Invoice or transaction reference number</li>
                            <li>Date of purchase</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Processing Time -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-8 fade-in hover-glow transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Refund Processing</h2>
                </div>
                
                <div class="space-y-4 text-gray-700 leading-relaxed">
                    <p>
                        Once your refund request is received and approved, it will be processed within 5-7 business days.
                    </p>
                    <p>
                        Refunds are issued to the original payment method used at the time of purchase.
                    </p>
                    <p>
                        Depending on your payment provider, it may take an additional 5-10 business days for the refund to appear in your account.
                    </p>
                </div>
            </div>

            <!-- Payment Provider -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-8 fade-in hover-glow transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Payment Processing</h2>
                </div>
                
                <p class="text-gray-700 leading-relaxed">
                    All payments for Infimal are processed securely through <strong>Stripe</strong>, our trusted payment partner. 
                    Stripe handles all payment processing and refunds in accordance with industry-standard security practices.
                </p>
            </div>

            <!-- Contact Section -->
            <div class="glass-card rounded-2xl p-8 shadow-lg text-center fade-in hover-glow transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Questions About Refunds?</h3>
                <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                    Our support team is here to help with any refund inquiries or questions you may have.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="mailto:support@infimal.site?subject=Refund Request" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-lg font-medium hover-glow transition-all duration-300">
                        Request Refund
                    </a>
                    <a href="/contact" class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-medium hover:bg-gray-50 transition">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold rainbow-text mb-4">Infimal</h3>
                    <p class="text-gray-400">Professional customer communication management platform for businesses.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/features" class="hover:text-white transition">Features</a></li>
                        <li><a href="/pricing" class="hover:text-white transition">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/about" class="hover:text-white transition">About</a></li>
                        <li><a href="/contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Legal</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/privacy" class="hover:text-white transition">Privacy</a></li>
                        <li><a href="/terms" class="hover:text-white transition">Terms</a></li>
                        <li><a href="/refund" class="text-white font-medium">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 Infimal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const fadeElements = document.querySelectorAll('.fade-in');
        const fadeInObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });
        fadeElements.forEach(el => fadeInObserver.observe(el));
    </script>
</body>
</html>