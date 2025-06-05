const ProfileService = {
  init: function () {
    const user = Utils.get_from_localstorage("user");
    if (!user) {
      window.location.href = "#login";
      return;
    }

    this.loadUserData(user.id);
    this.loadBooks(user.id);
  },

  loadUserData: function (id) {
    RestClient.get("users/id/" + id, function (data) {
      $("#nameSurname").text(data.name + " " + data.surname);
      $("#username").text(data.username);
      $("#email").text(data.email);

      let user = Utils.get_from_localstorage("user");
      if (user && user.profileImage) {
        $(".profile-img").attr("src", user.profileImage);
      } else {
        $(".profile-img").attr("src", "../static/images/logo.jpg"); 
      }
    });
  },


  loadBooks: function (id) {
    RestClient.get("books/all_profile", function (data) {
      let $booksCardsRow = $("#books-cards-row");
      data.forEach(function (book) {
        if (book.user_id == id) {
          ProfileService.createCard(book).appendTo($booksCardsRow);
        }
      });
    });
  },

  createCard: function (book) {
    let badgeColor = book.gender === "male" ? "bg-blue" : "bg-pink";
    let imagePath =
      book.image_path !== 0
        ? book.image_path
        : "https://dummyimage.com/450x300/dee2e6/6c757d.jpg";

    return $(`
      <div class="col mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="${imagePath}" alt="..." />
          <div class="card-body p-3 position-relative">
            <div class="badge badge-pill ${badgeColor} position-absolute" style="top: 0.6rem; right: 0.6rem"></div>
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
  }
};



function openLogoutModal() {
  document.getElementById("logoutModal").style.display = "block";
}

function closeLogoutModal() {
  document.getElementById("logoutModal").style.display = "none";
}

function confirmLogout() {
  Utils.remove_from_localstorage("user");
  window.location.href = "login.html";
}


function openEditProfileModal() {
  document.getElementById("editProfileModal").style.display = "block";
}

function closeEditProfileModal() {
  document.getElementById("editProfileModal").style.display = "none";
}

function saveProfileChanges(event) {
  event.preventDefault();

  const name = document.getElementById("name").value;
  const username = document.getElementById("username").value;
  const email = document.getElementById("email").value;

  document.querySelector("#nameSurname").innerText = name;
  document.querySelector("#username").innerText = username;
  document.querySelector("#email").innerText = email;

  let user = Utils.get_from_localstorage("user") || {};

  user.name = name;
  user.username = username;
  user.email = email;

  const profileImageData = sessionStorage.getItem("profileImageData");
  if (profileImageData) {
    user.profileImage = profileImageData; 
    document.querySelector(".profile-img").src = profileImageData; 
    sessionStorage.removeItem("profileImageData");
  }
  Utils.set_to_localstorage("user", user);

  closeEditProfileModal();
}



function loadWishlist() {
    const wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    
    BookService.getAllBooks().then(allBooks => {
        const wishlistBooks = allBooks.filter(book => wishlist.includes(book.id.toString()));
        
        const wishlistContainer = document.getElementById("wishlist-container");
        wishlistContainer.innerHTML = "";

        wishlistBooks.forEach(book => {
            const item = document.createElement("div");
            item.className = "wishlist-book";
            item.innerHTML = `
                <h3>${book.title}</h3>
                <p>${book.author}</p>
                <p>$${book.price}</p>
            `;
            wishlistContainer.appendChild(item);
        });
    });
}

document.getElementById("profileImage").addEventListener("change", function (event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.querySelector(".profile-img").src = e.target.result;
      sessionStorage.setItem("profileImageData", e.target.result);
    };
    reader.readAsDataURL(file);
  }
});


$(document).ready(function () {
  ProfileService.init();
});


