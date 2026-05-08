<?php $__env->startSection('title', 'Đăng nhập - Siêu thị Mini'); ?>

<?php $__env->startSection('content'); ?>
<main class="signup-form py-5" style="min-height: 80vh; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-4 text-center">
                        <h3 class="mb-0 fw-bold">Chào mừng quay lại!</h3>
                        <p class="mb-0 opacity-90 mt-1">Đăng nhập để tiếp tục mua sắm</p>
                    </div>
                    <div class="card-body p-5">
                        <form action="<?php echo e(route('user.authUser')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger rounded-3">
                                <?php echo e($errors->first()); ?>

                            </div>
                            <?php endif; ?>

                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-2">📧 Email đăng nhập</label>
                                <input type="email"
                                    class="form-control form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email"
                                    name="email" value="<?php echo e(old('email')); ?>" required autofocus
                                    placeholder="nhap@gmail.com">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-2">🔒 Mật khẩu</label>
                                <input type="password"
                                    class="form-control form-control-lg <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="password" required placeholder="Nhập mật khẩu">
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label small fw-normal" for="remember">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                                <a href="#" class="small text-decoration-none fw-semibold text-primary">Quên mật
                                    khẩu?</a>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit"
                                    class="btn btn-primary btn-lg fw-bold py-3 rounded-pill shadow-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập ngay
                                </button>
                            </div>

                            <div class="text-center border-top pt-3">
                                <small class="text-muted me-2">Chưa có tài khoản?</small>
                                <a href="<?php echo e(route('user.createUser')); ?>"
                                    class="fw-bold text-primary text-decoration-none">
                                    Đăng ký miễn phí!
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="bg-light py-4 text-center">
                        <small class="text-muted">Bảo mật thông tin người dùng • Hỗ trợ 24/7</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.signup-form .card {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 123, 255, 0.4);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PHONG\Documents\laravel-demo\laravel\resources\views/crud_user/login.blade.php ENDPATH**/ ?>