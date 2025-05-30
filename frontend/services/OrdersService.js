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
            <td>${this.statusToText(order.status)}</td>
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

  statusToText: function (status) {
    switch (status) {
      case 0: return "Pending";
      case 1: return "Shipped";
      case 2: return "Delivered";
      case 3: return "Cancelled";
      default: return "Unknown";
    }
  },

  bindFormSubmit: function () {
    const form = document.getElementById("addOrderForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const newOrder = {
        id: document.getElementById("add-id").value,
        user_id: document.getElementById("add-user_id").value,
        book_id: parseFloat(document.getElementById("add-book_id").value)
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
        .then(res => res.json())
        .then(data => {
          alert(data.message || "Order added.");
          location.reload();
        })
        .catch(err => alert("Error adding order: " + err));
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
