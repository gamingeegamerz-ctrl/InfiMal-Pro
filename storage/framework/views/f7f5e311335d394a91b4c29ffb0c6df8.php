<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - InfiMal</title>

    <!-- ========== FAVICON (SIRF M) ========== -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: "Inter", sans-serif; }
        .rainbow-gradient {
            background: linear-gradient(45deg, #FF6B6B, #FF8E53, #FFD166, #06D6A0, #118AB2, #073B4C);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <!-- ========== BRAND LOGO (FULL TEXT) ========== -->
            <div class="flex justify-center mb-6">
                <a href="<?php echo e(url('/')); ?>">
                    <img src="<?php echo e(asset('logo.png')); ?>" alt="INFIMAL" class="h-12 w-auto">
                </a>
            </div>

            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Forgot your password?</h1>
                <p class="text-gray-600 text-sm">Enter your email address and we'll send you a password reset link.</p>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.email')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="<?php echo e(old('email')); ?>"
                        required
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="you@example.com"
                    >
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <button type="submit" class="w-full rainbow-gradient text-white py-3 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition">
                    Send Password Reset Link
                </button>
            </form>

            <p class="text-center mt-6 text-sm text-gray-600">
                Remember your password?
                <a href="<?php echo e(route('login')); ?>" class="text-blue-600 font-semibold hover:text-blue-700">Back to Login</a>
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/InfiMal-Pro/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>