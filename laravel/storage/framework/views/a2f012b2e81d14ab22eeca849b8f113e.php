<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2 class="mb-4">Chỉnh sửa bài viết: <span class="text-primary"><?php echo e($post->title); ?></span></h2>

    <form action="<?php echo e(route('posts.update', $post->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="mb-3">
            <label class="form-label font-weight-bold">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="<?php echo e($post->title); ?>" required>
        </div>

        <div class="row">
            
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Vị trí hiển thị</label>
                <select name="type" class="form-select">
                    <option value="0" <?php echo e($post->type == 0 ? 'selected' : ''); ?>>Ảnh nhỏ (Ưu đãi khác)</option>
                    <option value="1" <?php echo e($post->type == 1 ? 'selected' : ''); ?>>Banner to (Lướt ở trên)</option>
                </select>
            </div>

            
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Thứ tự ưu tiên</label>
                <input type="number" name="priority" class="form-control" value="<?php echo e($post->priority); ?>"
                    placeholder="Số to hiện trước">
            </div>
        </div>

        
        <div class="mb-3 p-3 border rounded bg-light">
            <label class="form-label font-weight-bold d-block">Ảnh quảng cáo</label>
            <div class="mb-2">
                <small class="text-muted d-block mb-1">Ảnh hiện tại:</small>
                <img src="<?php echo e(asset('images/' . $post->image)); ?>" width="150" class="rounded shadow-sm border">
            </div>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-info">Để trống nếu sếp không muốn đổi ảnh.</small>
        </div>

        
        <div class="mb-3">
            <label class="form-label font-weight-bold">Nội dung</label>
            <textarea name="content" class="form-control" rows="5" required><?php echo e($post->content); ?></textarea>
        </div>

        <hr>
        <div class="mb-5">
            <button type="submit" class="btn btn-warning px-4">Lưu thay đổi</button>
            <a href="<?php echo e(route('posts.index')); ?>" class="btn btn-secondary px-4">Quay lại</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PHONG\Documents\laravel-demo\laravel\resources\views/posts/edit_post.blade.php ENDPATH**/ ?>