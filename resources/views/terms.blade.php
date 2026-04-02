<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Infimal</title>
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

    <!-- Terms of Service Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                    Terms of <span class="rainbow-text">Service</span>
                </h1>
                <p class="text-xl lg:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Please read these terms carefully before using our services.
                </p>
                <p class="text-gray-500">Last updated: January 2, 2026</p>
            </div>

            <div class="glass-card rounded-2xl p-8 shadow-lg fade-in hover-glow transition-all duration-300">
                <div class="prose prose-lg max-w-none">
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Agreement to Terms</h2>
                            <p class="text-gray-600 leading-relaxed">
                                By accessing or using Infimal's services, you agree to be bound by these Terms of Service 
                                and our Privacy Policy. If you disagree with any part of the terms, you may not access our services.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Account Registration</h2>
                            <p class="text-gray-600 leading-relaxed">
                                You must be at least 18 years old to use our services. When you create an account, you agree to:
                            </p>
                            <ul class="text-gray-600 list-disc list-inside mt-4 space-y-2">
                                <li>Provide accurate, current, and complete information</li>
                                <li>Maintain and promptly update your account information</li>
                                <li>Maintain the security of your password and accept all risks of unauthorized access</li>
                                <li>Notify us immediately of any unauthorized use of your account</li>
                                <li>Take responsibility for all activities that occur under your account</li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Service Usage & Prohibited Activities</h2>
                            <p class="text-gray-600 leading-relaxed">
                                You agree not to use our services to:
                            </p>
                            <ul class="text-gray-600 list-disc list-inside mt-4 space-y-2">
                                <li>Send unsolicited commercial communications</li>
                                <li>Use the platform without recipient consent</li>
                                <li>Upload purchased, scraped, or third-party contact lists without explicit consent</li>
                                <li>Bypass opt-out or unsubscribe mechanisms</li>
                                <li>Send communications to recipients who have not explicitly opted in</li>
                                <li>Violate any laws or regulations, including CAN-SPAM, GDPR, and similar regulations</li>
                                <li>Infringe upon any intellectual property rights</li>
                                <li>Transmit any malicious code or viruses</li>
                                <li>Harass, abuse, or harm another person</li>
                                <li>Collect or track personal information of others without consent</li>
                                <li>Impersonate any person or entity</li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Payment Terms</h2>
                            <p class="text-gray-600 leading-relaxed">
                                Our services are offered as a one-time lifetime payment processed through Stripe. By purchasing our services, you agree to:
                            </p>
                            <ul class="text-gray-600 list-disc list-inside mt-4 space-y-2">
                                <li>Pay all applicable fees for the services you select</li>
                                <li>Provide accurate billing and contact information</li>
                                <li>Authorize us to charge your payment method for the agreed amount</li>
                                <li>Understand that refunds are subject to our Refund Policy</li>
                                <li>Acknowledge that a 30-day money-back guarantee is provided for all purchases</li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Email Campaign Management & Compliance</h2>
                            <p class="text-gray-600 leading-relaxed">
                                Infimal is a software platform that provides tools for creating and managing customer communication campaigns. 
                                You acknowledge and agree that:
                            </p>
                            <ul class="text-gray-600 list-disc list-inside mt-4 space-y-2">
                                <li>You are solely responsible for all communication content and recipient lists</li>
                                <li>All recipients must have explicitly opted in to receive your communications</li>
                                <li>You must comply with CAN-SPAM, GDPR, and all applicable regulations</li>
                                <li>We provide tools for campaign management; we do not send communications on your behalf</li>
                                <li>We do not provide, sell, or facilitate access to contact lists</li>
                                <li>Violation of anti-spam policies will result in immediate account termination without refund</li>
                                <li>You must include working unsubscribe mechanisms in all campaigns</li>
                                <li>You are responsible for maintaining accurate suppression lists and honoring opt-out requests</li>
                            </ul>
                            <p class="text-gray-600 leading-relaxed mt-4">
                                Infimal reserves the right to suspend accounts that engage in prohibited activities or violate best practices without prior notice.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Intellectual Property</h2>
                            <p class="text-gray-600 leading-relaxed">
                                The Service and its original content, features, and functionality are and will remain the 
                                exclusive property of Infimal and its licensors. Our trademarks and trade dress may not be 
                                used in connection with any product or service without the prior written consent of Infimal.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">7. User Content</h2>
                            <p class="text-gray-600 leading-relaxed">
                                You retain all rights to any content you submit, post, or display on or through our services. 
                                By submitting content, you grant us a worldwide, non-exclusive, royalty-free license to use, 
                                reproduce, and display such content solely for the purpose of providing our services to you.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Termination</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We may terminate or suspend your account immediately, without prior notice or liability, 
                                for any reason whatsoever, including without limitation if you breach the Terms. Upon 
                                termination, your right to use the Service will immediately cease. Termination for violation 
                                of our policies will not result in a refund.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Limitation of Liability</h2>
                            <p class="text-gray-600 leading-relaxed">
                                In no event shall Infimal, nor its directors, employees, partners, agents, suppliers, or 
                                affiliates, be liable for any indirect, incidental, special, consequential or punitive 
                                damages, including without limitation, loss of profits, data, use, goodwill, or other 
                                intangible losses, resulting from your access to or use of or inability to access or use 
                                the services.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Governing Law</h2>
                            <p class="text-gray-600 leading-relaxed">
                                These Terms shall be governed by the laws of India, without regard to conflict of law principles. 
                                Any disputes arising from these terms will be resolved in accordance with the jurisdiction 
                                where Infimal operates.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Changes to Terms</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We reserve the right, at our sole discretion, to modify or replace these Terms at any time. 
                                If a revision is material, we will provide at least 30 days' notice prior to any new terms 
                                taking effect.
                            </p>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Contact Information</h2>
                            <p class="text-gray-600 leading-relaxed">
                                If you have any questions about these Terms, please contact us at:
                            </p>
                            <p class="text-gray-600 mt-2">
                                Email: legal@infimal.site<br>
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