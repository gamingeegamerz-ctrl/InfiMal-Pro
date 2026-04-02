<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Infimal</title>
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

    <!-- Privacy Policy Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                    Privacy <span class="rainbow-text">Policy</span>
                </h1>
                <p class="text-xl lg:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Your privacy is important to us. This policy explains how we collect, use, and protect your information.
                </p>
                <p class="text-gray-500">Last updated: January 2, 2026</p>
            </div>

            <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                <div class="prose prose-lg max-w-none">
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Information We Collect</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We collect information you provide directly to us when you create an account, use our services, 
                                or communicate with us. This may include:
                            </p>
                            <ul class="text-gray-600 list-disc list-inside mt-4 space-y-2">
                                <li>Account information (name, email address, company details)</li>
                                <li>Contact information for your communication recipients</li>
                                <li>Campaign content and performance data</li>
                                <li>Payment and billing information (processed securely by our payment partner Stripe)</li>
                                <li>Communication records and support requests</li>
                                <li>We process recipient data only as a data processor, based on instructions provided by our users</li>
                                <li>We do not own, sell, or independently use recipient contact data</li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">2. How We Use Your Information</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We use the information we collect to:
                            </p>
                            <ul class="text-gray-600 list-disc list-inside mt-4 space-y-2">
                                <li>Provide, maintain, and improve our services</li>
                                <li>Process your transactions and send related information</li>
                                <li>Send you technical notices, updates, and support messages</li>
                                <li>Respond to your comments, questions, and requests</li>
                                <li>Monitor and analyze trends, usage, and activities</li>
                                <li>Detect, prevent, and address technical issues</li>
                                <li>Ensure compliance with our Terms of Service and applicable policies</li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Information Sharing</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We do not sell, trade, or otherwise transfer your personal information to third parties 
                                without your consent, except in the following circumstances:
                            </p>
                            <ul class="text-gray-600 list-disc list-inside mt-4 space-y-2">
                                <li>With your explicit consent</li>
                                <li>To comply with legal obligations</li>
                                <li>To protect and defend our rights and property</li>
                                <li>With service providers who assist in our operations (e.g., Stripe for payment processing)</li>
                                <li>In connection with a business transfer or merger</li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Data Security</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We implement appropriate technical and organizational security measures to protect your 
                                personal information against unauthorized access, alteration, disclosure, or destruction. 
                                This includes encryption, access controls, and regular security assessments.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Your Rights</h2>
                            <p class="text-gray-600 leading-relaxed">
                                You have the right to:
                            </p>
                            <ul class="text-gray-600 list-disc list-inside mt-4 space-y-2">
                                <li>Access and receive a copy of your personal information</li>
                                <li>Rectify or update inaccurate personal information</li>
                                <li>Request deletion of your personal information</li>
                                <li>Restrict or object to our processing of your information</li>
                                <li>Data portability</li>
                                <li>Withdraw consent at any time</li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Cookies and Tracking</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We use cookies and similar tracking technologies to track activity on our service and 
                                hold certain information. Cookies are files with a small amount of data that may include 
                                an anonymous unique identifier.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Campaign Data Processing</h2>
                            <p class="text-gray-600 leading-relaxed">
                                Infimal acts as a data processor for recipient information you upload to our platform. 
                                We process this data solely based on your instructions and do not independently use, sell, 
                                or share recipient data with third parties.
                            </p>
                            <p class="text-gray-600 leading-relaxed mt-4">
                                <strong>Compliance Requirements:</strong> You are required to ensure all recipients have 
                                explicitly opted in to receive communications. We do not permit the use of purchased, 
                                scraped, or third-party contact lists. Violation of these terms may result in immediate 
                                account suspension.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Changes to This Policy</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We may update our Privacy Policy from time to time. We will notify you of any changes by 
                                posting the new Privacy Policy on this page and updating the "Last updated" date.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Contact Us</h2>
                            <p class="text-gray-600 leading-relaxed">
                                If you have any questions about this Privacy Policy, please contact us at:
                            </p>
                            <p class="text-gray-600 mt-2">
                                Email: privacy@infimal.site<br>
                                Business Location: India (Operating Globally)
                            </p>
                        </div>
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
                        <li><a href="/refund" class="hover:text-white transition">Refund Policy</a></li>
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