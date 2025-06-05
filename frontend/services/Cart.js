const Cart = {
  init: function () {
    this.cartKey = "cart";
    this.loadCart();
    this.renderCart();
  },

  loadCart: function () {
    const storedCart = localStorage.getItem(this.cartKey);
    this.cart = storedCart ? JSON.parse(storedCart) : {};
  },

  saveCart: function () {
    localStorage.setItem(this.cartKey, JSON.stringify(this.cart));
  },

  addToCart: function (book) {
    if (!book || !book.id || !book.title || !book.price) {
      console.warn("Invalid book object:", book);
      toastr.error("Failed to add book to cart: missing data.");
      return;
    }

    const bookId = book.id;

    if (this.cart[bookId]) {
      this.cart[bookId].quantity += 1;
    } else {
      this.cart[bookId] = {
        id: bookId,
        title: book.title,
        price: parseFloat(book.price),
        quantity: 1,
      };
    }

    this.saveCart();
    this.renderCart();
    toastr.success(`${book.title} added to cart!`);
  },

  renderCart: function () {
    const cartList = document.getElementById("cart");
    if (!cartList) return;

    cartList.innerHTML = "";

    Object.values(this.cart).forEach((item) => {
      const listItem = document.createElement("li");

      listItem.innerHTML = `
        <strong>${item.title}</strong> - Quantity: ${item.quantity} - Total: $${(item.quantity * item.price).toFixed(2)}
        <button class="remove-btn" data-id="${item.id}">Remove</button>
      `;

      cartList.appendChild(listItem);
    });

    
    document.querySelectorAll(".remove-btn").forEach((button) => {
      button.addEventListener("click", (e) => {
        const bookId = button.getAttribute("data-id");
        this.removeFromCart(bookId);
      });
    });
  },

  removeFromCart: function (bookId) {
    if (this.cart[bookId]) {
      delete this.cart[bookId];
      this.saveCart();
      this.renderCart();
      toastr.info("Item removed from cart.");
    }
  },

  clearCart: function () {
    this.cart = {};
    this.saveCart();
    this.renderCart();
  },

  cleanInvalidItems: function () {
  let changed = false;
  for (const id in this.cart) {
    const item = this.cart[id];
    if (!item.id || !item.title || !item.price) {
      delete this.cart[id];
      changed = true;
    }
  }

  if (changed) {
    this.saveCart();
    this.renderCart();
  }
}

};

Cart.init();
Cart.cleanInvalidItems();
