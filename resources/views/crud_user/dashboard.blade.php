@extends('layout')
@section('title', 'Admin Dashboard - Siêu thị Mini')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Hệ Thống Quản Trị</h2>
            <p class="text-muted mb-0">Dưới đây là báo cáo tổng quát về hoạt động của siêu thị.</p>
        </div>
        <div class="badge bg-white shadow-sm text-dark p-2 px-3 border rounded-pill">
            <i class="fas fa-calendar-check me-2 text-primary"></i>{{ date('d/m/Y') }}
        </div>
    </div>

    <div class="row g-4 mb-5">
        @php
        $cards = [
        ['title' => 'Người dùng', 'val' => $totalUsers, 'icon' => 'fa-users', 'bg' => 'linear-gradient(135deg, #667eea
        0%, #764ba2 100%)'],
        ['title' => 'Sản phẩm', 'val' => $totalProducts, 'icon' => 'fa-box-open', 'bg' => 'linear-gradient(135deg,
        #2af598 0%, #009efd 100%)'],
        ['title' => 'Danh mục', 'val' => $totalCategories, 'icon' => 'fa-th-large', 'bg' => 'linear-gradient(135deg,
        #f093fb 0%, #f5576c 100%)'],
        ['title' => 'Bài viết', 'val' => $totalPosts, 'icon' => 'fa-newspaper', 'bg' => 'linear-gradient(135deg, #f6d365
        0%, #fda085 100%)'],
        ['title' => 'Coupons', 'val' => $totalCoupons, 'icon' => 'fa-ticket-alt', 'bg' => 'linear-gradient(135deg,
        #4facfe 0%, #00f2fe 100%)'],
        ];
        @endphp

        @foreach($cards as $card)
        <div class="col-md-4 col-lg">
            <div class="card border-0 shadow-sm transition-hover" style="border-radius: 1.5rem; overflow: hidden;">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto d-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px; background: {{ $card['bg'] }}; border-radius: 12px; color: white;">
                        <i class="fas {{ $card['icon'] }} fs-5"></i>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">{{ $card['title'] }}</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($card['val']) }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 1.5rem;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i>Phân tích bài viết</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="postsChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 1.5rem;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold"><i class="fas fa-percentage me-2 text-success"></i>Ưu đãi</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="couponsChart" height="200"></canvas>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Kích hoạt</span>
                            <span class="fw-bold text-success">{{ $couponsActiveCounts['active'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Tạm ngưng</span>
                            <span class="fw-bold text-secondary">{{ $couponsActiveCounts['inactive'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-lg text-white" style="border-radius: 1.5rem; background: #212529;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Lối tắt quản lý nhanh</h5>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('products.index') }}"
                            class="btn btn-outline-light rounded-pill px-4 transition-hover">
                            <i class="fas fa-plus-circle me-2"></i>Kho hàng
                        </a>
                        <a href="{{ route('posts.index') }}"
                            class="btn btn-outline-light rounded-pill px-4 transition-hover">
                            <i class="fas fa-edit me-2"></i>Viết bài
                        </a>
                        <a href="{{ route('coupons.index') }}"
                            class="btn btn-outline-light rounded-pill px-4 transition-hover">
                            <i class="fas fa-tags me-2"></i>Tạo mã giảm giá
                        </a>
                        <a href="{{ route('user.listUser') }}"
                            class="btn btn-outline-light rounded-pill px-4 transition-hover">
                            <i class="fas fa-user-shield me-2"></i>Phân quyền
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    background-color: #f4f7f6;
}

.transition-hover {
    transition: all 0.3s ease;
}

.transition-hover:hover {
    transform: translateY(-7px);
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.card {
    transition: transform 0.3s ease;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document::addEventListener("DOMContentLoaded", function() {
    // Bar Chart
    new Chart(document.getElementById('postsChart'), {
        type: 'bar',
        data: {
            labels: ['Bản nháp', 'Công khai', 'Đã ẩn'],
            datasets: [{
                label: 'Số lượng bài viết',
                data: [{
                    {
                        $postsStatusCounts['draft']
                    }
                }, {
                    {
                        $postsStatusCounts['published']
                    }
                }, {
                    {
                        $postsStatusCounts['hidden']
                    }
                }],
                backgroundColor: ['#ffc107', '#28a745', '#6c757d'],
                borderRadius: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Doughnut Chart
    new Chart(document.getElementById('couponsChart'), {
        type: 'doughnut',
        data: {
            labels: ['Hoạt động', 'Dừng'],
            datasets: [{
                data: [{
                    {
                        $couponsActiveCounts['active']
                    }
                }, {
                    {
                        $couponsActiveCounts['inactive']
                    }
                }],
                backgroundColor: ['#28a745', '#e9ecef'],
                borderWidth: 0,
            }]
        },
        options: {
            cutout: '80%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection