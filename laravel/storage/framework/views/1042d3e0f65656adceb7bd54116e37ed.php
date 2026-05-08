<?php $__env->startSection('content'); ?>
<div class="card table-card">
    <h1>Danh sách user</h1>

    <form action="<?php echo e(route('user.listUser')); ?>" method="GET" style="box-shadow: none; margin: 0; padding: 10px;">
        <input type="text" name="search" placeholder="Nhập tên cần tìm..." value="<?php echo e(request('search')); ?>" style="width: 70%; display: inline-block;">
        <input type="submit" value="Tìm" style="width: 25%; display: inline-block; padding: 8px;">
    </form>

    <table>
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Sở thích</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($user->name); ?></td>
                <td><?php echo e($user->email); ?></td>
                <td><?php echo e($user->like); ?></td>
                <td>
                    <a href="<?php echo e(route('user.readUser', $user->id)); ?>">View</a> |
                    <a href="<?php echo e(route('user.updateUser', $user->id)); ?>">Edit</a> |
                    <a href="<?php echo e(route('user.deleteUser', $user->id)); ?>" onclick="return confirm('Xóa hả?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
    <?php echo e($users->links('pagination::bootstrap-4')); ?>

</div>
    
    <div class="pagination">
        
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PHONG\Documents\laravel-demo\laravel\resources\views/crud_user/list.blade.php ENDPATH**/ ?>