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
    alert(data.message || "Book updated.");
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
      alert(result.message || "Book deleted.");
      location.reload();
    })
    .catch(error => alert("Error: " + error));
  }
}

document.addEventListener("DOMContentLoaded", () => {
  fetch("http://localhost:8000/backend/books")
    .then(res => res.json())
    .then(books => {
      const bookList = document.getElementById("book-list");
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
    alert(data.message || "Book added.");
    location.reload();
  })
  .catch(err => alert("Error adding book: " + err));
});


