const ProfileService = {
  user: null,

  init: async function () {
    this.user = Utils.get_from_localstorage("user");
    if (!this.user) {
      window.location.href = "#login";
      return;
    }

    await this.loadUserData(this.user.id);
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
      const data = await RestClient.getAsync("/users/" + id);
      this.user = { ...this.user, ...data };
      Utils.set_to_localstorage("user", this.user);

      $("#nameSurname").text(`${data.first_name} ${data.last_name}`);
      $("#email").text(data.email);

      const profileImage = this.user.profileImage || "../static/images/logo.jpg";
      $(".profile-img").attr("src", profileImage);
    } catch (error) {
      console.error("Failed to load user data:", error);
    }
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
