# TODO

## Authentication fixes (login)
- [x] Tìm lỗi: controller `authUser` đang so sánh mật khẩu plain text.
- [x] Sửa `CrudUserController@authUser` để dùng `Hash::check`, đồng thời fallback plain text và hash lại nếu cần.
- [ ] (Khuyến nghị) Sửa `resources/views/crud_user/login.blade.php` để loại bỏ double `@extends` / form lặp.

