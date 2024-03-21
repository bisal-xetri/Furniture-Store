// // let cartItems = [];
// // function addToCart() {
// //   cartItems.push(cartCount);
// // }
// // function cartItemCount() {
// //   let cartCount = document.querySelector("#cart");
// //   cartCount.innerText = cartItems.length;
// // }
// document.querySelectorAll(".js-add-to-cart").forEach((button) => {
//   button.addEventListener("click", () => {
//     const productId = button.dataset.productId;

//     // Check if the product is already in the cart
//     const matchingItem = cart.find((item) => item.productId === productId);

//     if (matchingItem) {
//       // If the product is in the cart, update the quantity
//       matchingItem.quantity++;
//     } else {
//       // If the product is not in the cart, add it with quantity 1
//       cart.push({ productId, quantity: 1 });
//     }
//     let cartQuantity = 0;
//     cart.forEach((item) => {
//       cartQuantity += item.quantity;
//     });
//     document.querySelector(".js-cart-quantity").innerHTML = cartQuantity;
//     // Update the cart count display
//   });
// });

// // function updateCartCount() {
// //   const cartCountElement = document.querySelector("#cart");
// //   cartCountElement.innerText = cart.reduce(
// //     (total, item) => total + item.quantity,
// //     0
// //   );
// // }
