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
          <button class="btn add-to-cart" onclick="BookService.addToCart(${book.id})">Add to Cart</button>
          <button class="btn view-details" data-book='${JSON.stringify(book)}'>View Details</button>
        </div>
      `;
      container.append(card);
    });

    // Modal functionality
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
    console.log("Book added to cart:", bookId);
    toastr.success("Book added to cart!");
  }
};

$(document).ready(function () {
  BookService.init();
});
