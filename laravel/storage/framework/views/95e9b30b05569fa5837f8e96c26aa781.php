<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/products.css')); ?>">
</head>

<body>

<div class="container">

    <div class="top-bar">
        <form method="GET" action="<?php echo e(route('products.index')); ?>" class="search">
            <input type="text" name="keyword" placeholder="Nhập tên sản phẩm..." value="<?php echo e($keyword ?? ''); ?>">
            <button type="submit">Tìm kiếm</button>
        </form>

        <a class="btn-add" href="<?php echo e(route('products.create')); ?>">+ Thêm sản phẩm</a>
    </div>

    <h2>Danh sách sản phẩm</h2>

    <table>
        <tr>
            <th>Tên</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Ảnh</th>
            <th>Action</th>
        </tr>

        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($p->name); ?></td>
            <td><?php echo e(number_format($p->price)); ?> đ</td>
            <td><?php echo e($p->quantity); ?></td>
            <td>
                <?php if($p->image): ?>
                    <img src="<?php echo e(asset('images/'.$p->image)); ?>" width="70">
                <?php endif; ?>
            </td>
            <td class="actions">
                <a class="btn-edit" href="<?php echo e(route('products.edit', $p->id)); ?>">Sửa</a>

                <form action="<?php echo e(route('products.destroy', $p->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button class="btn-delete" onclick="return confirm('Xóa sản phẩm này?')">
                        Xóa
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>

</div>

</body>
</html><?php /**PATH C:\Users\PHONG\Documents\laravel-demo\laravel\resources\views/products/index.blade.php ENDPATH**/ ?>