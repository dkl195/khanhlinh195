# Business Requirements Document (BRD)
**Project:** PLAYARENA – LEGO E-Commerce Management Information System  
**Version:** 1.0  
**Author:** [Your Name]  
**Date:** August 2026  
**Status:** Approved

---

## 1. Executive Summary

PLAYARENA là hệ thống thương mại điện tử quản lý bán lẻ đồ chơi LEGO trực tuyến. Hệ thống cho phép khách hàng duyệt sản phẩm, đặt hàng và thanh toán qua QR code; đồng thời cung cấp công cụ quản trị toàn diện cho admin để quản lý sản phẩm, đơn hàng, người dùng và theo dõi KPIs.

---

## 2. Business Objectives

| ID | Mục tiêu | Đo lường |
|----|----------|----------|
| BO-01 | Cung cấp kênh bán hàng trực tuyến 24/7 | Hệ thống hoạt động liên tục, uptime ≥ 99% |
| BO-02 | Tăng hiệu quả quản lý tồn kho | Admin nhận cảnh báo khi stock ≤ 10 |
| BO-03 | Đơn giản hóa quy trình thanh toán | Hỗ trợ thanh toán QR trong < 2 phút |
| BO-04 | Minh bạch hóa quy trình xử lý đơn hàng | Tracking đơn hàng theo 6 trạng thái |
| BO-05 | Hỗ trợ ra quyết định kinh doanh bằng dữ liệu | Dashboard KPI cho admin |

---

## 3. Stakeholders

| Stakeholder | Vai trò | Quyền lợi |
|-------------|---------|-----------|
| Customer (Khách hàng) | End User | Mua sắm dễ dàng, theo dõi đơn hàng |
| Administrator | System Admin | Quản lý toàn bộ hệ thống, xem báo cáo |
| Guest User | Anonymous Visitor | Duyệt sản phẩm, thêm vào giỏ hàng |
| Payment Gateway (VietQR) | External System | Xử lý giao dịch QR |

---

## 4. Scope

### 4.1 In Scope
- Quản lý tài khoản người dùng (đăng ký, đăng nhập, phân quyền)
- Catalogue sản phẩm (duyệt, tìm kiếm, lọc, sắp xếp)
- Giỏ hàng và wishlist (lưu trên trình duyệt)
- Quy trình đặt hàng và thanh toán QR (VietQR)
- Quản lý đơn hàng (Admin + Customer)
- Quản lý sản phẩm và tồn kho (Admin)
- Quản lý người dùng và phân quyền (Admin)
- Dashboard KPIs và báo cáo (Admin)

### 4.2 Out of Scope
- Thanh toán bằng thẻ tín dụng / ví điện tử
- Email notification tự động
- Đánh giá và nhận xét sản phẩm
- Chương trình khuyến mãi / mã giảm giá
- Quản lý địa chỉ giao hàng
- Ứng dụng mobile native

---

## 5. Functional Requirements

### 5.1 Authentication & Authorization

| ID | Yêu cầu | Ưu tiên |
|----|---------|---------|
| FR-AUTH-01 | Hệ thống cho phép người dùng đăng ký bằng email và mật khẩu | Must Have |
| FR-AUTH-02 | Hệ thống xác thực thông tin đăng nhập và tạo JWT token | Must Have |
| FR-AUTH-03 | Hệ thống phân quyền 2 role: `user` và `admin` | Must Have |
| FR-AUTH-04 | Token hết hạn sau 24 giờ, yêu cầu đăng nhập lại | Must Have |
| FR-AUTH-05 | Admin được redirect đến trang admin sau đăng nhập | Should Have |
| FR-AUTH-06 | Mật khẩu được mã hóa bằng bcrypt (10 rounds) | Must Have |

### 5.2 Product Catalog

| ID | Yêu cầu | Ưu tiên |
|----|---------|---------|
| FR-PROD-01 | Hệ thống hiển thị danh sách sản phẩm với hình ảnh, tên, giá, độ tuổi, số mảnh | Must Have |
| FR-PROD-02 | Người dùng có thể tìm kiếm sản phẩm theo tên | Must Have |
| FR-PROD-03 | Người dùng có thể lọc sản phẩm theo theme và trạng thái tồn kho | Should Have |
| FR-PROD-04 | Người dùng có thể sắp xếp theo giá (tăng/giảm) và mới nhất | Should Have |
| FR-PROD-05 | Sản phẩm hết hàng (stock = 0) hiển thị nhãn "Out of Stock" | Must Have |

### 5.3 Shopping Cart

| ID | Yêu cầu | Ưu tiên |
|----|---------|---------|
| FR-CART-01 | Người dùng có thể thêm sản phẩm vào giỏ hàng | Must Have |
| FR-CART-02 | Giỏ hàng được lưu tại localStorage trên trình duyệt | Must Have |
| FR-CART-03 | Hệ thống tính subtotal, thuế 8%, phí ship $10 (miễn nếu subtotal > $100) | Must Have |
| FR-CART-04 | Người dùng có thể thay đổi số lượng hoặc xóa sản phẩm khỏi giỏ | Must Have |
| FR-CART-05 | Badge trên icon giỏ hàng cập nhật real-time | Should Have |

### 5.4 Order & Payment

