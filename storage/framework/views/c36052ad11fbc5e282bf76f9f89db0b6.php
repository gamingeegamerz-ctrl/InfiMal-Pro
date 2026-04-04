<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfiMal - Professional Customer Communication Platform</title>
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
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold rainbow-text">InfiMal</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-gray-900 font-medium transition">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-gray-900 font-medium transition">How It Works</a>
                    <a href="#pricing" class="text-gray-600 hover:text-gray-900 font-medium transition">Pricing</a>
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
                <a href="#features" class="block text-gray-600 hover:text-gray-900 font-medium">Features</a>
                <a href="#how-it-works" class="block text-gray-600 hover:text-gray-900 font-medium">How It Works</a>
                <a href="#pricing" class="block text-gray-600 hover:text-gray-900 font-medium">Pricing</a>
                <a href="/login" class="block text-gray-600 hover:text-gray-900 font-medium">Login</a>
                <a href="/register" class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium text-center hover-glow">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center fade-in">
                <h1 class="text-5xl lg:text-7xl font-bold text-gray-900 mb-6 leading-tight">
                    Customer Communication
                    <span class="rainbow-text block">Made Professional</span>
                </h1>
                <p class="text-xl lg:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Transform your customer outreach with our enterprise-grade platform. Build, send, and track professional campaigns with advanced analytics and automation tools designed for modern businesses.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="/register" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-12 py-4 rounded-2xl font-semibold text-lg hover-glow transition-all duration-300 transform hover:scale-105">
                        Get Started
                    </a>
                    <a href="#features" class="border-2 border-gray-300 text-gray-700 px-12 py-4 rounded-2xl font-semibold text-lg hover:bg-gray-50 transition-all duration-300">
                        See Features
                    </a>
                </div>
                <p class="text-gray-500 mt-6 text-sm">One-time payment • Lifetime access • Cancel anytime</p>
            </div>

            <!-- Dashboard Preview -->
            <div class="mt-20 fade-in">
                <div class="glass-card rounded-3xl shadow-2xl p-4 max-w-6xl mx-auto">
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6">
                        <div class="flex space-x-2 mb-4">
                            <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-800 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-white">98.7%</div>
                                <div class="text-gray-400 text-sm">Delivery Rate</div>
                            </div>
                            <div class="bg-gray-800 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-white">24.3%</div>
                                <div class="text-gray-400 text-sm">Open Rate</div>
                            </div>
                            <div class="bg-gray-800 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-white">Enterprise</div>
                                <div class="text-gray-400 text-sm">Scale Ready</div>
                            </div>
                        </div>
                        <div class="mt-6 text-center text-gray-500 text-sm">
                            Real Campaign Performance Metrics
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center fade-in">
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">10K+</div>
                    <div class="text-gray-600">Active Users</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">50M+</div>
                    <div class="text-gray-600">Messages Delivered</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">99.9%</div>
                    <div class="text-gray-600">Uptime</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">24/7</div>
                    <div class="text-gray-600">Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Powerful Features for Modern Teams</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Everything you need to create, send, and optimize professional campaigns that drive results.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Scalable Message Delivery</h3>
                    <p class="text-gray-600">Enterprise-grade infrastructure with intelligent throttling ensures your campaigns reach inboxes quickly. Support for multiple SMTP providers with automatic failover keeps your deliverability high.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Real-time Analytics</h3>
                    <p class="text-gray-600">Track every aspect of your campaigns with comprehensive dashboards. Monitor opens, clicks, bounces, and conversions in real-time to make data-driven decisions that improve performance.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Secure & Compliant</h3>
                    <p class="text-gray-600">Bank-level encryption protects your data. Full compliance with GDPR, CAN-SPAM, and international regulations. Automated list hygiene keeps your sender reputation pristine.</p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Drag & Drop Builder</h3>
                    <p class="text-gray-600">Create stunning messages without coding. Our intuitive visual editor includes mobile-responsive templates, custom branding options, and dynamic content blocks for personalized campaigns.</p>
                </div>

                <!-- Feature 5 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Smart Automation</h3>
                    <p class="text-gray-600">Set up sophisticated workflows that trigger based on subscriber behavior. Welcome series, abandoned cart messages, and re-engagement campaigns run automatically while you focus on strategy.</p>
                </div>

                <!-- Feature 6 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Team Collaboration</h3>
                    <p class="text-gray-600">Work together seamlessly with role-based permissions, shared templates, and collaborative campaign workflows. Perfect for agencies managing multiple client accounts.</p>
                </div>

                <!-- Feature 7 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-pink-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Subscriber Management</h3>
                    <p class="text-gray-600">Organize subscribers with advanced segmentation tools. Import contacts easily, manage opt-ins and opt-outs automatically, and keep lists clean with built-in validation and deduplication.</p>
                </div>

                <!-- Feature 8 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-teal-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">A/B Testing</h3>
                    <p class="text-gray-600">Optimize every campaign with split testing. Test subject lines, content, send times, and more. Get statistical significance reports to confidently choose winning variations.</p>
                </div>

                <!-- Feature 9 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">API Integration</h3>
                    <p class="text-gray-600">Connect with your favorite tools via our RESTful API. Integrate with CRMs, e-commerce platforms, and custom applications. Webhooks provide real-time event notifications.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600">Get started in minutes with our simple 4-step process</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center fade-in">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Create Account</h3>
                    <p class="text-gray-600">Sign up in seconds. Get instant access to our professional platform.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center fade-in">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Import Contacts</h3>
                    <p class="text-gray-600">Upload your subscriber list via CSV or connect your existing tools through our API integrations.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center fade-in">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Design Campaign</h3>
                    <p class="text-gray-600">Use our drag-and-drop builder to create beautiful, responsive messages that match your brand.</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center fade-in">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">4</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Send & Track</h3>
                    <p class="text-gray-600">Launch your campaign and monitor real-time performance with detailed analytics and insights.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Use Cases Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Perfect for Every Business</h2>
                <p class="text-xl text-gray-600">Whatever your industry, InfiMal scales with your needs</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Use Case 1 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow">
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">SaaS Companies</h3>
                    <p class="text-gray-600">Onboard new users, share product updates, and reduce churn with targeted drip campaigns and behavioral triggers.</p>
                </div>

                <!-- Use Case 2 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow">
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">E-commerce</h3>
                    <p class="text-gray-600">Recover abandoned carts, announce sales, and build customer loyalty with personalized product recommendations.</p>
                </div>

                <!-- Use Case 3 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow">
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Marketing Agencies</h3>
                    <p class="text-gray-600">Manage multiple client campaigns with team collaboration tools, white-label options, and detailed reporting.</p>
                </div>

                <!-- Use Case 4 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow">
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Content Creators</h3>
                    <p class="text-gray-600">Grow your audience with newsletters, course launches, and community updates that keep subscribers engaged.</p>
                </div>

                <!-- Use Case 5 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow">
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">B2B Services</h3>
                    <p class="text-gray-600">Nurture leads through the sales funnel with educational content, case studies, and personalized outreach.</p>
                </div>

                <!-- Use Case 6 -->
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow">
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Nonprofits</h3>
                    <p class="text-gray-600">Connect with donors, share impact stories, and organize fundraising campaigns with personalized donor journeys.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose InfiMal</h2>
                <p class="text-xl text-gray-600">Built for businesses that value reliability and results</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="fade-in">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise-Grade Infrastructure</h3>
                    <p class="text-gray-600 mb-4">Our platform is built on robust architecture that handles millions of messages daily. With automatic scaling, redundant systems, and global delivery networks, your campaigns reach customers reliably no matter where they are.</p>
                    <p class="text-gray-600">We maintain 99.9% uptime with 24/7 monitoring and instant failover capabilities to ensure your business never misses a beat.</p>
                </div>

                <div class="fade-in">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Data-Driven Optimization</h3>
                    <p class="text-gray-600 mb-4">Every campaign generates valuable insights. Our advanced analytics engine tracks engagement metrics, subscriber behavior, and conversion patterns to help you understand what resonates with your audience.</p>
                    <p class="text-gray-600">Use these insights to continuously improve your messaging strategy and achieve better results over time.</p>
                </div>

                <div class="fade-in">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Compliance & Security First</h3>
                    <p class="text-gray-600 mb-4">We take data protection seriously. InfiMal is fully compliant with GDPR, CAN-SPAM, and other international regulations. All data is encrypted in transit and at rest using industry-standard protocols.</p>
                    <p class="text-gray-600">Our platform includes built-in tools for consent management, unsubscribe handling, and data retention policies to keep you compliant automatically.</p>
                </div>

                <div class="fade-in">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Seamless Integrations</h3>
                    <p class="text-gray-600 mb-4">Connect InfiMal with your existing tech stack through our comprehensive API and pre-built integrations. Sync data with CRMs, e-commerce platforms, analytics tools, and more.</p>
                    <p class="text-gray-600">Webhooks enable real-time event notifications, allowing you to build sophisticated workflows that respond instantly to subscriber actions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Trusted by Leading Teams</h2>
                <p class="text-xl text-gray-600">See what our customers say about their experience</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"InfiMal transformed how we communicate with our customers. The automation features saved us countless hours while improving engagement rates significantly."</p>
                    <p class="font-semibold text-gray-900">Sarah Johnson</p>
                    <p class="text-sm text-gray-500">Marketing Director, TechStart</p>
                </div>

                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"The analytics dashboard gives us insights we never had before. We can now make data-driven decisions that actually move the needle on customer retention."</p>
                    <p class="font-semibold text-gray-900">Michael Chen</p>
                    <p class="text-sm text-gray-500">CEO, GrowthLabs</p>
                </div>

                <div class="glass-card rounded-2xl p-8 shadow-lg fade-in">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"Reliable, powerful, and easy to use. InfiMal handles our high-volume campaigns without breaking a sweat. The deliverability rates are exceptional."</p>
                    <p class="font-semibold text-gray-900">Emily Rodriguez</p>
                    <p class="text-sm text-gray-500">Head of Operations, ScaleUp Inc</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600">Everything you need to know about InfiMal</p>
            </div>

            <div class="space-y-6" x-data="{ openFaq: null }">
                <div class="glass-card rounded-2xl shadow-lg fade-in">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full p-6 text-left flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">What types of campaigns can I run with InfiMal?</span>
                        <svg class="w-6 h-6 text-gray-600 transform transition-transform" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-transition class="px-6 pb-6">
                        <p class="text-gray-600">InfiMal supports all types of customer communication campaigns including transactional messages, marketing announcements, product updates, onboarding sequences, re-engagement campaigns, and automated drip sequences. You can create both one-time broadcasts and complex multi-step automation workflows.</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-lg fade-in">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full p-6 text-left flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">How does InfiMal ensure high deliverability?</span>
                        <svg class="w-6 h-6 text-gray-600 transform transition-transform" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-transition class="px-6 pb-6">
                        <p class="text-gray-600">We use intelligent throttling algorithms, support multiple SMTP providers with automatic failover, maintain clean IP reputation through list hygiene tools, and implement industry best practices for authentication including SPF, DKIM, and DMARC. Our infrastructure is optimized to maximize inbox placement rates.</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-lg fade-in">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full p-6 text-left flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Can I integrate InfiMal with my existing tools?</span>
                        <svg class="w-6 h-6 text-gray-600 transform transition-transform" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-transition class="px-6 pb-6">
                        <p class="text-gray-600">Yes. InfiMal offers a comprehensive RESTful API that allows you to integrate with CRMs, e-commerce platforms, analytics tools, and custom applications. We also provide webhooks for real-time event notifications and support standard data import formats including CSV and API endpoints.</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-lg fade-in">
                    <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full p-6 text-left flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">What kind of analytics and reporting does InfiMal provide?</span>
                        <svg class="w-6 h-6 text-gray-600 transform transition-transform" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-transition class="px-6 pb-6">
                        <p class="text-gray-600">Our analytics dashboard tracks all key metrics in real-time including delivery rates, open rates, click-through rates, bounce rates, unsubscribe rates, and conversions. You can segment data by campaign, time period, subscriber groups, and more. Export detailed reports for deeper analysis or stakeholder presentations.</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-lg fade-in">
                    <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full p-6 text-left flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Is my data secure with InfiMal?</span>
                        <svg class="w-6 h-6 text-gray-600 transform transition-transform" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 5" x-transition class="px-6 pb-6">
                        <p class="text-gray-600">Absolutely. We use bank-level encryption for all data in transit and at rest. InfiMal is fully compliant with GDPR, CAN-SPAM, and international data protection regulations. Our infrastructure includes redundant backups, 24/7 security monitoring, and regular security audits to protect your data.</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-lg fade-in">
                    <button @click="openFaq = openFaq === 6 ? null : 6" class="w-full p-6 text-left flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Do you offer customer support?</span>
                        <svg class="w-6 h-6 text-gray-600 transform transition-transform" :class="{ 'rotate-180': openFaq === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 6" x-transition class="px-6 pb-6">
                        <p class="text-gray-600">Yes. All InfiMal customers receive priority support through email and our help center. We provide comprehensive documentation, video tutorials, and best practice guides. Our support team is available to help with technical issues, campaign optimization, and strategic guidance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Simple Lifetime Pricing</h2>
                <p class="text-xl text-gray-600">One payment, lifetime access</p>
            </div>

            <div class="glass-card rounded-3xl shadow-2xl border-2 border-blue-500 relative overflow-hidden fade-in hover-glow">
                <div class="absolute top-0 right-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-2 rounded-bl-2xl">
                    Most Popular
                </div>
                
                <div class="p-12 text-center">
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">Agency Lifetime</h3>
                    <div class="flex items-baseline justify-center mb-8">
                        <span class="text-6xl font-bold text-gray-900">$299</span>
                        <span class="text-gray-600 text-xl ml-2">one-time</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left mb-12">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Campaign Management Tools</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Enterprise Scale Delivery</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">All SMTP Provider Support</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Real-time Analytics Dashboard</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Team Collaboration Features</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Lifetime Updates & Support</span>
                        </div>
                    </div>
                    
                    <a href="/register" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-16 py-4 rounded-2xl font-semibold text-lg inline-block hover-glow transition-all duration-300 transform hover:scale-105">
                        Get Started Now
                    </a>
                    
                    <p class="text-gray-500 mt-6 text-sm">No hidden fees • No monthly subscriptions • Priority support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-20 bg-gradient-to-br from-blue-600 to-purple-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-in">
            <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">Ready to Transform Your Customer Communication?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">Join thousands of businesses using InfiMal to build meaningful relationships with their customers through professional, scalable campaigns.</p>
            <a href="/register" class="bg-white text-blue-600 px-12 py-4 rounded-2xl font-semibold text-lg inline-block hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                Get Started
            </a>
            <p class="text-blue-100 mt-6 text-sm">One-time payment • Lifetime access • Cancel anytime</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold rainbow-text mb-4">InfiMal</h3>
                    <p class="text-gray-400">Professional customer communication management platform for agencies and businesses.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#how-it-works" class="hover:text-white transition">How It Works</a></li>
                        <li><a href="#pricing" class="hover:text-white transition">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="https://infimal.site/about" class="hover:text-white transition">About</a></li>
                        <li><a href="https://infimal.site/contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Legal</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="https://infimal.site/privacy" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="https://infimal.site/terms" class="hover:text-white transition">Terms & Conditions</a></li>
                        <li><a href="https://infimal.site/refund" class="hover:text-white transition">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 InfiMal. All rights reserved.</p>
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
</html><?php /**PATH /var/www/infimal--Local/resources/views/public/index.blade.php ENDPATH**/ ?>