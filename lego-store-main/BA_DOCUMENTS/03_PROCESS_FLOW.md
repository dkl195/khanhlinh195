# Process Flow & BPMN Diagrams
**Project:** PLAYARENA – LEGO E-Commerce MIS  
**Version:** 1.0  
**Author:** [Your Name]  
**Date:** August 2026

> Tài liệu này mô tả các luồng nghiệp vụ chính của hệ thống PLAYARENA dưới dạng BPMN process flow, bao gồm As-Is (nếu có) và To-Be (hệ thống hiện tại).

---

## FLOW 1: Luồng Đặt Hàng & Thanh Toán (Order-to-Payment)

### Mô tả
Luồng quan trọng nhất của hệ thống, từ khi khách hàng bắt đầu checkout đến khi đơn hàng được xác nhận và tồn kho được cập nhật.

### BPMN Diagram

```mermaid
flowchart TD
    Start([🟢 Khách hàng\nnhấn Checkout]) --> A{Đã đăng nhập?}
    
    A -- Chưa --> B[Redirect đến\ntrang Login]
    B --> B1[Đăng nhập thành công] --> C
    
    A -- Rồi --> C{Giỏ hàng\ntrống?}
    
    C -- Trống --> D[Hiển thị lỗi\n'Cart is empty']
    D --> End1([🔴 Dừng])
    
    C -- Có hàng --> E[Gửi POST /orders\nvới danh sách items]
    
    E --> F{Kiểm tra\ntồn kho}
    
    F -- Không đủ --> G[Trả lỗi\n'Not enough stock']
    G --> End2([🔴 Dừng])
    
    F -- Đủ --> H[BEGIN TRANSACTION]
    
    H --> I[Tạo bản ghi Orders\nstatus = pending]
    I --> J[Tạo Order Items\nsnapshot sản phẩm]
    J --> K[Tạo Payment Transaction\nstatus = pending]
    K --> L[Generate TX-REF\nduy nhất]
    L --> M[Tạo QR payload\nquy đổi sang VND]
    M --> N[COMMIT TRANSACTION]
    
    N --> O[Hiển thị\nQR Payment Modal]
    O --> P{QR hết hạn\nsau 30 phút?}
    
    P -- Hết hạn --> Q[Hiển thị\n'QR Expired']
    Q --> End3([🔴 Đơn chờ / Huỷ])
    
    P -- Còn hạn --> R[Khách chuyển\nkhoản ngân hàng]
    R --> S[Nhấn\n'I've Transferred']
    
    S --> T[POST /payments/\ntxRef/confirm]
    T --> U{Transaction\ntồn tại?}
    
    U -- Không --> V[Lỗi\n'Transaction not found']
    V --> End4([🔴 Dừng])
    
    U -- Có --> W{Status =\npending?}
    
    W -- Đã paid --> X[Trả về success\n'Already paid']
    X --> End5([✅ OK])
    
    W -- Không phải pending --> Y[Lỗi\n'Not pending']
    Y --> End6([🔴 Dừng])
    
    W -- Pending --> Z[BEGIN TRANSACTION]
    Z --> Z1{Kiểm tra stock\nlần 2}
    
    Z1 -- Không đủ --> Z2[ROLLBACK\nHiển thị lỗi]
    Z2 --> End7([🔴 Dừng])
    
    Z1 -- Đủ --> AA[UPDATE payment\nstatus = paid\npaid_at = NOW]
    AA --> AB[UPDATE order\nstatus = confirmed]
    AB --> AC[DECREMENT stock\ncho từng sản phẩm]
    AC --> AD[COMMIT]
    
    AD --> AE[Xóa giỏ hàng\ntrên browser]
    AE --> AF[Hiển thị\n✅ Success Message]
    AF --> End8([🟢 Hoàn tất])
    
    style Start fill:#00C851,color:#fff
    style End8 fill:#00C851,color:#fff
    style End1 fill:#ff4444,color:#fff
    style End2 fill:#ff4444,color:#fff
    style End3 fill:#ff8800,color:#fff
    style End4 fill:#ff4444,color:#fff
    style End6 fill:#ff4444,color:#fff
    style End7 fill:#ff4444,color:#fff
    style H fill:#4A90D9,color:#fff
    style N fill:#4A90D9,color:#fff
    style Z fill:#4A90D9,color:#fff
    style AD fill:#4A90D9,color:#fff
```

