const UserService = {
  token: localStorage.getItem("token"),

  init: function () {
    this.loadUsers();
  },

  loadUsers: function () {
    fetch("http://localhost:8000/backend/users", {
      headers: {
        "Authorization": `Bearer ${this.token}`
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
          `;
          userList.appendChild(row);
        });
      })
      .catch(error => console.error("Error loading users:", error));
  },

 
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


window.addEventListener("DOMContentLoaded", () => {
  UserService.init();
});
