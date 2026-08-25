# User Stories & Acceptance Criteria
**Project:** PLAYARENA – LEGO E-Commerce MIS  
**Version:** 1.0  
**Author:** [Your Name]  
**Date:** August 2026

---

## Hướng dẫn đọc

- **Format:** As a [actor], I want to [action], so that [benefit]
- **Priority:** 🔴 Must Have | 🟡 Should Have | 🟢 Nice to Have
- **Status:** ✅ Done | 🔄 In Progress | ⏳ Backlog

---

## EPIC 1: Authentication & Account Management

---

### US-001 · Đăng ký tài khoản 🔴 ✅

**User Story:**  
As a **guest user**, I want to **register an account with email and password**, so that **I can place orders and track my purchase history**.

**Acceptance Criteria:**
- [ ] AC-001-1: Form đăng ký có 2 field: Email và Password
- [ ] AC-001-2: Email phải đúng format (có @, domain)
- [ ] AC-001-3: Nếu email đã tồn tại → hiển thị lỗi "Email already registered"
- [ ] AC-001-4: Mật khẩu được hash bằng bcrypt trước khi lưu vào DB
- [ ] AC-001-5: Tài khoản mới mặc định có role = `user`
- [ ] AC-001-6: Sau đăng ký thành công → redirect đến trang đăng nhập

---

### US-002 · Đăng nhập 🔴 ✅

**User Story:**  
As a **registered user**, I want to **log in with my email and password**, so that **I can access my account and make purchases**.

**Acceptance Criteria:**
- [ ] AC-002-1: Hệ thống xác thực email + password
- [ ] AC-002-2: Thông tin đúng → tạo JWT token (expires 24h) và lưu vào localStorage
- [ ] AC-002-3: Thông tin sai → hiển thị lỗi "Invalid credentials"
- [ ] AC-002-4: Admin login → redirect đến `admin.html`
- [ ] AC-002-5: User login → redirect đến `index.html`
- [ ] AC-002-6: Token hết hạn → yêu cầu đăng nhập lại

---

### US-003 · Đăng xuất 🔴 ✅

**User Story:**  
As a **logged-in user**, I want to **log out**, so that **my session is ended securely**.

**Acceptance Criteria:**
- [ ] AC-003-1: Click logout → xóa JWT token khỏi localStorage
- [ ] AC-003-2: Sau logout → redirect đến trang login
- [ ] AC-003-3: Sau logout → không thể truy cập protected pages mà không đăng nhập lại

---

### US-004 · Xem thông tin cá nhân 🟡 ✅

**User Story:**  
As a **logged-in user**, I want to **view my profile information**, so that **I can verify my account details**.

**Acceptance Criteria:**
- [ ] AC-004-1: Profile page hiển thị email của người dùng
- [ ] AC-004-2: Profile page hiển thị role hiện tại (user/admin)
- [ ] AC-004-3: Nếu chưa đăng nhập → redirect đến login

---

## EPIC 2: Product Browsing

---

### US-005 · Duyệt sản phẩm 🔴 ✅

**User Story:**  
As a **guest or customer**, I want to **browse all available LEGO sets**, so that **I can discover products I want to buy**.

**Acceptance Criteria:**
- [ ] AC-005-1: Trang products hiển thị danh sách sản phẩm dạng grid
- [ ] AC-005-2: Mỗi sản phẩm hiển thị: hình ảnh, tên, giá (USD), độ tuổi, số mảnh
- [ ] AC-005-3: Sản phẩm stock = 0 hiển thị badge "Out of Stock"
- [ ] AC-005-4: Sản phẩm load từ API `/products` (real-time từ database)

---

### US-006 · Tìm kiếm sản phẩm 🔴 ✅

**User Story:**  
As a **visitor**, I want to **search products by name**, so that **I can quickly find a specific LEGO set**.

**Acceptance Criteria:**
- [ ] AC-006-1: Search box ở đầu trang
- [ ] AC-006-2: Kết quả filter real-time khi nhập (không cần nhấn Enter)
- [ ] AC-006-3: Không tìm thấy kết quả → hiển thị "No products found"
- [ ] AC-006-4: Search không phân biệt hoa thường

