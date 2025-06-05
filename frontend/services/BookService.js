let BookService = {
  init: function () {
    console.log("u initu sam");
    BookService.getAllBooks();
  },

  getAllBooks: function () {
    console.log("u get sam");
    RestClient.get("/books", function (data) {
      BookService.renderBooks(data);
    }, function (error) {
      console.error("fetch error", error);
      toastr.error("loading error ");
    });
  },

  renderBooks: function (books) {
    console.log("u render sam");
    let container = $("#books-list");
    container.empty();

    books.forEach(function (book) {
      let card = `
        <div class="books-card">
          <img src="${book.image_url || '../static/images/background.jpg'}" alt="Book Cover">
          <h3 class="book-title">${book.title}</h3>
          <p class="book-author">by ${book.author}</p>
          <p class="book-category">Category: ${book.category_id || 'Unknown'}</p>
          <p class="book-price">$${parseFloat(book.price).toFixed(2)}</p>
           <button class="btn add-to-cart" data-book-id="${book.id}">Add To Cart</button>

          <button class="btn view-details" data-book='${JSON.stringify(book)}'>View Details</button>
        </div>
      `;
      container.append(card);
      
    });

    
    $(".add-to-cart").off("click").on("click", function () {
        const bookId = $(this).data("book-id");
        const selectedBook = books.find(b => b.id === bookId);
        Cart.addToCart(selectedBook);
     });

        
    $(".view-details").on("click", function () {
      const book = $(this).data("book");

      $("#single-book-title").text(book.title);
      $("#single-book-author").text(`Author: ${book.author}`);
      $("#single-book-category").text(`Category: ${book.category_name || "Unknown"}`);
      $("#single-book-description").text(book.description || "No description available.");
      $("#single-book-price").text(`Price: $${parseFloat(book.price).toFixed(2)}`);

      $("#single-book-modal").fadeIn();
    });

    $(".single-book-close-btn").on("click", function () {
      $("#single-book-modal").fadeOut();
    });

    $(window).on("click", function (event) {
      if (event.target.id === "single-book-modal") {
        $("#single-book-modal").fadeOut();
      }
    });
  },

  addToCart: function (bookId) {
    let cart = JSON.parse(localStorage.getItem("cart")) || {};

    if (cart[bookId]) {
      cart[bookId].quantity += 1;
    } else {
      cart[bookId] = {
        book_id: bookId,
        quantity: 1
      };
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    alert("Book added to cart!");
  }
};

function displayCart() {
  const cart = JSON.parse(localStorage.getItem("cart")) || {};
  const cartList = document.getElementById("cart");
  if (!cartList) return;

  cartList.innerHTML = ""; 

  Object.entries(cart).forEach(([bookId, item]) => {
    const li = document.createElement("li");
    li.textContent = `Book ID: ${item.book_id} | Quantity: ${item.quantity}`;
    cartList.appendChild(li);
  });
}


function openLogoutModal() {
  document.getElementById("logoutModal").style.display = "block";
}

function closeLogoutModal() {
  document.getElementById("logoutModal").style.display = "none";
}

function confirmLogout() {
  window.location.href = "login.html";
}

function openEditBookModal(book) {
  document.getElementById("edit-id").value = book.id;
  document.getElementById("edit-title").value = book.title;
  document.getElementById("edit-author").value = book.author;
  document.getElementById("edit-price").value = book.price;
  document.getElementById("edit-category").value = book.category_id;
  document.getElementById("editBookModal").style.display = "block";
}

function closeEditBookModal() {
  document.getElementById("editBookModal").style.display = "none";
}

document.getElementById("editBookForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const id = document.getElementById("edit-id").value;
  const updatedBook = {
    title: document.getElementById("edit-title").value,
    author: document.getElementById("edit-author").value,
    price: parseFloat(document.getElementById("edit-price").value),
    category_id: parseInt(document.getElementById("edit-category").value)
  };

  const token = localStorage.getItem("token");

  fetch(`http://localhost:8000/backend/books/${id}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "Authorization": `Bearer ${token}`
    },
    body: JSON.stringify(updatedBook)
  })
    .then(res => res.json())
    .then(data => {
      toastr.success(data.message || "Book updated successfully.");
      location.reload();
    })
    .catch(err => alert("Error updating book: " + err));
});

function deleteBook(bookId) {
  const token = localStorage.getItem("token");
  if (confirm("Are you sure you want to delete this book?")) {
    fetch(`http://localhost:8000/backend/books/${bookId}`, {
      method: "DELETE",
      headers: {
        "Authorization": `Bearer ${token}`
      }
    })
      .then(response => response.json())
      .then(result => {
        toastr.success(result.message || "Book deleted successfully."); 
        BookService.getAllBooks();

        setTimeout(() => {
          location.reload();
        }, 300);
      })
      .catch(error => alert("Error: " + error));
  }
}

document.addEventListener("DOMContentLoaded", () => {
  fetch("http://localhost:8000/backend/books")
    .then(res => res.json())
    .then(books => {
      const bookList = document.getElementById("book-list");
      if (bookList) {
        bookList.innerHTML = "";

        books.forEach(book => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${book.id}</td>
            <td>${book.title}</td>
            <td>${book.author}</td>
            <td>${book.price}</td>
            <td>${book.category_id}</td>
            <td>
              <button class="btn btn-sm btn-primary" onclick='openEditBookModal(${JSON.stringify(book)})'>Edit</button>
              <button class="btn btn-sm btn-danger" onclick="deleteBook(${book.id})">Delete</button>
            </td>
          `;
          bookList.appendChild(row);
        });
      }
    })
    .catch(error => console.error("Error loading books:", error));
});

function openAddBookModal() {
  document.getElementById("addBookModal").style.display = "block";
}

function closeAddBookModal() {
  document.getElementById("addBookModal").style.display = "none";
}

document.getElementById("addBookForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const newBook = {
    title: document.getElementById("add-title").value,
    author: document.getElementById("add-author").value,
    price: parseFloat(document.getElementById("add-price").value),
    category_id: parseInt(document.getElementById("add-category").value)
  };

  const token = localStorage.getItem("token");

  fetch("http://localhost:8000/backend/books", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Authorization": `Bearer ${token}`
    },
    body: JSON.stringify(newBook)
  })
    .then(res => res.json())
    .then(data => {
      toastr.success("Book added!");
      BookService.getAllBooks();

      setTimeout(() => {
        location.reload();
      }, 300);
    })
    .catch(err => alert("Error adding book: " + err));
});


