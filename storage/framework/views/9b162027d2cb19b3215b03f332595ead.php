<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => ['title' => 'SMTP Accounts']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'SMTP Accounts']); ?>
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8 animate-slide-up">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 mb-2">SMTP Accounts</h1>
            <p class="text-primary-600">Manage your email delivery servers and configurations</p>
        </div>
        <a href="<?php echo e(route('smtp.create')); ?>"
           class="bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-server"></i>
            Add SMTP Account
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 animate-scale-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Total Accounts</p>
                    <p class="text-3xl font-bold text-primary-900"><?php echo e($accounts->count()); ?></p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-server text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Active Accounts</p>
                    <p class="text-3xl font-bold text-accent-600"><?php echo e($accounts->where('is_active', true)->count()); ?></p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Verified</p>
                    <p class="text-3xl font-bold text-primary-900"><?php echo e($accounts->where('status', 'verified')->count()); ?></p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-shield-alt text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Today's Usage</p>
                    <p class="text-3xl font-bold text-accent-600"><?php echo e($accounts->sum('emails_sent_today')); ?></p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-600 to-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if(session('success')): ?>
        <div class="mb-6 p-4 glass-effect border border-green-200/50 rounded-xl animate-slide-up">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-500/20 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <p class="text-green-300"><?php echo e(session('success')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-6 p-4 glass-effect border border-red-200/50 rounded-xl animate-slide-up">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-500/20 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <p class="text-red-300"><?php echo e(session('error')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- SMTP Accounts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 animate-fade-in" style="animation-delay: 0.4s;">
        <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="glass-effect rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-primary-100/50">
                <!-- Account Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg <?php echo e(!$account->is_active ? 'opacity-50' : ''); ?>">
                            <i class="fas fa-server text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-primary-900 flex items-center">
                                <?php echo e($account->name); ?>

                                <?php if($account->is_default): ?>
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-accent-100 text-accent-700 rounded-lg border border-accent-200">Default</span>
                                <?php endif; ?>
                            </h3>
                            <p class="text-primary-600 text-sm"><?php echo e($account->host); ?>:<?php echo e($account->port); ?></p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        <!-- Status Badge -->
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                            <?php switch($account->status):
                                case ('verified'): ?> bg-accent-100 text-accent-800 border border-accent-200
                                <?php case ('failed'): ?> bg-red-100 text-red-800 border border-red-200
                                <?php case ('pending'): ?> bg-yellow-100 text-yellow-800 border border-yellow-200
                                <?php case ('suspended'): ?> bg-gray-100 text-gray-800 border border-gray-200
                            <?php endswitch; ?>">
                            <i class="fas fa-circle text-xs mr-1 animate-pulse"></i>
                            <?php echo e(ucfirst($account->status)); ?>

                        </span>

                        <!-- Active/Inactive Indicator -->
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full <?php echo e($account->is_active ? 'bg-accent-500' : 'bg-red-500'); ?> mr-1"></div>
                            <span class="text-xs text-primary-600"><?php echo e($account->is_active ? 'Active' : 'Inactive'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Account Details -->
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-primary-600">From Address:</span>
                        <span class="text-sm font-medium text-primary-900"><?php echo e($account->from_address); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-primary-600">Encryption:</span>
                        <span class="text-sm font-medium text-primary-900 uppercase"><?php echo e($account->encryption ?: 'None'); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-primary-600">Today's Usage:</span>
                        <span class="text-sm font-medium text-primary-900"><?php echo e($account->emails_sent_today); ?>/<?php echo e($account->daily_limit); ?></span>
                    </div>
                </div>

                <!-- Usage Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-primary-600 mb-1">
                        <span>Daily Limit Usage</span>
                        <span><?php echo e(round(($account->emails_sent_today / max($account->daily_limit, 1)) * 100)); ?>%</span>
                    </div>
                    <div class="w-full bg-primary-100 rounded-full h-2">
                        <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-2 rounded-full transition-all duration-300"
                             style="width: <?php echo e(min(($account->emails_sent_today / max($account->daily_limit, 1)) * 100, 100)); ?>%"></div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4 border-t border-primary-100/50">
                    <div class="flex space-x-2">
                        <a href="<?php echo e(route('smtp.edit', $account->id)); ?>"
                           class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                           title="Edit Account">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('smtp.test', $account->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                    class="p-2 text-primary-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Test Connection">
                                <i class="fas fa-flask"></i>
                            </button>
                        </form>
                        <?php if(!$account->is_default): ?>
                            <form action="<?php echo e(route('smtp.set-default', $account->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                        class="p-2 text-primary-600 hover:text-accent-600 hover:bg-accent-50 rounded-lg transition-colors"
                                        title="Set as Default">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <form action="<?php echo e(route('smtp.toggle-active', $account->id)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                                class="p-2 rounded-lg transition-colors <?php echo e($account->is_active ? 'text-accent-600 hover:bg-accent-50' : 'text-red-600 hover:bg-red-50'); ?>"
                                title="<?php echo e($account->is_active ? 'Deactivate Account' : 'Activate Account'); ?>">
                            <?php if($account->is_active): ?>
                                <i class="fas fa-pause"></i>
                            <?php else: ?>
                                <i class="fas fa-play"></i>
                            <?php endif; ?>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="glass-effect rounded-2xl p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-100 to-accent-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-server text-5xl text-primary-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-primary-900 mb-2">No SMTP accounts yet</h3>
                    <p class="text-primary-600 mb-8 text-lg">Configure your first SMTP server to start sending emails at scale</p>
                    <a href="<?php echo e(route('smtp.create')); ?>"
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-accent-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Add Your First SMTP Account
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php /**PATH /Users/user/Desktop/Bulksend Email Only/resources/views/smtp/index.blade.php ENDPATH**/ ?>