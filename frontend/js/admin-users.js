function openLogoutModal() {
  document.getElementById("logoutModal").style.display = "block";
}

function closeLogoutModal() {
  document.getElementById("logoutModal").style.display = "none";
}

function confirmLogout() {
  window.location.href = "login.html";
}


function deleteUser(userId) {
  const token = localStorage.getItem("token");
  if (confirm("Are you sure you want to delete this user?")) {
    fetch(`http://localhost:8000/backend/users/${userId}`, {
       method: "DELETE",
        headers: {
          "Authorization": `Bearer ${token}`
        }
    })
    .then(response => response.json())
    .then(result => {
      alert(result.message || "User deleted.");
      location.reload();
    })
    .catch(error => alert("Error: " + error));
  }
}

fetch("http://localhost:8000/backend/users", {
  headers: {
    "Authorization": `Bearer ${localStorage.getItem("token")}`
  }
})
  .then(res => {
    if (!res.ok) {
      return res.text().then(text => { throw new Error(text); });
    }
    return res.json();
  })
  .then(users => {
    const userList = document.getElementById("user-list");
    userList.innerHTML = "";

    users.forEach(user => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${user.id}</td>
        <td>${user.first_name}</td>
        <td>${user.last_name}</td>
        <td>${user.email}</td>
        <td>${user.role}</td>
        <td>${user.created_at}</td>
        <td>
          <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">Delete</button>
        </td>
      `;
      userList.appendChild(row);
    });
  })
  .catch(error => console.error("Error loading users:", error));