### Key Business Rules trong Flow này
| Bước | Business Rule |
|------|--------------|
| Tính tổng | Subtotal + Tax(8%) + Shipping($10, free >$100) |
| QR expiry | Hết hạn sau 30 phút |
| VND conversion | 1 USD × 25,000 = VND |
| Double check stock | Kiểm tra tồn kho 2 lần (khi tạo đơn & khi xác nhận) |
| Atomic transaction | Tất cả bước trong 1 DB transaction |
| Snapshot | Order items lưu giá/tên tại thời điểm đặt hàng |

---

## FLOW 2: Luồng Xác Thực & Phân Quyền (Authentication & Authorization)

```mermaid
flowchart TD
    Start([Request đến\nAPI endpoint]) --> A{Token\ntrong header?}
    
    A -- Không có --> B[401 Unauthorized]
    B --> End1([🔴 Dừng])
    
    A -- Có --> C{Verify\nJWT token}
    
    C -- Invalid/Expired --> D[401 Token Invalid]
    D --> End2([🔴 Dừng])
    
    C -- Valid --> E[Decode user info\nid, email, role]
    
    E --> F{Route yêu cầu\nAdmin role?}
    
    F -- Không --> G[✅ Proceed\nto Controller]
    
    F -- Có --> H{User role\n= admin?}
    
    H -- Không --> I[403 Forbidden\n'Admin access required']
    I --> End3([🔴 Dừng])
    
    H -- Có --> G
    G --> End4([✅ Xử lý request])
    
    style Start fill:#4A90D9,color:#fff
    style End4 fill:#00C851,color:#fff
    style End1 fill:#ff4444,color:#fff
    style End2 fill:#ff4444,color:#fff
    style End3 fill:#ff4444,color:#fff
```

### Protected Routes
| Route Pattern | Yêu cầu |
|--------------|---------|
| `GET /products` | Public (không cần token) |
| `POST /orders` | Authenticated (any role) |
| `GET /orders` | Authenticated (own data only) |
| `GET /admin/*` | Admin role required |
| `POST /admin/products` | Admin role required |
| `PATCH /admin/orders/:id/status` | Admin role required |

---

## FLOW 3: Luồng Quản Lý Sản Phẩm - Admin (Product Management)

```mermaid
flowchart TD
    Start([Admin vào\ntab Inventory]) --> A[Load danh sách\nsản phẩm]
    A --> B{Admin muốn\nlàm gì?}
    
    B -- Thêm mới --> C[Mở form\nAdd Product]
    C --> D[Điền thông tin:\ntên, giá, stock,\nage, pieces, theme]
    D --> E{Upload\nhình ảnh?}
    E -- Có --> F[Multer validate\nfile type & size]
    F -- Invalid --> G[Lỗi: Invalid file]
    F -- Valid --> H[Lưu file vào\n/uploads/]
    H --> I
    E -- Không --> I[POST /admin/products]
    I --> J{Validate\ninput}
    J -- Lỗi --> K[Hiển thị\nvalidation errors]
    J -- OK --> L[INSERT into products DB]
    L --> M[✅ Sản phẩm mới\nxuất hiện trong list]
    
    B -- Sửa --> N[Pre-fill form\nvới data hiện tại]
    N --> O[Admin sửa fields]
    O --> P[PUT /admin/products/:id]
    P --> Q[UPDATE products DB]
    Q --> R[✅ List refresh]
    
    B -- Xóa --> S[Hiện confirm dialog\n'Are you sure?']
    S -- Hủy --> B
    S -- Xác nhận --> T[DELETE /admin/products/:id]
    T --> U[DELETE FROM products]
    U --> V[Note: Order history\nvẫn giữ nguyên\n do snapshot]
    V --> W[✅ List refresh]
    
    style Start fill:#4A90D9,color:#fff
    style M fill:#00C851,color:#fff
    style R fill:#00C851,color:#fff
    style W fill:#00C851,color:#fff
```

