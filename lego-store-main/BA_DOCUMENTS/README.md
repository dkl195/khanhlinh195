# 📊 BA Portfolio – PLAYARENA LEGO E-Commerce MIS

## Giới thiệu dự án

**PLAYARENA** là hệ thống thương mại điện tử quản lý bán lẻ đồ chơi LEGO trực tuyến, được xây dựng với vai trò vừa là developer vừa là Business Analyst.

| | |
|---|---|
| **Loại dự án** | E-Commerce Management Information System |
| **Stack** | Node.js / Express / SQLite / Vanilla JS |
| **Thời gian** | 2026 |
| **Role** | Business Analyst + Developer |

---

## 📂 Tài liệu BA

| File | Mô tả | Dùng khi nào |
|------|-------|-------------|
| [01_BRD.md](./01_BRD.md) | Business Requirements Document | Phỏng vấn enterprise, chứng minh khả năng viết yêu cầu |
| [02_USER_STORIES.md](./02_USER_STORIES.md) | 20 User Stories + 60 Acceptance Criteria | Phỏng vấn Agile/Scrum BA |
| [03_PROCESS_FLOW.md](./03_PROCESS_FLOW.md) | 5 BPMN Process Flow diagrams | Chứng minh khả năng mô hình hóa quy trình |

### Tài liệu bổ sung (trong thư mục gốc)
| File | Mô tả |
|------|-------|
| [../USECASE_DIAGRAM.md](../USECASE_DIAGRAM.md) | 38 Use Cases chi tiết với actors |
| [../DATABASE_ERD.md](../DATABASE_ERD.md) | ERD đầy đủ, 5 entities, 3NF |

---

## 🎯 Năng lực BA thể hiện qua dự án

### Requirements Elicitation & Documentation
- Xác định 3 nhóm stakeholder (Guest, Customer, Admin)
- Viết BRD với 30+ Functional Requirements, 8 Non-Functional Requirements
- Định nghĩa 10 Business Rules cho các nghiệp vụ chính

### Process Modeling
- 5 luồng nghiệp vụ BPMN (As-Is → To-Be)
- Xác định 18+ decision points và failure scenarios
- Mô hình hóa luồng Order-to-Payment phức tạp với atomic transactions

### Data Analysis & Modeling
- Thiết kế ERD chuẩn 3NF với 5 entities
- Định nghĩa relationships, indexes, constraints
- Data snapshot pattern cho immutable order history

### Use Case Analysis
- 38 use cases đầy đủ với actors, preconditions, main/alternative flows
- Phân tích Role-Based Access Control (RBAC)
- Ma trận phân quyền: Public / Authenticated / Admin

### User Stories & Acceptance Criteria
- 20 User Stories theo format chuẩn Agile
- 60+ Acceptance Criteria có thể kiểm thử được
- Phân loại MoSCoW: Must Have / Should Have / Nice to Have

### KPI Definition
- Xác định 4 KPI cards cho Admin Dashboard
- Business metrics: Low Stock Threshold, Tax Rate, Shipping Threshold
- Payment SLA: QR expiry 30 phút

---

## 📝 Cách trình bày trong CV

```
Project: PLAYARENA – LEGO E-Commerce MIS (Personal Project)
Thời gian: 2026
Role: Business Analyst

• Thu thập và tài liệu hóa yêu cầu: viết BRD với 30+ functional requirements,
  8 NFRs, 10 business rules cho hệ thống e-commerce
• Xây dựng 20 User Stories theo chuẩn Agile với 60+ Acceptance Criteria
  có thể kiểm thử được
• Mô hình hóa 5 luồng nghiệp vụ BPMN bao gồm Order-to-Payment flow
  (12 bước, 8 failure scenarios)
• Thiết kế ERD chuẩn 3NF với 5 entities, 4 relationships, 8 DB indexes
• Phân tích 38 use cases, định nghĩa RBAC cho 3 actor groups
• Xác định KPIs cho Admin Dashboard: tổng sản phẩm, low stock, pending orders
```

---

*Last Updated: August 2026*
