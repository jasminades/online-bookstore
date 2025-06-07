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

function loadUserProfile() {
  const userId = localStorage.getItem("user_id");
  ProfileService.loadUserData(userId)
    .then(user => {
      document.getElementById("nameSurname").textContent = user.name + " " + user.last_name;
      document.getElementById("email").textContent = user.email;

      document.getElementById("name").value = user.name;
      document.getElementById("last_name").value = user.last_name;
      document.getElementById("email").value = user.email;

      const img = document.querySelector(".profile-img");
      img.src = user.profileImageUrl || "../static/images/logo.jpg"; 
    })
    .catch(() => {
      /* alert(""); */
    });
}


function loadUserReviews(userId) {
    $.ajax({
        url: `http://localhost:8000/backend/reviews/user/${userId}`,
        method: 'GET',
        success: function(reviews) {
            const reviewsContainer = $('#user-reviews');
            reviewsContainer.empty();

            if (reviews.length === 0) {
                reviewsContainer.append('<p>No reviews yet.</p>');
                return;
            }

            reviews.forEach(review => {
                const reviewHtml = `
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>Book ID: ${review.book_id}</h5>
                            <p>Rating: ${review.rating} / 5</p>
                            <p>Comment: ${review.comment}</p>
                            <small>Created at: ${new Date(review.created_at).toLocaleString()}</small>
                        </div>
                    </div>
                </div>`;
                reviewsContainer.append(reviewHtml);
            });
        },
        error: function(err) {
            $('#user-reviews').html('<p>No reviews.</p>');
        }
    });
}


$(document).ready(() => {
  UserReviewsService.init(); 

  const userId = localStorage.getItem("user_id");
  loadUserProfile();
  loadUserReviews(userId);
});
