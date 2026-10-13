
"use strict";

// Dữ liệu khởi tạo gồm 5 công việc mẫu
const items = [
    {
        id: 1,
        title: "Viết báo cáo HTML",
        dueDate: "2026-10-20",
        priority: "Cao",
        status: "pending"
    },
    {
        id: 2,
        title: "Ôn Flexbox",
        dueDate: "2026-10-23",
        priority: "Trung bình",
        status: "pending"
    },
    {
        id: 3,
        title: "Làm bài DOM",
        dueDate: "2026-10-19",
        priority: "Cao",
        status: "done"
    },
    {
        id: 4,
        title: "Đọc tài liệu CSS",
        dueDate: "2026-10-25",
        priority: "Thấp",
        status: "pending"
    },
    {
        id: 5,
        title: "Thiết kế trang cá nhân",
        dueDate: "2026-10-21",
        priority: "Trung bình",
        status: "done"
    }
];

// Lấy các phần tử HTML
const taskForm = document.querySelector("#taskForm");
const taskTitle = document.querySelector("#taskTitle");
const dueDate = document.querySelector("#dueDate");
const priority = document.querySelector("#priority");
const searchInput = document.querySelector("#searchInput");
const statusFilter = document.querySelector("#statusFilter");
const taskList = document.querySelector("#taskList");
const taskSummary = document.querySelector("#taskSummary");
const emptyMessage = document.querySelector("#emptyMessage");

// C1: Hiển thị danh sách công việc
function renderTasks() {
    const keyword = searchInput.value.trim().toLowerCase();
    const status = statusFilter.value;

    // Tìm kiếm theo tên và lọc trạng thái
    const filteredItems = items.filter(function(item) {
        const matchName = item.title.toLowerCase().includes(keyword);
        const matchStatus =
            status === "all" || item.status === status;

        return matchName && matchStatus;
    });

    // Xóa danh sách cũ trước khi hiển thị lại
    taskList.replaceChildren();

    // Hiển thị số lượng công việc
    const completed = items.filter(function(item) {
        return item.status === "done";
    }).length;

    taskSummary.textContent =
        "Tổng số: " + items.length +
        " | Đã hoàn thành: " + completed +
        " | Chưa hoàn thành: " + (items.length - completed);

    // Thông báo khi không có kết quả
    emptyMessage.hidden = filteredItems.length !== 0;

    filteredItems.forEach(function(item) {
        // Tạo thẻ công việc
        const card = document.createElement("article");
        card.className = "task-item";

        if (item.status === "done") {
            card.classList.add("is-done");
        }

        // Phần nội dung
        const content = document.createElement("div");
        content.className = "task-content";

        const title = document.createElement("h3");
        title.className = "task-title";
        title.textContent = item.title;

        const meta = document.createElement("div");
        meta.className = "task-meta";

        // Ngày hoàn thành
        const date = document.createElement("span");
        date.textContent = item.dueDate
            ? "Hạn: " + item.dueDate.split("-").reverse().join("/")
            : "Chưa có hạn";

        // Mức ưu tiên
        const priorityBadge = document.createElement("span");
        priorityBadge.className = "badge";

        if (item.priority === "Cao") {
            priorityBadge.classList.add("priority-high");
        } else if (item.priority === "Trung bình") {
            priorityBadge.classList.add("priority-medium");
        } else {
            priorityBadge.classList.add("priority-low");
        }

        priorityBadge.textContent = "Ưu tiên: " + item.priority;

        // Trạng thái
        const statusBadge = document.createElement("span");
        statusBadge.className = "badge";

        if (item.status === "done") {
            statusBadge.classList.add("status-done");
            statusBadge.textContent = "Đã hoàn thành";
        } else {
            statusBadge.classList.add("status-pending");
            statusBadge.textContent = "Chưa hoàn thành";
        }

        meta.append(date, priorityBadge, statusBadge);
        content.append(title, meta);

        // Phần nút chức năng
        const actions = document.createElement("div");
        actions.className = "task-actions";

        const completeButton = document.createElement("button");
        completeButton.type = "button";
        completeButton.className = "btn btn-complete";
        completeButton.dataset.action = "toggle";
        completeButton.dataset.id = item.id;

        completeButton.textContent =
            item.status === "done" ? "Hoàn tác" : "Hoàn thành";

        const deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.className = "btn btn-delete";
        deleteButton.dataset.action = "delete";
        deleteButton.dataset.id = item.id;
        deleteButton.textContent = "Xóa";

        actions.append(completeButton, deleteButton);
        card.append(content, actions);

        taskList.append(card);
    });
}

// C2: Thêm công việc từ form
taskForm.addEventListener("submit", function(event) {
    event.preventDefault();

    const title = taskTitle.value.trim();

    // Kiểm tra tên không được để trống
    if (title === "") {
        alert("Vui lòng nhập tên công việc!");
        taskTitle.focus();
        return;
    }

    // Tạo công việc mới
    const newTask = {
        id: items.length > 0
            ? Math.max(...items.map(function(item) {
                return item.id;
            })) + 1
            : 1,
        title: title,
        dueDate: dueDate.value,
        priority: priority.value,
        status: "pending"
    };

    // Thêm vào mảng và cập nhật giao diện ngay
    items.unshift(newTask);

    // Xóa tìm kiếm và đưa bộ lọc về tất cả
    searchInput.value = "";
    statusFilter.value = "all";

    taskForm.reset();
    priority.value = "Trung bình";

    renderTasks();
    taskTitle.focus();
});

// C3: Tìm kiếm theo tên
searchInput.addEventListener("input", function() {
    renderTasks();
});

// C3: Lọc theo trạng thái
statusFilter.addEventListener("change", function() {
    renderTasks();
});

// C4: Đánh dấu hoàn thành hoặc xóa công việc theo ID
taskList.addEventListener("click", function(event) {
    const button = event.target.closest("button[data-action]");

    if (!button) {
        return;
    }

    const id = Number(button.dataset.id);

    const index = items.findIndex(function(item) {
        return item.id === id;
    });

    if (index === -1) {
        return;
    }

    if (button.dataset.action === "toggle") {
        // Đổi trạng thái công việc
        items[index].status =
            items[index].status === "done" ? "pending" : "done";
    } else if (button.dataset.action === "delete") {
        // Xóa đúng công việc theo ID
        const confirmed = confirm(
            'Bạn có chắc muốn xóa "' + items[index].title + '" không?'
        );

        if (!confirmed) {
            return;
        }

        items.splice(index, 1);
    }

    // Cập nhật lại danh sách sau khi thay đổi
    renderTasks();
});

// Hiển thị 5 công việc ngay khi mở trang
renderTasks();
