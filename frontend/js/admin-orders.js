document.addEventListener("DOMContentLoaded", () => {
  const ordersList = document.getElementById("orders-list");

  function loadOrders() {
    fetch('http://localhost:8000/backend/orders')
      .then(res => res.json())
      .then(data => {
        ordersList.innerHTML = "";
        data.forEach(order => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${order.id}</td>
            <td>${order.user_id}</td>
            <td>${order.total_price}</td>
            <td>${order.status}</td>
            <td>${order.created_at}</td>
          `;
          ordersList.appendChild(row);
        });
      })
      .catch(err => console.error("Error fetching orders:", err));
  }

  loadOrders();

  const logoutBtn = document.getElementById("logoutBtn");
  const logoutModal = document.getElementById("logoutModal");
  const closeLogoutModalBtn = document.getElementById("closeLogoutModal");
  const cancelLogoutBtn = document.getElementById("cancelLogoutBtn");
  const confirmLogoutBtn = document.getElementById("confirmLogoutBtn");

  function openLogoutModal() {
    logoutModal.style.display = "block";
  }

  function closeLogoutModal() {
    logoutModal.style.display = "none";
  }

  logoutBtn.addEventListener("click", openLogoutModal);
  closeLogoutModalBtn.addEventListener("click", closeLogoutModal);
  cancelLogoutBtn.addEventListener("click", closeLogoutModal);

  confirmLogoutBtn.addEventListener("click", () => {
    window.location.href = "login.html";
  });


  const addOrderForm = document.getElementById("addOrderForm");
  addOrderForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const newOrder = {
      user_id: parseInt(document.getElementById("add-user-id").value),
      total_price: parseFloat(document.getElementById("add-total-price").value),
      status: document.getElementById("add-status").value.trim(),
    };

    const token = localStorage.getItem("token");

    fetch("http://localhost:8000/backend/orders", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`
      },
      body: JSON.stringify(newOrder),
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message || "Order added.");
      const addOrderModal = bootstrap.Modal.getInstance(document.getElementById('addOrderModal'));
      addOrderModal.hide();

      addOrderForm.reset();
      loadOrders();
    })
    .catch(err => alert("Error adding order: " + err));
  });
});
