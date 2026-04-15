<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - InfiMal</title>
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
                    <a href="/" class="text-2xl font-bold rainbow-text">InfiMal</a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/features" class="text-gray-600 hover:text-gray-900 font-medium transition">Features</a>
                    <a href="/pricing" class="text-gray-600 hover:text-gray-900 font-medium transition">Pricing</a>
                    <a href="/blog" class="text-gray-600 hover:text-gray-900 font-medium transition">Blog</a>
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
                <a href="/blog" class="block text-gray-600 hover:text-gray-900 font-medium">Blog</a>
                <a href="/login" class="block text-gray-600 hover:text-gray-900 font-medium">Login</a>
                <a href="/register" class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium text-center hover-glow">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Blog Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                    Our <span class="rainbow-text">Blog</span>
                </h1>
                <p class="text-xl lg:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Insights, tips, and strategies to help you master email marketing and grow your business.
                </p>
            </div>

            <!-- Featured Post -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-12 fade-in hover-glow transition-all duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <span class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full mb-4">
                            Featured
                        </span>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">The Ultimate Guide to Email Marketing in 2024</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Discover the latest trends, strategies, and best practices for email marketing that actually work. 
                            Learn how to increase open rates, boost engagement, and drive conversions with our comprehensive guide.
                        </p>
                        <div class="flex items-center text-gray-500 text-sm">
                            <span>November 26, 2024</span>
                            <span class="mx-2">•</span>
                            <span>10 min read</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl h-64 flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">Featured Post</span>
                    </div>
                </div>
            </div>

            <!-- Blog Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Post 1 -->
                <div class="glass-card rounded-2xl p-6 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="bg-gradient-to-br from-green-400 to-blue-500 rounded-xl h-40 mb-4 flex items-center justify-center">
                        <span class="text-white font-bold">Campaign Strategies</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">5 Campaign Strategies That Convert</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Learn the top 5 email campaign strategies that successful businesses use to drive conversions and revenue.
                    </p>
                    <div class="flex items-center text-gray-500 text-xs">
                        <span>Nov 20, 2024</span>
                        <span class="mx-2">•</span>
                        <span>7 min read</span>
                    </div>
                </div>

                <!-- Post 2 -->
                <div class="glass-card rounded-2xl p-6 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl h-40 mb-4 flex items-center justify-center">
                        <span class="text-white font-bold">Analytics Guide</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Understanding Email Analytics</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        A comprehensive guide to understanding and leveraging email analytics for better campaign performance.
                    </p>
                    <div class="flex items-center text-gray-500 text-xs">
                        <span>Nov 15, 2024</span>
                        <span class="mx-2">•</span>
                        <span>5 min read</span>
                    </div>
                </div>

                <!-- Post 3 -->
                <div class="glass-card rounded-2xl p-6 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="bg-gradient-to-br from-orange-400 to-red-500 rounded-xl h-40 mb-4 flex items-center justify-center">
                        <span class="text-white font-bold">Best Practices</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Email Marketing Best Practices</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Essential best practices every marketer should follow for successful email marketing campaigns.
                    </p>
                    <div class="flex items-center text-gray-500 text-xs">
                        <span>Nov 10, 2024</span>
                        <span class="mx-2">•</span>
                        <span>8 min read</span>
                    </div>
                </div>

                <!-- Post 4 -->
                <div class="glass-card rounded-2xl p-6 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="bg-gradient-to-br from-indigo-400 to-purple-500 rounded-xl h-40 mb-4 flex items-center justify-center">
                        <span class="text-white font-bold">Automation Tips</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Mastering Email Automation</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        How to set up effective email automation workflows that save time and increase engagement.
                    </p>
                    <div class="flex items-center text-gray-500 text-xs">
                        <span>Nov 5, 2024</span>
                        <span class="mx-2">•</span>
                        <span>6 min read</span>
                    </div>
                </div>

                <!-- Post 5 -->
                <div class="glass-card rounded-2xl p-6 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="bg-gradient-to-br from-teal-400 to-blue-500 rounded-xl h-40 mb-4 flex items-center justify-center">
                        <span class="text-white font-bold">GDPR Guide</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">GDPR Compliance for Email Marketing</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Everything you need to know about GDPR compliance and email marketing regulations.
                    </p>
                    <div class="flex items-center text-gray-500 text-xs">
                        <span>Nov 1, 2024</span>
                        <span class="mx-2">•</span>
                        <span>9 min read</span>
                    </div>
                </div>

                <!-- Post 6 -->
                <div class="glass-card rounded-2xl p-6 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="bg-gradient-to-br from-pink-400 to-rose-500 rounded-xl h-40 mb-4 flex items-center justify-center">
                        <span class="text-white font-bold">ROI Tips</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Maximizing Email Marketing ROI</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Proven strategies to maximize your return on investment from email marketing campaigns.
                    </p>
                    <div class="flex items-center text-gray-500 text-xs">
                        <span>Oct 25, 2024</span>
                        <span class="mx-2">•</span>
                        <span>7 min read</span>
                    </div>
                </div>
            </div>

            <!-- Newsletter CTA -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mt-12 text-center fade-in hover-glow transition-all duration-300">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Stay Updated</h3>
                <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                    Get the latest email marketing tips, strategies, and industry insights delivered to your inbox.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
                    <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover-glow transition-all duration-300">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold rainbow-text mb-4">InfiMal</h3>
                    <p class="text-gray-400">Professional email marketing platform for agencies and businesses.</p>
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
                        <li><a href="/blog" class="hover:text-white transition">Blog</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Legal</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/privacy" class="hover:text-white transition">Privacy</a></li>
                        <li><a href="/terms" class="hover:text-white transition">Terms</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 InfiMal. All rights reserved.</p>
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
