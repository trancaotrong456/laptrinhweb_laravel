<?php $__env->startSection('title', 'Trang chủ - Siêu thị Mini'); ?>
<!-- <?php $__env->startSection('content'); ?>-->
<?php $__env->startSection('content'); ?>
<div class="hero-section text-center py-5 bg-gradient-primary">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4 text-white animate-fade-in">Chào mừng đến Siêu thị Mini</h1>
        <p class="lead text-white-50 mb-5">Mua sắm thông minh - Giao hàng siêu tốc</p>
        <?php if(auth()->guard()->guest()): ?>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?php echo e(route('login')); ?>" class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow-lg">
                <i class="fas fa-lock me-2"></i>Đăng nhập
            </a>
            <a href="<?php echo e(route('user.createUser')); ?>" class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow-lg">
                <i class="fas fa-user-plus me-2"></i>Đăng ký
            </a>
        </div>
        <?php else: ?>
        <?php if(Auth::user()->role == 1): ?>
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-info btn-lg px-5 py-3 rounded-pill shadow-lg text-white">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
        </a>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php if(isset($banners) && $banners->count() > 0): ?>
<section class="banner-section">
    <div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner rounded-3 shadow-lg">
            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="carousel-item <?php echo e($index == 0 ? 'active' : ''); ?>">
                <img src="<?php echo e($banner->image ? asset('images/' . $banner->image) : asset('images/banner-placeholder.jpg')); ?>"
                    class="d-block w-100" style="height: 500px; object-fit: cover;" alt="<?php echo e($banner->title); ?>">
                <div class="carousel-caption d-none d-md-block">
                    <?php if(Auth::check() && Auth::user()->role == 1): ?>
                    <a href="<?php echo e(route('posts.edit', $banner->id)); ?>" class="btn btn-sm btn-warning mb-2">⚙️ Edit
                        Banner</a>
                    <?php endif; ?>
                    <h2 class="display-5 fw-bold text-shadow"><?php echo e($banner->title); ?></h2>
                    <p class="lead text-shadow"><?php echo e(Str::limit($banner->content, 120)); ?></p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="#" class="btn btn-primary btn-lg rounded-pill px-4">Xem ngay</a>
                        <a href="<?php echo e(route('posts.index')); ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">Tất
                            cả khuyến mãi</a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>
<?php endif; ?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3 gradient-text">🛍️ Sản phẩm HOT</h2>
            <p class="lead text-muted">Chọn lựa những sản phẩm chất lượng cao với giá tốt nhất</p>
        </div>

        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $products->take(12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card border-0 h-100 shadow-lg overflow-hidden rounded-4" style="transition: all 0.4s ease;">
                    <div class="position-relative overflow-hidden" style="height: 220px;">
                        <?php if($product->image): ?>
                        <img src="<?php echo e(asset('images/' . $product->image)); ?>"
                            class="card-img-top w-100 h-100 object-fit-cover" alt="<?php echo e($product->name); ?>">
                        <?php else: ?>
                        <div
                            class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white">
                            <i class="fas fa-gift fa-3x"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="fw-bold mb-2"
                            style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            <?php echo e($product->name); ?>

                        </h6>
                        <div class="mb-2">
                            <span class="h5 fw-bolder text-danger"><?php echo e(number_format($product->price)); ?>đ</span>
                        </div>
                        <div class="mt-auto">
                            <form action="<?php echo e(route('cart.add')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                <button type="submit" class="btn btn-danger w-100 py-2 fw-bold rounded-3"
                                    style="font-size: 0.9rem;"
                                    <?php echo e($product->quantity <= 0 ? 'disabled title="Hết hàng"' : ''); ?>>
                                    <i class="fas fa-cart-shopping me-1"></i>Mua ngay
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-fire fa-5x text-muted mb-4 opacity-50"></i>
                <h3 class="text-muted mb-4">Chưa có sản phẩm nào</h3>
                <?php if(Auth::check() && Auth::user()->role == 1): ?>
                <a href="<?php echo e(route('products.create')); ?>"
                    class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg">
                    <i class="fas fa-plus-circle me-2"></i>Thêm sản phẩm
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.animate-fade-in {
    animation: fadeIn 1s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.gradient-text {
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
 <?php $__env->startSection('title', 'Chào mừng đến với Siêu thị Mini'); ?>

<?php $__env->startSection('content'); ?>
<div style="text-align: center; padding: 50px 20px; background: #f8f9fa;">
    <h1 style="font-size: 3rem; color: #007bff;">Chào mừng đến với Siêu Thị </h1>
    <p style="font-size: 1.2rem; color: #6c757d;">Hệ thống quản lý siêu thị hiện đại, nhanh chóng và chính xác.</p>

    <?php if(auth()->guard()->guest()): ?>
    <div style="margin-top: 30px;">
        <a href="<?php echo e(route('login')); ?>"
            style="padding: 15px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">Đăng
            nhập quản trị</a>
        <a href="<?php echo e(route('user.createUser')); ?>"
            style="padding: 15px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;">Tham
            gia ngay</a>
    </div>
    <?php else: ?>
    <div style="margin-top: 30px;">
        <a href="<?php echo e(route('dashboard')); ?>"
            style="padding: 15px 30px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px;">Vào
            trang quản lý (Dashboard)</a>
    </div>
    <?php endif; ?>
</div>

<div style="display: flex; justify-content: space-around; padding: 50px; text-align: center;">
    <div>
        <h3>🛍️ Sản phẩm</h3>
        <p>Hàng ngàn mặt hàng đa dạng.</p>
    </div>
    <div>
        <h3>🚚 Giao hàng</h3>
        <p>Nhanh chóng trong nội thành.</p>
    </div>
    <div>
        <h3>💎 Uy tín</h3>
        <p>Chất lượng hàng đầu Việt Nam.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PHONG\Documents\laravel-demo\laravel\resources\views/index.blade.php ENDPATH**/ ?>