<?php $__env->startSection('title', '🔥 Khuyến mãi Hot - Siêu thị Mini'); ?>

<?php $__env->startSection('content'); ?>

<?php if(Auth::check() && Auth::user()->role == 1): ?>
<section class="py-3 bg-light border-bottom">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="text-muted"><i class="fas fa-shield-alt me-2 text-primary"></i>Khu vực quản trị</span>
        <a href="<?php echo e(route('posts.create')); ?>" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Thêm khuyến mãi mới
        </a>
    </div>
</section>
<?php endif; ?>

<section class="promo-hero position-relative overflow-hidden mb-5"
    style="min-height: 60vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-8 text-white text-center text-lg-start">
                <h1 class="display-4 fw-bold mb-4">🔥<br>SIÊU KHUYẾN MÃI</h1>
                <p class="lead mb-4 opacity-90">Tiết kiệm đến 70% - Săn sale cực đã cùng Siêu thị Mini!</p>
                <a href="#deals" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-semibold shadow-lg">
                    <i class="fas fa-fire me-2"></i>Xem ngay ưu đãi
                </a>
            </div>
        </div>
    </div>
</section>

<div class="container">

    <div id="promoCarousel" class="carousel slide mb-5 shadow-sm rounded" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner rounded">
            <?php $__empty_1 = true; $__currentLoopData = $sliderPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="carousel-item <?php echo e($index == 0 ? 'active' : ''); ?>">
                <img src="<?php echo e($post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/1200x450?text=Banner'); ?>"
                    class="d-block w-100" alt="<?php echo e($post->title); ?>" style="height: 450px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    <?php if(Auth::check() && Auth::user()->role == 1): ?>
                    <div class="mb-2 d-flex justify-content-center gap-2">
                        <a href="<?php echo e(route('posts.edit', $post->id)); ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                        <form action="<?php echo e(route('posts.destroy', $post->id)); ?>" method="POST" class="d-inline"
                            onsubmit="return confirm('Xóa thật không?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger btn-sm">🗑️ Xóa</button>
                        </form>
                    </div>
                    <?php endif; ?>
                    <h4><?php echo e($post->title); ?></h4>
                    <p><?php echo e(Str::limit($post->content, 60)); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="carousel-item active">
                <div class="d-flex justify-content-center align-items-center bg-light" style="height:450px;">
                    <p class="text-muted">Chưa có banner nào</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <section id="deals" class="py-4">
        <h3 class="mb-4 border-bottom pb-2">🎁 Ưu Đãi Đặc Biệt</h3>
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $smallPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-lg rounded-4 overflow-hidden hover-lift">
                    <?php if($post->priority > 5): ?>
                    <div class="position-absolute top-0 start-0 m-3 z-3">
                        <span class="badge bg-warning text-dark fw-bold px-3 py-2">
                            <i class="fas fa-crown me-1"></i>TOP <?php echo e($post->priority); ?>

                        </span>
                    </div>
                    <?php endif; ?>
                    <img src="<?php echo e($post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/400x300'); ?>"
                        class="card-img-top" alt="<?php echo e($post->title); ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo e($post->title); ?></h5>
                        <p class="card-text text-muted"><?php echo e(Str::limit($post->content, 80)); ?></p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-outline-danger btn-sm w-100 mb-2">Xem ngay</a>
                            <?php if(Auth::check() && Auth::user()->role == 1): ?>
                            <a href="<?php echo e(route('posts.edit', $post->id)); ?>" class="btn btn-warning btn-sm w-100 mb-2">✏️ Sửa</a>
                            <form action="<?php echo e(route('posts.destroy', $post->id)); ?>" method="POST"
                                onsubmit="return confirm('Xóa thật không?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-sm w-100">🗑️ Xóa</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-gift fa-5x text-muted mb-4"></i>
                <h3 class="text-muted">Chưa có deal nào</h3>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="py-5">
        <div class="row text-center g-4">
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold text-primary">10K+</h3>
                <p class="text-muted">Khách hàng</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-fire fa-3x text-danger mb-3"></i>
                <h3 class="fw-bold text-danger"><?php echo e($sliderPosts->count() + $smallPosts->count()); ?></h3>
                <p class="text-muted">Ưu đãi hiện có</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-shipping-fast fa-3x text-success mb-3"></i>
                <h3 class="fw-bold text-success">2h</h3>
                <p class="text-muted">Giao hàng</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-award fa-3x text-warning mb-3"></i>
                <h3 class="fw-bold text-warning">5⭐</h3>
                <p class="text-muted">Đánh giá</p>
            </div>
        </div>
    </section>

</div>

<style>
.hover-lift { transition: transform .3s ease, box-shadow .3s ease; }
.hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,.15) !important; }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PHONG\Documents\laravel-demo\laravel\resources\views/posts/index_post.blade.php ENDPATH**/ ?>