---

### US-007 · Lọc và sắp xếp sản phẩm 🟡 ✅

**User Story:**  
As a **visitor**, I want to **filter and sort products**, so that **I can find products that match my preferences**.

**Acceptance Criteria:**
- [ ] AC-007-1: Filter theo theme (Classic, Technic, City, ...)
- [ ] AC-007-2: Filter theo trạng thái tồn kho (In Stock / Out of Stock)
- [ ] AC-007-3: Sort theo giá thấp → cao, cao → thấp, mới nhất
- [ ] AC-007-4: Filter và sort có thể kết hợp đồng thời

---

## EPIC 3: Shopping Cart

---

### US-008 · Thêm sản phẩm vào giỏ hàng 🔴 ✅

**User Story:**  
As a **visitor or customer**, I want to **add products to my cart**, so that **I can purchase multiple items at once**.

**Acceptance Criteria:**
- [ ] AC-008-1: Nút "Add to Cart" trên mỗi sản phẩm
- [ ] AC-008-2: Sản phẩm được thêm vào localStorage
- [ ] AC-008-3: Badge trên icon giỏ hàng cập nhật ngay lập tức
- [ ] AC-008-4: Sản phẩm Out of Stock → nút "Add to Cart" bị disable
- [ ] AC-008-5: Thêm sản phẩm đã có trong giỏ → tăng số lượng thêm 1

---

### US-009 · Xem và quản lý giỏ hàng 🔴 ✅

**User Story:**  
As a **shopper**, I want to **view and manage my cart**, so that **I can review my selections before purchasing**.

**Acceptance Criteria:**
- [ ] AC-009-1: Giỏ hàng hiển thị: hình ảnh, tên, giá đơn vị, số lượng, tổng từng dòng
- [ ] AC-009-2: Người dùng có thể tăng/giảm số lượng
- [ ] AC-009-3: Người dùng có thể xóa từng sản phẩm
- [ ] AC-009-4: Hệ thống tính đúng: Subtotal, Tax (8%), Shipping, Total
- [ ] AC-009-5: Shipping = $0 khi Subtotal > $100; = $10 khi Subtotal ≤ $100
- [ ] AC-009-6: Giỏ hàng trống → hiển thị thông báo và link quay lại shop

---

## EPIC 4: Order & Payment

---

### US-010 · Đặt hàng 🔴 ✅

**User Story:**  
As a **logged-in customer**, I want to **place an order from my cart**, so that **I can purchase the products I selected**.

**Acceptance Criteria:**
- [ ] AC-010-1: Chưa đăng nhập → click Checkout → redirect đến login
- [ ] AC-010-2: Giỏ hàng trống → không thể checkout
- [ ] AC-010-3: Hệ thống kiểm tra tồn kho trước khi tạo đơn; nếu không đủ → hiển thị lỗi
- [ ] AC-010-4: Đơn hàng được tạo atomic (order + order_items + payment_transaction cùng 1 transaction)
- [ ] AC-010-5: Nếu bất kỳ bước nào thất bại → rollback toàn bộ
- [ ] AC-010-6: Sau khi tạo đơn → hiển thị modal với QR code thanh toán

---

### US-011 · Thanh toán qua QR 🔴 ✅

**User Story:**  
As a **customer**, I want to **pay via QR code**, so that **I can complete my purchase quickly using my banking app**.

**Acceptance Criteria:**
- [ ] AC-011-1: Hệ thống tạo transaction reference duy nhất (format: TX-xxxxxx)
- [ ] AC-011-2: Số tiền quy đổi sang VND (1 USD = 25,000 VND) hiển thị trên QR
- [ ] AC-011-3: QR code hết hạn sau 30 phút, hiển thị thời gian đếm ngược
- [ ] AC-011-4: Người dùng nhấn "I've Transferred" để xác nhận thanh toán
- [ ] AC-011-5: Xác nhận thành công → payment status = paid, order status = confirmed
- [ ] AC-011-6: Tồn kho giảm đúng số lượng sau xác nhận
- [ ] AC-011-7: Giỏ hàng được xóa sau khi xác nhận thanh toán thành công

