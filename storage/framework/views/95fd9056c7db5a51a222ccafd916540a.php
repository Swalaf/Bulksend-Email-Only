<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => ['title' => 'Subscribers']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Subscribers']); ?>
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8 animate-slide-up">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 mb-2">Email Subscribers</h1>
            <p class="text-primary-600">Manage your subscriber lists and contacts</p>
        </div>
        <a href="<?php echo e(route('subscribers.create')); ?>"
           class="bg-gradient-to-r from-accent-600 to-accent-700 hover:from-accent-700 hover:to-accent-800 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-user-plus"></i>
            Add Subscriber
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 animate-scale-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Total Subscribers</p>
                    <p class="text-3xl font-bold text-primary-900"><?php echo e($subscribers->total() ?? 0); ?></p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Active Subscribers</p>
                    <p class="text-3xl font-bold text-accent-600"><?php echo e($subscribers->where('status', 'active')->count() ?? 0); ?></p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Subscriber Lists</p>
                    <p class="text-3xl font-bold text-primary-900"><?php echo e($subscriberLists->count() ?? 0); ?></p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-primary-600 to-accent-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-list text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="glass-effect rounded-2xl p-6 mb-8 animate-slide-up" style="animation-delay: 0.3s;">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-primary-700 mb-2">Search Subscribers</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name or email..."
                       class="w-full px-4 py-3 bg-white/50 border border-primary-200 rounded-xl text-primary-900 placeholder-primary-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-primary-700 mb-2">Status Filter</label>
                <select name="status" class="px-4 py-3 bg-white/50 border border-primary-200 rounded-xl text-primary-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="unsubscribed" <?php echo e(request('status') == 'unsubscribed' ? 'selected' : ''); ?>>Unsubscribed</option>
                    <option value="bounced" <?php echo e(request('status') == 'bounced' ? 'selected' : ''); ?>>Bounced</option>
                </select>
            </div>
            <div>
                <label for="list_id" class="block text-sm font-medium text-primary-700 mb-2">Subscriber List</label>
                <select name="list_id" class="px-4 py-3 bg-white/50 border border-primary-200 rounded-xl text-primary-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                    <option value="">All Lists</option>
                    <?php $__currentLoopData = $subscriberLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($list->id); ?>" <?php echo e(request('list_id') == $list->id ? 'selected' : ''); ?>><?php echo e($list->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <?php if(request()->hasAny(['search', 'status', 'list_id'])): ?>
                    <a href="<?php echo e(route('subscribers.index')); ?>" class="bg-primary-100 hover:bg-primary-200 text-primary-700 px-4 py-3 rounded-xl font-medium transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Subscribers Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 animate-fade-in" style="animation-delay: 0.4s;">
        <?php $__empty_1 = true; $__currentLoopData = $subscribers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscriber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="glass-effect rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-primary-100/50">
                <!-- Subscriber Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg"><?php echo e(strtoupper(substr($subscriber->first_name ?: $subscriber->email, 0, 1))); ?></span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-primary-900">
                                <?php echo e($subscriber->first_name); ?> <?php echo e($subscriber->last_name); ?>

                            </h3>
                            <p class="text-primary-600 text-sm"><?php echo e($subscriber->email); ?></p>
                        </div>
                    </div>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                        <?php if($subscriber->status === 'active'): ?> bg-accent-100 text-accent-800 border border-accent-200
                        <?php elseif($subscriber->status === 'unsubscribed'): ?> bg-red-100 text-red-800 border border-red-200
                        <?php else: ?> bg-yellow-100 text-yellow-800 border border-yellow-200 <?php endif; ?>">
                        <?php echo e(ucfirst($subscriber->status)); ?>

                    </span>
                </div>

                <!-- Subscriber Lists -->
                <?php if($subscriber->lists->count() > 0): ?>
                    <div class="mb-4">
                        <p class="text-xs font-medium text-primary-600 mb-2">Lists:</p>
                        <div class="flex flex-wrap gap-1">
                            <?php $__currentLoopData = $subscriber->lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex px-2 py-1 text-xs bg-primary-100 text-primary-700 rounded-lg">
                                    <?php echo e($list->name); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4 border-t border-primary-100/50">
                    <div class="flex space-x-2">
                        <a href="<?php echo e(route('subscribers.show', $subscriber->id)); ?>"
                           class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                           title="View Subscriber">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?php echo e(route('subscribers.edit', $subscriber->id)); ?>"
                           class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                           title="Edit Subscriber">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <div class="text-xs text-primary-500">
                        <?php echo e($subscriber->created_at->diffForHumans()); ?>

                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="glass-effect rounded-2xl p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-accent-100 to-accent-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-5xl text-accent-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-primary-900 mb-2">No subscribers yet</h3>
                    <p class="text-primary-600 mb-8 text-lg">Start building your email list by adding your first subscriber</p>
                    <a href="<?php echo e(route('subscribers.create')); ?>"
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-accent-600 to-accent-700 text-white font-semibold rounded-xl hover:from-accent-700 hover:to-accent-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        Add Your First Subscriber
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($subscribers->hasPages()): ?>
        <div class="mt-8 flex justify-center animate-fade-in" style="animation-delay: 0.6s;">
            <div class="glass-effect rounded-xl p-4">
                <?php echo e($subscribers->appends(request()->query())->links()); ?>

            </div>
        </div>
    <?php endif; ?>>
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
<?php /**PATH /Users/user/Desktop/Bulksend Email Only/resources/views/subscribers/index.blade.php ENDPATH**/ ?>