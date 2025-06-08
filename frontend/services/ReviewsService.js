const ReviewsService = {
  token: localStorage.getItem("token"),

  init: function () {
    this.loadReviews();
  },

  loadReviews: function () {
    fetch("http://localhost:8000/backend/reviews", {
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
      .then(reviews => {
        const reviewsList = document.getElementById("reviews-list");
        reviewsList.innerHTML = "";

        reviews.forEach(review => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${review.id}</td>
            <td>${review.user?.name || review.user_id}</td>
            <td>${review.book?.title || review.book_id}</td>
            <td>${review.rating}</td>
            <td>${review.comment}</td>
            <td>${review.created_at}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="ReviewsService.deleteReview(${review.id})">Delete</button>
            </td>`;

          reviewsList.appendChild(row);
        });
      })
      .catch(error => console.error("Error loading reviews:", error));
  },

  deleteReview: function (reviewId) {
    if (confirm("Are you sure you want to delete this review?")) {
      fetch(`http://localhost:8000/backend/reviews/${reviewId}`, {
        method: "DELETE",
        headers: {
          "Authorization": `Bearer ${this.token}`
        }
      })
        .then(response => response.json())
        .then(result => {
          alert(result.message || "Review deleted.");
          this.loadReviews();
        })
        .catch(error => alert("Error: " + error));
    }
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


window.addEventListener("DOMContentLoaded", () => {
  ReviewsService.init();
});