---

### US-012 · Xem lịch sử đơn hàng 🔴 ✅

**User Story:**  
As a **customer**, I want to **view my order history**, so that **I can track what I have purchased**.

**Acceptance Criteria:**
- [ ] AC-012-1: Trang orders hiển thị tất cả đơn hàng của user hiện tại
- [ ] AC-012-2: Mỗi đơn hiển thị: Order ID, ngày tạo, tổng tiền, trạng thái
- [ ] AC-012-3: Chưa đăng nhập → redirect đến login
- [ ] AC-012-4: User chỉ thấy đơn hàng của chính mình (không thấy đơn của user khác)

---

### US-013 · Xem chi tiết đơn hàng 🟡 ✅

**User Story:**  
As a **customer**, I want to **view the details of a specific order**, so that **I can see what I ordered and the payment status**.

**Acceptance Criteria:**
- [ ] AC-013-1: Hiển thị danh sách sản phẩm (tên, hình, số lượng, đơn giá, thành tiền)
- [ ] AC-013-2: Hiển thị subtotal, tax, shipping, total
- [ ] AC-013-3: Hiển thị trạng thái đơn hàng và trạng thái thanh toán
- [ ] AC-013-4: Hiển thị transaction reference
- [ ] AC-013-5: User cố truy cập đơn của người khác → trả về lỗi 403 Forbidden

---

## EPIC 5: Admin – Product Management

---

### US-014 · Thêm sản phẩm mới 🔴 ✅

**User Story:**  
As an **admin**, I want to **add new products to the catalog**, so that **customers can browse and purchase new items**.

**Acceptance Criteria:**
- [ ] AC-014-1: Form gồm: tên, giá, theme, độ tuổi, số mảnh, tồn kho, upload hình
- [ ] AC-014-2: Tất cả fields bắt buộc phải có giá trị hợp lệ
- [ ] AC-014-3: Giá và tồn kho không được âm
- [ ] AC-014-4: Hình ảnh được upload lên server và lưu path vào DB
- [ ] AC-014-5: Sản phẩm mới xuất hiện ngay trong danh sách sau khi tạo
- [ ] AC-014-6: Non-admin user không thể gọi endpoint này (403)

---

### US-015 · Chỉnh sửa sản phẩm 🔴 ✅

**User Story:**  
As an **admin**, I want to **edit existing product information**, so that **I can keep the catalog up to date**.

**Acceptance Criteria:**
- [ ] AC-015-1: Form chỉnh sửa được pre-fill với dữ liệu hiện tại
- [ ] AC-015-2: Admin có thể cập nhật hình ảnh mới (optional)
- [ ] AC-015-3: Thay đổi giá không ảnh hưởng đến lịch sử đơn hàng cũ (do snapshot)
- [ ] AC-015-4: Cập nhật thành công → danh sách refresh ngay lập tức

---

### US-016 · Xóa sản phẩm 🔴 ✅

**User Story:**  
As an **admin**, I want to **delete a product**, so that **I can remove items that are no longer available**.

**Acceptance Criteria:**
- [ ] AC-016-1: Hiện dialog xác nhận trước khi xóa
- [ ] AC-016-2: Xóa thành công → sản phẩm biến mất khỏi catalog
- [ ] AC-016-3: Xóa sản phẩm không ảnh hưởng đến lịch sử đơn hàng (snapshot đã lưu)
- [ ] AC-016-4: Non-admin user không thể xóa sản phẩm (403)

---

### US-017 · Cảnh báo tồn kho thấp 🟡 ✅

**User Story:**  
As an **admin**, I want to **see alerts for low stock products**, so that **I can reorder inventory in time**.

