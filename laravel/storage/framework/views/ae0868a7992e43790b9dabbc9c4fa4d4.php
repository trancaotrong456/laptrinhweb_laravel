<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Management - <?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <style>
    nav ul {
        display: flex;
        align-items: center;
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    nav ul li { margin-right: 15px; }
    .cart-item {
        margin-left: auto;
        margin-right: 20px;
    }
    .cart-item a {
        background-color: #ffc107;
        color: #000;
        font-weight: bold;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }
    .cart-item a:hover { background-color: #e0a800; }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="<?php echo e(route('home')); ?>">Trang chủ</a></li>
            <?php if(auth()->guard()->guest()): ?>
                <li><a href="<?php echo e(route('login')); ?>">Đăng nhập</a></li>
                <li><a href="<?php echo e(route('user.createUser')); ?>">Đăng kí</a></li>
            <?php else: ?>
                <li><a href="<?php echo e(route('products.index')); ?>">Sản phẩm</a></li>
                <li><a href="<?php echo e(route('categories.index')); ?>">Danh mục</a></li>
                <li><a href="<?php echo e(route('posts.index')); ?>">Tin tức</a></li>
                <?php if(Auth::user()->role == 1): ?>
                <li><a href="<?php echo e(route('user.listUser')); ?>">Quản lý User</a></li>
                <?php endif; ?>
                <li><a href="<?php echo e(route('signout')); ?>">Đăng xuất</a></li>
            <?php endif; ?>
            <li class="cart-item">
                <a href="<?php echo e(route('cart.index')); ?>">
                    🛒 Giỏ hàng (<?php echo e(session()->has('cart') ? count(session('cart')) : 0); ?>)
                </a>
            </li>
        </ul>
    </nav>

    <div class="container" style="padding-bottom: 60px;">
        <?php if(session('success')): ?>
        <div style="background: #d4edda; color: #155724; padding: 10px; margin: 20px auto; max-width: 400px; border-radius: 5px;">
            <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <footer style="background-color: #0000FF; color: white; padding: 10px; position: fixed; bottom: 0; width: 100%; z-index: 1000;">
        <p style="text-align: center; margin: 0;">&copy; Trần Cao Trọng - 24211TT1101</p>
    </footer>
</body>
</html><?php /**PATH C:\Users\PHONG\Documents\laravel-demo\laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>