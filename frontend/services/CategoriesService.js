const CategoriesService = {
  init: function () {
    this.loadCategories();
    this.bindFormSubmit();
  },

  loadCategories: function () {
    fetch("http://localhost:8000/backend/categories", {
      headers: {
        "Authorization": `Bearer ${localStorage.getItem("token")}`
      }
    })
      .then(res => res.json())
      .then(categories => {
        const categoriesList = document.getElementById("categories-list");
        categoriesList.innerHTML = "";

        categories.forEach(category => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${category.id}</td>
            <td>${category.name}</td>
            <td>
              <button class="btn btn-sm btn-danger" onclick="CategoriesService.deleteCategory(${category.id})">Delete</button>
            </td>
          `;
          categoriesList.appendChild(row);
        });
      })
      .catch(error => console.error("Error loading categories:", error));
  },

  deleteCategory: function (categoryId) {
    const token = localStorage.getItem("token");
    if (confirm("Are you sure you want to delete this category?")) {
      fetch(`http://localhost:8000/backend/categories/${categoryId}`, {
        method: "DELETE",
        headers: {
          "Authorization": `Bearer ${token}`
        }
      })
        .then(response => response.json())
        .then(result => {
          alert(result.message || "Category deleted.");
          location.reload();
        })
        .catch(error => alert("Error: " + error));
    }
  },




  bindFormSubmit: function () {
  const form = document.getElementById("addCategoryForm");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const newCategory = {
      name: document.getElementById("add-name").value,
    };

    const token = localStorage.getItem("token");

    fetch("http://localhost:8000/backend/categories", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`
      },
      body: JSON.stringify(newCategory)
    })
    .then(response => response.json())
    .then(result => {
      alert(result.message || "Category created.");
      location.reload();
    })
    .catch(error => alert("Error creating category: " + error));
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

