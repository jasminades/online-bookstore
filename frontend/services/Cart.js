const Cart = {
  init: function(userId) {
    this.userId = userId;
    this.cartKey = `cart_${this.userId}`;
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


const cartBtn = document.getElementById('cart-btn');
const cartModal = document.getElementById('cart-modal');
const cartClose = document.getElementById('cart-close');
const cartEmptyMsg = document.getElementById('cart-empty-msg');
const cartList = document.getElementById('cart');

cartBtn.addEventListener('click', () => {
  cartModal.style.display = 'block';
});

cartClose.addEventListener('click', () => {
  cartModal.style.display = 'none';
});


window.addEventListener('click', (e) => {
  if (e.target === cartModal) {
    cartModal.style.display = 'none';
  }
});

function updateCartCount() {
  const cart = Cart.cart || {};
  const count = Object.values(cart).reduce((acc, item) => acc + item.quantity, 0);
  document.getElementById('cart-count').textContent = count;
}


const originalRenderCart = Cart.renderCart.bind(Cart);
Cart.renderCart = function() {
  originalRenderCart();
  if (cartList.children.length === 0) {
    cartEmptyMsg.style.display = 'block';
  } else {
    cartEmptyMsg.style.display = 'none';
  }
  updateCartCount();
};

updateCartCount();


Cart.init();
Cart.cleanInvalidItems();