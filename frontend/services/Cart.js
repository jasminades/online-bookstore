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

purchaseItems: function () {
  const token = localStorage.getItem("token");
  const userId = this.userId;

  if (!token || !userId) {
    toastr.error("User not authenticated.");
    return;
  }

  function toMySQLDateTime(date) {
    const d = new Date(date);
    return d.getFullYear() + "-" +
      String(d.getMonth() + 1).padStart(2, "0") + "-" +
      String(d.getDate()).padStart(2, "0") + " " +
      String(d.getHours()).padStart(2, "0") + ":" +
      String(d.getMinutes()).padStart(2, "0") + ":" +
      String(d.getSeconds()).padStart(2, "0");
  }

  const orders = Object.values(this.cart).map(item => ({
    user_id: userId,
    total_price: (item.quantity * item.price),
    status: "pending",  
    book_id: parseInt(item.id),
    order_date: toMySQLDateTime(new Date()),  
    quantity: item.quantity
  }));

  if (orders.length === 0) {
    toastr.info("Cart is empty.");
    return;
  }

  Promise.all(
    orders.map(order =>
      fetch("http://localhost:8000/backend/orders", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
        body: JSON.stringify(order)
      })
    )
  )
  .then(async responses => {
    for (const res of responses) {
      const text = await res.text();
      console.log('Response text:', text);

      try {
        const json = JSON.parse(text);
        console.log('Parsed JSON:', json);
      } catch (e) {
        console.error('Failed to parse JSON:', e, 'Response text:', text);
        throw e; 
      }
    }
  })
  .then(() => {
    toastr.success("Purchase successful!");
    this.clearCart();
  })
  .catch(error => {
    console.error("Error placing orders:", error);
    toastr.error("An error occurred during purchase.");
  });
},


  clearCart: function () {
    this.cart = {};
    this.saveCart();
    this.renderCart();
  },
  getItems: function() {
  return Object.values(this.cart);
}
,

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

function updateCartUI() {
    document.getElementById('cart-count').textContent = 0;
    document.getElementById('cart').innerHTML = '';
    document.getElementById('cart-empty-msg').style.display = 'block';
}


document.getElementById("purchase-btn").addEventListener("click", () => {
  Cart.purchaseItems();
});