**Acceptance Criteria:**
- [ ] AC-017-1: Sản phẩm có stock ≤ 10 được highlight màu đỏ/cam
- [ ] AC-017-2: Dashboard KPI hiển thị tổng số sản phẩm low stock
- [ ] AC-017-3: Tab Inventory có thể lọc xem riêng sản phẩm low stock

---

## EPIC 6: Admin – Order & User Management

---

### US-018 · Quản lý đơn hàng 🔴 ✅

**User Story:**  
As an **admin**, I want to **view and manage all customer orders**, so that **I can process and fulfill orders efficiently**.

**Acceptance Criteria:**
- [ ] AC-018-1: Danh sách tất cả đơn hàng với: email khách, ngày đặt, tổng tiền, trạng thái
- [ ] AC-018-2: Admin có thể thay đổi trạng thái đơn hàng từ dropdown
- [ ] AC-018-3: Trạng thái hợp lệ: pending, processing, confirmed, shipped, completed, cancelled
- [ ] AC-018-4: Thay đổi được lưu ngay lập tức

---

### US-019 · Quản lý người dùng 🟡 ✅

**User Story:**  
As an **admin**, I want to **manage user accounts and roles**, so that **I can control access to the system**.

**Acceptance Criteria:**
- [ ] AC-019-1: Danh sách người dùng với: ID, email, role hiện tại
- [ ] AC-019-2: Admin có thể thay đổi role từ `user` sang `admin` và ngược lại
- [ ] AC-019-3: Thay đổi role có hiệu lực ngay (token hiện tại vẫn còn role cũ cho đến khi hết hạn)

---

## EPIC 7: Admin Dashboard & KPIs

---

### US-020 · Xem Dashboard KPIs 🔴 ✅

**User Story:**  
As an **admin**, I want to **see key performance indicators on the dashboard**, so that **I can quickly assess the business status**.

**Acceptance Criteria:**
- [ ] AC-020-1: Dashboard hiển thị 4 KPI cards: Total Products, Categories, Low Stock, Pending Orders
- [ ] AC-020-2: KPIs được tải ngay khi admin đăng nhập vào
- [ ] AC-020-3: Data là real-time từ database

---

## Bảng tóm tắt User Stories

| Epic | US ID | Tên | Priority | Status |
|------|-------|-----|----------|--------|
| Authentication | US-001 | Đăng ký | 🔴 Must | ✅ |
| Authentication | US-002 | Đăng nhập | 🔴 Must | ✅ |
| Authentication | US-003 | Đăng xuất | 🔴 Must | ✅ |
| Authentication | US-004 | Xem profile | 🟡 Should | ✅ |
| Product | US-005 | Duyệt sản phẩm | 🔴 Must | ✅ |
| Product | US-006 | Tìm kiếm | 🔴 Must | ✅ |
| Product | US-007 | Lọc & sắp xếp | 🟡 Should | ✅ |
| Cart | US-008 | Thêm vào giỏ | 🔴 Must | ✅ |
| Cart | US-009 | Quản lý giỏ hàng | 🔴 Must | ✅ |
| Order | US-010 | Đặt hàng | 🔴 Must | ✅ |
| Order | US-011 | Thanh toán QR | 🔴 Must | ✅ |
| Order | US-012 | Lịch sử đơn hàng | 🔴 Must | ✅ |
| Order | US-013 | Chi tiết đơn hàng | 🟡 Should | ✅ |
| Admin | US-014 | Thêm sản phẩm | 🔴 Must | ✅ |
| Admin | US-015 | Sửa sản phẩm | 🔴 Must | ✅ |
| Admin | US-016 | Xóa sản phẩm | 🔴 Must | ✅ |
| Admin | US-017 | Cảnh báo low stock | 🟡 Should | ✅ |
| Admin | US-018 | Quản lý đơn hàng | 🔴 Must | ✅ |
| Admin | US-019 | Quản lý users | 🟡 Should | ✅ |
| Admin | US-020 | Dashboard KPIs | 🔴 Must | ✅ |

**Tổng:** 20 User Stories | 60+ Acceptance Criteria

---

*Document Version: 1.0 | Last Updated: August 2026*
