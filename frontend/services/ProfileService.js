const ProfileService = {
  user: null,

  init: async function () {
    this.user = Utils.get_from_localstorage("user");
    if (!this.user) {
      window.location.href = "#login";
      return;
    }

    await this.loadUserData(this.user.id);
    await this.loadBooks(this.user.id);
    this.setupEventListeners();
  },

  setupEventListeners: function () {
    document.getElementById("profileImage").addEventListener("change", (event) => {
      this.handleProfileImageChange(event);
    });

    document.querySelector(".edit-profile-btn").addEventListener("click", () => {
      this.populateEditProfileForm();
      this.openModal("editProfileModal");
    });

    document.querySelector(".logout-btn").addEventListener("click", () => this.openModal("logoutModal"));
    document.querySelector("#logoutModal .confirm-btn").addEventListener("click", () => this.confirmLogout());
    document.querySelector("#logoutModal .cancel-btn").addEventListener("click", () => this.closeModal("logoutModal"));
    document.querySelector("#logoutModal .close").addEventListener("click", () => this.closeModal("logoutModal"));

    document.querySelector("#editProfileModal .close").addEventListener("click", () => this.closeModal("editProfileModal"));
    document.querySelector("#editProfileModal .cancel-btn").addEventListener("click", () => this.closeModal("editProfileModal"));

    document.getElementById("editProfileForm").addEventListener("submit", (event) => this.saveProfileChanges(event));
  },

  openModal: function (modalId) {
    document.getElementById(modalId).style.display = "block";
  },

  closeModal: function (modalId) {
    document.getElementById(modalId).style.display = "none";
  },

  loadUserData: async function (id) {
    try {
      const data = await RestClient.getAsync("users/id/" + id);
      this.user = { ...this.user, ...data };
      Utils.set_to_localstorage("user", this.user);

      $("#nameSurname").text(`${data.name} ${data.surname}`);
      $("#email").text(data.email);

      const profileImage = this.user.profileImage || "../static/images/logo.jpg";
      $(".profile-img").attr("src", profileImage);
    } catch (error) {
      console.error("Failed to load user data:", error);
    }
  },

  loadBooks: async function (userId) {
    try {
      const data = await RestClient.getAsync("books/all_profile");
      const $booksCardsRow = $("#books-cards-row");
      $booksCardsRow.empty();

      data
        .filter((book) => book.user_id === userId)
        .forEach((book) => {
          this.createCard(book).appendTo($booksCardsRow);
        });
    } catch (error) {
      console.error("Failed to load books:", error);
    }
  },

  createCard: function (book) {
    const badgeColor = book.gender === "male" ? "bg-primary" : "bg-danger";
    const imagePath =
      book.image_path && book.image_path !== 0
        ? book.image_path
        : "https://dummyimage.com/450x300/dee2e6/6c757d.jpg";

    return $(`
      <div class="col mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="${imagePath}" alt="${book.title}" />
          <div class="card-body p-3 position-relative">
            <div class="badge badge-pill ${badgeColor} position-absolute" style="top: 0.6rem; right: 0.6rem">
              ${book.gender || ''}
            </div>
            <div class="text-center">
              <h5 class="fw-bolder">${book.title}</h5>
              ${book.author}<br>
              $${parseFloat(book.price).toFixed(2)}
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
            <div class="row mb-2">
              <div class="col text-center">
                <div class="d-grid gap-2">
                  <a class="btn btn-outline-dark" href="?id=${book.id}#itempage">View</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    `);
  },

  handleProfileImageChange: function (event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
      $(".profile-img").attr("src", e.target.result);
      sessionStorage.setItem("profileImageData", e.target.result);
    };
    reader.readAsDataURL(file);
  },

  populateEditProfileForm: function () {
    document.getElementById("name").value = this.user.name || "";
    document.getElementById("email").value = this.user.email || "";
  },

  saveProfileChanges: function (event) {
    event.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();

   
    $("#nameSurname").text(name);
    $("#email").text(email);

    this.user.name = name;
    this.user.email = email;

    const profileImageData = sessionStorage.getItem("profileImageData");
    if (profileImageData) {
      this.user.profileImage = profileImageData;
      $(".profile-img").attr("src", profileImageData);
      sessionStorage.removeItem("profileImageData");
    }

    Utils.set_to_localstorage("user", this.user);


    this.closeModal("editProfileModal");
  },

  confirmLogout: function () {
    Utils.remove_from_localstorage("user");
    window.location.href = "login.html";
  }
};


RestClient.getAsync = function (url) {
  return new Promise((resolve, reject) => {
    this.get(url, (data) => {
      resolve(data);
    }, (error) => {
      reject(error);
    });
  });
};


$(document).ready(() => {
  ProfileService.init();
});
