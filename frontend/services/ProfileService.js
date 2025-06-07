const ProfileService = {
  user: null,

  init: function () {
    this.user = Utils.get_from_localstorage("user");  

    if (!this.user) {
      console.error("User not found in localStorage.");
      return;
    }

    $('#profile_email').html(this.user.email);
    $('#profile_username').html(this.user.username);

    $("#nameSurname").text(`${this.user.first_name || ""} ${this.user.last_name || ""}`);
    $("#email").text(this.user.email || "");
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
    document.getElementById("name").value = this.user.first_name || "";
    document.getElementById("last_name").value = this.user.last_name || "";
    document.getElementById("email").value = this.user.email || "";
  },


  saveProfileChanges: function (event) {
  event.preventDefault();

  const firstName = document.getElementById("name").value.trim();
  const lastName = document.getElementById("last_name").value.trim();
  const email = document.getElementById("email").value.trim();

  $("#nameSurname").text(`${firstName} ${lastName}`);
  $("#email").text(email);

  this.user.first_name = firstName;
  this.user.last_name = lastName;
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
