const UserReviewsService = {
  init: function () {
    this.bindFormSubmit();
  },

  

  deleteReview: function (reviewId) {
    const token = localStorage.getItem("token");
    if (confirm("Are you sure you want to delete this review?")) {
      fetch(`http://localhost:8000/backend/reviews/${reviewId}`, {
        method: "DELETE",
        headers: {
          "Authorization": `Bearer ${token}`
        }
      })
        .then(response => response.json())
        .then(result => {
          alert(result.message || "Review deleted.");
          location.reload();
        })
        .catch(error => alert("Error: " + error));
    }
  },




bindFormSubmit: function () {
  const form = document.getElementById("addReviewForm");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    

    const newReview = {
      book_id: document.getElementById("add-book_id").value,
      rating: document.getElementById("add-rating").value,
      comment: document.getElementById("add-comment").value,
      user_id: localStorage.getItem("user_id")
    };
    console.log("Submitting review:", newReview);




    const token = localStorage.getItem("token");

    fetch("http://localhost:8000/backend/reviews", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`
      },
      body: JSON.stringify(newReview)
    })
    .then(async (response) => {
      const result = await response.json();
      if (!response.ok) {
        throw new Error(result.error || "Failed to create review");
      }
      alert(result.message || "Review created.");
      location.reload();
    })
    .catch(error => alert("Error creating review: " + error.message));
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

$(document).ready(() => {
  UserReviewsService.init();
});

