// This is a public sample test API key.
// Don't submit any personally identifiable information in requests made with this key.
// Sign in to see your own test API key embedded in code samples.
const stripe = Stripe("pk_test_TYooMQauvdEDq54NiTphI7jx");

initialize();

// Create a Checkout Session as soon as the page loads
async function initialize() {
  try {
    const response = await fetch("../scripts/handleCheckout.php", {
      method: "POST",
    });

    const data = await response.json();

    if (data.error) {
      throw new Error(data.message || 'Something went wrong');
    }

    const checkoutElement = document.getElementById('checkout');
    const loadingElement = document.getElementById('loading-message');

    const checkout = await stripe.initEmbeddedCheckout({
      clientSecret: data.clientSecret,
    });

    // First mount the checkout
    await checkout.mount("#checkout");
    
    // Then show checkout and hide loading after successful mount
    checkoutElement.style.display = 'block';
    loadingElement.style.display = 'none';

  } catch (error) {
    document.getElementById('loading-message').innerHTML = `
      <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Error</h4>
        <p>${error.message}</p>
        <hr>
        <p class="mb-0">Please try again or contact support if the problem persists.</p>
      </div>
    `;
  }
}

$(function () {
  $("#navbar").load("../components/navbar.html");
  $("#footer").load("../components/footer.html");
});
