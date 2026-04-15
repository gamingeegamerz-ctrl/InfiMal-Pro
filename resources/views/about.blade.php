<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Infimal</title>
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
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold rainbow-text">Infimal</a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/features" class="text-gray-600 hover:text-gray-900 font-medium transition">Features</a>
                    <a href="/pricing" class="text-gray-600 hover:text-gray-900 font-medium transition">Pricing</a>
                    <a href="/login" class="text-gray-600 hover:text-gray-900 font-medium transition">Login</a>
                    <a href="/register" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-medium hover-glow transition-all duration-300">
                        Get Started
                    </a>
                </div>

                <!-- Mobile menu button -->
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

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t border-gray-200">
            <div class="px-4 py-4 space-y-4">
                <a href="/features" class="block text-gray-600 hover:text-gray-900 font-medium">Features</a>
                <a href="/pricing" class="block text-gray-600 hover:text-gray-900 font-medium">Pricing</a>
                <a href="/login" class="block text-gray-600 hover:text-gray-900 font-medium">Login</a>
                <a href="/register" class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium text-center hover-glow">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- About Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                    About <span class="rainbow-text">Infimal</span>
                </h1>
                <p class="text-xl lg:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                    A self-service email campaign management platform empowering businesses to communicate effectively with their subscribers.
                </p>
            </div>

            <!-- Mission & Vision -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                    <p class="text-gray-600 leading-relaxed">
                        To provide accessible email campaign management tools that help businesses communicate effectively with their customers. We believe in empowering businesses with technology while maintaining strict compliance and ethical practices.
                    </p>
                </div>

                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed">
                        To become a trusted platform for permission-based email communication worldwide. We envision a future where businesses can engage their audiences effectively while respecting privacy and maintaining the highest standards of compliance.
                    </p>
                </div>
            </div>

            <!-- Compliance & Responsibility Section -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-16 fade-in hover-glow transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Responsible Email Management</h3>
                </div>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        Infimal is a self-service email campaign management platform. We provide the tools, but you maintain full control and responsibility for your communications.
                    </p>
                    <p>
                        <strong>What we provide:</strong> Software tools for creating, scheduling, and managing email campaigns with your own subscriber lists.
                    </p>
                    <p>
                        <strong>What we don't do:</strong> We do not send emails on your behalf, provide email lists, or engage in any form of unsolicited messaging.
                    </p>
                    <p>
                        <strong>Your Responsibility:</strong> All subscribers must have explicitly opted in to receive your emails. You are solely responsible for compliance with CAN-SPAM, GDPR, and all applicable email regulations.
                    </p>
                    <p>
                        <strong>Anti-Spam Policy:</strong> We strictly prohibit the use of purchased, scraped, or third-party email lists. Violation of these terms may result in immediate account suspension.
                    </p>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
                <div class="glass-card rounded-2xl p-6 text-center fade-in hover-glow transition-all duration-300">
                    <div class="text-3xl font-bold text-gray-900 mb-2">10K+</div>
                    <div class="text-gray-600">Businesses</div>
                </div>
                <div class="glass-card rounded-2xl p-6 text-center fade-in hover-glow transition-all duration-300">
                    <div class="text-3xl font-bold text-gray-900 mb-2">50M+</div>
                    <div class="text-gray-600">Campaigns Managed</div>
                </div>
                <div class="glass-card rounded-2xl p-6 text-center fade-in hover-glow transition-all duration-300">
                    <div class="text-3xl font-bold text-gray-900 mb-2">99.9%</div>
                    <div class="text-gray-600">Uptime</div>
                </div>
                <div class="glass-card rounded-2xl p-6 text-center fade-in hover-glow transition-all duration-300">
                    <div class="text-3xl font-bold text-gray-900 mb-2">24/7</div>
                    <div class="text-gray-600">Support</div>
                </div>
            </div>

            <!-- Team Values -->
            <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Our Values</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Compliance & Ethics</h4>
                        <p class="text-gray-600 text-sm">We prioritize ethical practices and strict compliance with email regulations worldwide.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Innovation</h4>
                        <p class="text-gray-600 text-sm">We continuously improve our platform with features that help businesses succeed responsibly.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Customer Success</h4>
                        <p class="text-gray-600 text-sm">Your success is our success. We're committed to helping you achieve your goals.</p>
                    </div>
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
                    <p class="text-gray-400">Self-service email campaign management platform for businesses.</p>
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
                        <li><a href="/refund" class="hover:text-white transition">Refund Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Infimal. All rights reserved.</p>
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
    </script>
</body>
</html>