| ID | Yêu cầu | Ưu tiên |
|----|---------|---------|
| FR-ORD-01 | Chỉ người dùng đã đăng nhập mới có thể tạo đơn hàng | Must Have |
| FR-ORD-02 | Hệ thống kiểm tra tồn kho trước khi tạo đơn | Must Have |
| FR-ORD-03 | Đơn hàng được tạo trong 1 transaction (atomic) gồm: order, order_items, payment_transaction | Must Have |
| FR-ORD-04 | Hệ thống tạo mã QR thanh toán với transaction reference duy nhất | Must Have |
| FR-ORD-05 | QR code hết hạn sau 30 phút | Must Have |
| FR-ORD-06 | Khi xác nhận thanh toán: payment → paid, order → confirmed, stock giảm tương ứng | Must Have |
| FR-ORD-07 | Người dùng có thể xem lịch sử đơn hàng | Must Have |
| FR-ORD-08 | Order items lưu snapshot sản phẩm (tên, giá, hình) tại thời điểm đặt hàng | Must Have |

### 5.5 Admin – Product Management

| ID | Yêu cầu | Ưu tiên |
|----|---------|---------|
| FR-ADM-01 | Admin có thể tạo sản phẩm mới với upload hình ảnh | Must Have |
| FR-ADM-02 | Admin có thể chỉnh sửa thông tin sản phẩm | Must Have |
| FR-ADM-03 | Admin có thể xóa sản phẩm | Must Have |
| FR-ADM-04 | Hệ thống cảnh báo sản phẩm có stock ≤ 10 | Should Have |

### 5.6 Admin – Order & User Management

| ID | Yêu cầu | Ưu tiên |
|----|---------|---------|
| FR-ADM-05 | Admin có thể xem tất cả đơn hàng của mọi khách hàng | Must Have |
| FR-ADM-06 | Admin có thể cập nhật trạng thái đơn hàng | Must Have |
| FR-ADM-07 | Admin có thể xem danh sách người dùng | Must Have |
| FR-ADM-08 | Admin có thể thay đổi role người dùng (user ↔ admin) | Should Have |

### 5.7 Admin – Dashboard & KPIs

| ID | Yêu cầu | Ưu tiên |
|----|---------|---------|
| FR-ADM-09 | Dashboard hiển thị: tổng sản phẩm, số categories, low stock count, pending orders | Must Have |
| FR-ADM-10 | Hệ thống hiển thị báo cáo doanh thu và tồn kho | Should Have |

---

## 6. Non-Functional Requirements

| ID | Yêu cầu | Tiêu chí |
|----|---------|---------|
| NFR-01 | **Performance** – API response time | ≤ 500ms cho 95% requests |
| NFR-02 | **Security** – Password storage | Bcrypt hash, không lưu plain text |
| NFR-03 | **Security** – API authorization | JWT token required cho protected routes |
| NFR-04 | **Security** – SQL Injection | Parameterized queries cho mọi DB query |
| NFR-05 | **Reliability** – Transaction integrity | ACID compliance cho order creation & payment |
| NFR-06 | **Usability** – Responsive design | Hoạt động trên mobile và desktop |
| NFR-07 | **Scalability** – Database design | Schema sẵn sàng migrate lên PostgreSQL |
| NFR-08 | **Maintainability** – Code structure | Repository pattern, separation of concerns |

---

## 7. Business Rules

| ID | Rule |
|----|------|
| BR-01 | Thuế = 8% × subtotal |
| BR-02 | Phí ship = $10, miễn phí nếu subtotal > $100 |
| BR-03 | QR payment hết hạn sau 30 phút kể từ khi tạo |
| BR-04 | Tồn kho giảm chỉ khi payment được xác nhận (status = paid) |
| BR-05 | Không thể xác nhận đơn hàng nếu tồn kho không đủ |
| BR-06 | Một đơn hàng chỉ có một giao dịch thanh toán |
| BR-07 | Order items lưu snapshot: giá tại thời điểm đặt hàng, không thay đổi theo giá hiện tại |
| BR-08 | Sản phẩm low stock khi stock ≤ 10 |
| BR-09 | Trạng thái đơn hàng chỉ chuyển theo chiều: pending → processing → confirmed → shipped → completed |
| BR-10 | Chỉ giao dịch có status = pending mới có thể xác nhận thanh toán |

---

## 8. Assumptions & Constraints

### Assumptions
- Người dùng có trình duyệt hiện đại hỗ trợ localStorage
- Thanh toán được xác nhận thủ công bởi người dùng (chưa có webhook tự động từ ngân hàng)
- Tỷ giá USD/VND cố định = 25,000 VND/USD

### Constraints
- Database: SQLite (phù hợp cho môi trường development/demo)
- Không có email verification khi đăng ký
- Không hỗ trợ multiple payment methods (chỉ QR)

---

## 9. Dependencies

| Dependency | Loại | Mô tả |
|-----------|------|-------|
| Node.js ≥ 14 | Technical | Runtime environment |
| Express.js 5 | Technical | Web framework |
| SQLite3 | Technical | Database |
| bcrypt | Technical | Password hashing |
| JWT (jsonwebtoken) | Technical | Authentication tokens |
| Multer | Technical | File upload handling |
| VietQR API | External | QR code generation |

---

## 10. Acceptance Criteria (tổng quan)

- ✅ Guest user có thể duyệt và tìm kiếm sản phẩm mà không cần đăng nhập
- ✅ User đăng ký, đăng nhập và đặt hàng thành công
- ✅ QR payment tạo ra mã giao dịch duy nhất và hết hạn đúng thời gian
- ✅ Xác nhận thanh toán cập nhật đúng trạng thái order và trừ tồn kho
- ✅ Admin quản lý CRUD sản phẩm, đơn hàng, người dùng
- ✅ Dashboard hiển thị đầy đủ 4 KPIs
- ✅ Unauthorized user không thể truy cập admin routes

---

*Document Version: 1.0 | Last Updated: August 2026*
