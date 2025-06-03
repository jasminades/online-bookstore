const OrdersService = {
  init: function () {
    this.loadOrders();
    this.bindFormSubmit();
  },

  loadOrders: function () {
    fetch("http://localhost:8000/backend/orders", {
      headers: {
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    })
      .then(res => res.json())
      .then(orders => {
        const orderList = document.getElementById("order-list");
        orderList.innerHTML = "";

        orders.forEach(order => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${order.id}</td>
            <td>${order.user_id}</td>
            <td>${order.total_price}</td>
            <td>
              <select onchange="OrdersService.updateStatus(${order.id}, this.value)" class="form-select form-select-sm">
                <option value="0" ${order.status == 0 ? "selected" : ""}>Pending</option>
                <option value="1" ${order.status == 1 ? "selected" : ""}>To Be Made</option>
                <option value="2" ${order.status == 2 ? "selected" : ""}>Completed</option>
                <option value="3" ${order.status == 3 ? "selected" : ""}>Paused</option>
              </select>
            </td>
            <td>${order.created_at}</td>
            <td>${order.book_id}</td>
            <td>
              <button class="btn btn-sm btn-danger" onclick="OrdersService.deleteOrder(${order.id})">Delete</button>
            </td>
          `;
          orderList.appendChild(row);
        });
      })
      .catch(error => console.error("Error loading orders:", error));
  },

  deleteOrder: function (orderId) {
    const token = localStorage.getItem("token");
    if (confirm("Are you sure you want to delete this order?")) {
      fetch(`http://localhost:8000/backend/orders/${orderId}`, {
        method: "DELETE",
        headers: {
          "Authorization": `Bearer ${token}`
        }
      })
        .then(response => response.json())
        .then(result => {
          alert(result.message || "Order deleted.");
          location.reload();
        })
        .catch(error => alert("Error: " + error));
    }
  },

  updateStatus: function (orderId, newStatus) {
    const token = localStorage.getItem("token");
    fetch(`http://localhost:8000/backend/orders/${orderId}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`
      },
      body: JSON.stringify({ status: parseInt(newStatus) })
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message || "Status updated.");
      })
      .catch(err => alert("Error updating status: " + err));
  },


   statusToText: function (status) {
    const map = {
      0: "Pending", 1: "To Be Made", 2: "Completed", 3: "Paused",
      "pending": "Pending", "to_be_made": "To Be Made",
      "completed": "Completed", "paused": "Paused"
    };
    return map[status] || "Unknown";
  },


  bindFormSubmit: function () {
  const form = document.getElementById("addOrderForm");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const newOrder = {
      id: parseInt(document.getElementById("add-id").value),
      user_id: parseInt(document.getElementById("add-user_id").value),
      total_price: parseFloat(document.getElementById("add-price").value),
      status: 0,
      created_at: document.getElementById("add-created_at").value,
      book_id: parseInt(document.getElementById("add-book_id").value)
    };

    const token = localStorage.getItem("token");

    fetch("http://localhost:8000/backend/orders", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`
      },
      body: JSON.stringify(newOrder)
    })
    .then(response => response.json())
    .then(result => {
      alert(result.message || "Order created.");
      location.reload();
    })
    .catch(error => alert("Error creating order: " + error));
    });
  }
};


function openLogoutModal() {
  document.getElementById("logoutModal").style.display = "block";
}

function closeLogoutModal() {
  document.getElementById("logoutModal").style.display = "none";
}

function confirmLogout() {
  window.location.href = "login.html";
}
