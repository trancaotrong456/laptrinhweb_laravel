@extends('layout')

@section('title', 'Tất cả danh mục - Siêu thị trực tuyến')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #2e7d32;">Khám Phá Danh Mục Sản Phẩm</h2>
        <p class="text-muted">Lựa chọn các sản phẩm tươi sạch và chất lượng nhất</p>
    </div>
    
    <div class="row g-4">
        @forelse($categories as $category)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('categories.show', $category) }}" class="card text-decoration-none shadow-sm h-100 border-0" style="transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div style="width: 60px; height: 60px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #2e7d32; font-size: 24px;">
                            @if($category->type == 'do_uong')
                                <i class="fas fa-coffee"></i>
                            @elseif($category->type == 'thuc_pham')
                                <i class="fas fa-hamburger"></i>
                            @elseif($category->type == 'gia_dung')
                                <i class="fas fa-blender"></i>
                            @else
                                <i class="fas fa-box-open"></i>
                            @endif
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">{{ $category->name }}</h5>
                    <p class="text-muted small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $category->description ?? 'Tuyển chọn các mặt hàng chất lượng cao' }}
                    </p>
                    <div class="mt-3 text-success fw-bold small">
                        {{ $category->products_count }} sản phẩm
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-50"></i>
            <h5 class="text-muted">Chưa có danh mục nào</h5>
        </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-5">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
