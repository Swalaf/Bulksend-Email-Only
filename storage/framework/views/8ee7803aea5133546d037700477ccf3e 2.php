<?php $__env->startSection('title', 'Campaigns'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Campaigns</h1>
        <a href="<?php echo e(route('campaigns.create')); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Campaign
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form method="GET" class="flex gap-4 flex-wrap">
            <select name="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="scheduled">Scheduled</option>
                <option value="sending">Sending</option>
                <option value="sent">Sent</option>
                <option value="paused">Paused</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Filter</button>
        </form>
    </div>

    <!-- Campaigns Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Open Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Click Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="<?php echo e(route('campaigns.show', $campaign->id)); ?>" class="text-indigo-600 hover:text-indigo-900 font-medium">
                            <?php echo e($campaign->name); ?>

                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        <?php echo e($campaign->subject); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php switch($campaign->status):
                                case ('draft'): ?> bg-gray-100 text-gray-800 <?php break; ?>
                                <?php case ('scheduled'): ?> bg-blue-100 text-blue-800 <?php break; ?>
                                <?php case ('sending'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
                                <?php case ('sent'): ?> bg-green-100 text-green-800 <?php break; ?>
                                <?php case ('paused'): ?> bg-orange-100 text-orange-800 <?php break; ?>
                                <?php case ('cancelled'): ?> bg-red-100 text-red-800 <?php break; ?>
                            <?php endswitch; ?>">
                            <?php echo e(ucfirst($campaign->status)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        <?php echo e(number_format($campaign->sent_count)); ?> / <?php echo e(number_format($campaign->total_recipients)); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        <?php echo e($campaign->getOpenRate()); ?>%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        <?php echo e($campaign->getClickRate()); ?>%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        <?php if($campaign->scheduled_at): ?>
                            <?php echo e($campaign->scheduled_at->format('M d, Y H:i')); ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="<?php echo e(route('campaigns.show', $campaign->id)); ?>" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <?php if($campaign->canEdit()): ?>
                            <a href="<?php echo e(route('campaigns.edit', $campaign->id)); ?>" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">No campaigns yet</p>
                            <p class="text-sm">Create your first campaign to get started</p>
                            <a href="<?php echo e(route('campaigns.create')); ?>" class="mt-4 text-indigo-600 hover:text-indigo-500">Create Campaign</a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($campaigns->hasPages()): ?>
    <div class="mt-4">
        <?php echo e($campaigns->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/user/Desktop/Bulksend Email Only/resources/views/campaigns/index.blade.php ENDPATH**/ ?>