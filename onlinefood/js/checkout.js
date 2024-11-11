console.log("test");

document.querySelector('.main-paymant-outlet').innerHTML=""
document.getElementById('paypal').addEventListener('click',function(){
  document.querySelector('.main-paymant-outlet').innerHTML=`<div class="paymant-outlet">
  <h3>Payment</h3>
<label for="fname">Accepted Cards</label>
<div class="icon-container">
<i class="fa fa-cc-visa" style="color:navy;"></i>
<i class="fa fa-cc-amex" style="color:blue;"></i>
<i class="fa fa-cc-mastercard" style="color:red;"></i>
<i class="fa fa-cc-discover" style="color:orange;"></i>
</div>
<div class="main-line">
<label for="cname">Name on Card</label>
<input type="text" id="cname" name="cardname" required placeholder="John More Doe">
</div>
<div class="main-line"><label for="ccnum">Credit card number</label>
<input id="ccnum" name="cardnumber" type="tel" inputmode="numeric" pattern="[0-9\s]{16}" 
autocomplete="cc-number" maxlength="16" 
placeholder="xxxx xxxx xxxx xxxx"></div>
<div class="main-linee"><div class="exp-month"><label for="expmonth">Exp Month</label>
<select name="expmonth" id="expmonth" required>
        <option value="" selected disabled hidden>Select Month</option>
        <option value="1">January</option>
        <option value='2'>February</option>
    <option value='3'>March</option>
    <option value='4'>April</option>
    <option value='5'>May</option>
    <option value='6'>June</option>
    <option value='7'>July</option>
    <option value='8'>August</option>
    <option value='9'>September</option>
    <option value='10'>October</option>
    <option value='11'>November</option>
    <option value='12'>December</option>

    </select>
</div>

<div class="exp-year">
<label for="expyear">Exp Year</label>
<input type="tel" pattern="[0-9\s]{4}" inputmode="numeric" id="expyear" required  name="expyear" minlength="4" maxlength="4" placeholder="2018">
</div>
<div class="card-cvv">
<label for="cvv">CVV</label>
<input type="tel" pattern="[0-9\s]{3}" inputmode="numeric" id="cvv" min="3" maxlength="3" required name="cvv" placeholder="352">
</div>
</div>
  </div>`
})

document.querySelector('.HELLO').addEventListener('click',function(){
  document.querySelector('.main-paymant-outlet').innerHTML=""
})
// document.getElementById("pay").addEventListener("click", function () {
  
// let paypal=document.querySelector('input[type = radio]:checked').value;
// console.log(paypal);
// if(paypal=="paypal"){
//     // Access the total price element by its id
// var totalPriceElement = document.getElementById("amount");
// var totalPriceText = totalPriceElement.innerText;
// var totalPrice = parseFloat(totalPriceText.replace("₹", ""));


//   let option = {
//     Key: "rzp_test_KlOw7YFkkbzWGd",
//     amount: totalPrice*100,
//   };
//   let rzp = new Razorpay(option);
//   rzp.open();
// }
// else{
//   return confirm('Do you want to confirm the order?');
  
  
// }
// });
