<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Thêm bài viết / Khuyến mãi mới</h2>

    
    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('posts.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        
        <div class="mb-3">
            <label class="form-label">Tiêu đề bài viết</label>
            <input type="text" name="title" class="form-control" value="<?php echo e(old('title')); ?>" required>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Ảnh minh họa</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        
        <div class="mb-3">
            <label class="form-label">Nội dung chi tiết</label>
            <textarea name="content" class="form-control" rows="6" required><?php echo e(old('content')); ?></textarea>
        </div>

<<<<<<< Updated upstream
        
        <div class="mb-3">
            <label class="form-label">Vị trí hiển thị</label>
            <select name="type" class="form-select">
                <option value="0" <?php echo e(old('type') == 0 ? 'selected' : ''); ?>>Ảnh nhỏ</option>
                <option value="1" <?php echo e(old('type') == 1 ? 'selected' : ''); ?>>Banner lớn</option>
            </select>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Độ ưu tiên</label>
            <input type="number" name="priority" class="form-control" value="<?php echo e(old('priority', 0)); ?>">
        </div>

        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Lưu bài viết
        </button>
=======
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Vị trí hiển thị</label>
                <select name="type" class="form-select">
                    <option value="0">Ảnh nhỏ (Ưu đãi khác)</option>
                    <option value="1">Banner to (Trượt ở trên)</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Thứ tự ưu tiên</label>
                <input type="number" name="priority" class="form-control" placeholder="Số to hiện trước" value="0">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Lưu Bài Viết</button>
        <a href="<?php echo e(route('posts.index')); ?>" class="btn btn-secondary">Quay lại</a>
>>>>>>> Stashed changes
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PHONG\Documents\laravel-demo\laravel\resources\views/posts/create_post.blade.php ENDPATH**/ ?>