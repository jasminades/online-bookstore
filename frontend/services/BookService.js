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
        console.error("Failed to fetch books:", error);
        toastr.error("Failed to load books.");
      });
    },

    renderBooks: function (books) {
      console.log("u render sam");
      let container = $("#books-list");
      container.empty();

      books.forEach(function (book) {
        let card = `
          <div class="books-card">
            <img src="${book.image_url || '../static/images/book1.jpg'}" alt="Book Cover">
            <h3 class="book-title">${book.title}</h3>
            <p class="book-author">by ${book.author}</p>
            <p class="book-category">Category: ${book.category_name || 'Unknown'}</p>
            <p class="book-price">$${parseFloat(book.price).toFixed(2)}</p>
            <button class="btn add-to-cart" onclick="BookService.addToCart(${book.id})">Add to Cart</button>
            <button class="btn view-details" onclick="window.location.href='index.html#book-${book.id}'">View Details</button>
          </div>
        `;
        container.append(card);
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