---

## FLOW 4: Luồng Đăng Ký & Đăng Nhập (Registration & Login)

### As-Is Process (Trước khi có hệ thống)
```
Khách hàng muốn mua → Gọi điện / nhắn tin thủ công → Admin xử lý thủ công
→ Không có tracking → Không có order history → Dễ xảy ra sai sót
```

### To-Be Process (Hệ thống PLAYARENA)

```mermaid
flowchart LR
    subgraph Guest["👤 Guest User"]
        A([Truy cập website]) --> B[Duyệt sản phẩm]
        B --> C{Muốn mua?}
        C -- Chưa có tài khoản --> D[Đăng ký]
        C -- Có tài khoản --> E[Đăng nhập]
    end
    
    subgraph Register["📝 Đăng Ký"]
        D --> D1[Nhập Email\n+ Password]
        D1 --> D2{Email\ntồn tại?}
        D2 -- Rồi --> D3[❌ Email exists]
        D2 -- Chưa --> D4[Hash password\nbcrypt 10 rounds]
        D4 --> D5[INSERT user\nrole = 'user']
        D5 --> D6[✅ Redirect Login]
    end
    
    subgraph Login["🔑 Đăng Nhập"]
        E --> E1[Nhập Email\n+ Password]
        E1 --> E2[Verify credentials]
        E2 -- Sai --> E3[❌ Invalid credentials]
        E2 -- Đúng --> E4[Generate JWT\nexpires 24h]
        E4 --> E5{Role?}
        E5 -- admin --> E6[Redirect admin.html]
        E5 -- user --> E7[Redirect index.html]
    end
    
    style Guest fill:#e3f2fd
    style Register fill:#f3e5f5
    style Login fill:#e8f5e8
```

---

## FLOW 5: Admin Dashboard – KPI Calculation

```mermaid
flowchart TD
    Start([Admin login\nthành công]) --> A[Load Dashboard]
    
    A --> B[Parallel fetch]
    
    B --> C[GET /products]
    B --> D[GET /admin/orders]
    B --> E[GET /admin/users]
    
    C --> F[Count total products]
    C --> G[Count unique themes\n= Categories]
    C --> H[Count stock ≤ 10\n= Low Stock]
    
    D --> I[Count status = pending\n= Pending Orders]
    D --> J[Sum total của\nconfirmed orders\n= Revenue]
    
    E --> K[Count total users]
    
    F & G & H --> KPI1[📦 Products KPIs]
    I & J --> KPI2[📋 Orders KPIs]
    K --> KPI3[👥 Users KPI]
    
    KPI1 & KPI2 & KPI3 --> L[Render 4 KPI Cards]
    L --> End([✅ Dashboard Ready])
    
    style Start fill:#4A90D9,color:#fff
    style End fill:#00C851,color:#fff
    style KPI1 fill:#fff9c4
    style KPI2 fill:#fff9c4
    style KPI3 fill:#fff9c4
```

---

## Summary – Danh sách Processes

| Flow | Tên | Actors | Complexity |
|------|-----|--------|-----------|
| FLOW 1 | Order-to-Payment | Customer, System, Payment GW | ⭐⭐⭐ High |
| FLOW 2 | Authentication & Authorization | All users, System | ⭐⭐ Medium |
| FLOW 3 | Product Management (Admin) | Admin, System | ⭐⭐ Medium |
| FLOW 4 | Registration & Login | Guest, Customer, System | ⭐ Low |
| FLOW 5 | KPI Dashboard Calculation | Admin, System | ⭐ Low |

---

## Process Metrics

| Metric | Value |
|--------|-------|
| Tổng số luồng nghiệp vụ chính | 5 |
| Tổng số decision points (gateways) | 18+ |
| Critical path (Order-to-Payment) | 12 bước chính |
| Failure points được xử lý | 8 loại lỗi |
| External system integrations | 1 (VietQR) |

---

*Document Version: 1.0 | Last Updated: August 2026*
