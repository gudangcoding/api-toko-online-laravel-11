<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Library -->
    <link rel="stylesheet" href="{{ asset('tema/library/bootstrap/css/bootstrap.min.css') }}">

    <!-- Style -->
    <link rel="stylesheet" href="style/main.css">
    <title>Cart Detail | Viola Online Clothing Store</title>
</head>

<body class="bg-white">


    <nav class="second-nav">
        <div class="text-center brand brand-second">
            <h1 class="text-uppercase text-main font-weight-bold">
                <a class='text-reset' href='/'>Viola | <span
                        class="text-capitalize font-weight-normal">Cart</span></a>
            </h1>
        </div>
    </nav>

    <main id="price">
        <div class="container">
            <!-- Cart Title -->
            <section class="title-cart">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <h1 class="text-second primary-col">My Cart</h1>
                    </div>
                </div>
            </section>

            <section class="cart">

                <!-- Cart Content -->
                <div class="row cart-content">

                    <div class="cart-product col-sm-12 col-md-8 table-responsive-md">
                        <div class="cart-product-content">
                            <table id="cart-list" class="table table-borderless">
                                <tr>
                                    <td style="width: 30%;">
                                        <img src="assets/images/pict2.jpg" alt="thumbnail-product" width="150"
                                            height="150" class="rounded-lg">
                                    </td>
                                    <td>
                                        <ul>
                                            <li class="text-main secondary-col cart-product-title">Sweet Sweater</li>
                                            <li class="text-center size-product-cart text-main">M</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li class="text-second primary-col font-weight-bold price-product-cart">
                                                $10.00</li>
                                            <li class="qty-product">1 x</li>
                                        </ul>
                                    </td>

                                    <td aria-label="close">
                                        <a href="#" class="text-reset close text-decoration-none"
                                            aria-hidden="true">&times;</a>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4">
                                        <hr class="m-0">
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 30%;">
                                        <img src="assets/images/pict3.jpg" alt="thumbnail-product" width="150"
                                            height="150" class="rounded-lg">
                                    </td>
                                    <td>
                                        <ul>
                                            <li class="text-main secondary-col cart-product-title">Simple T-Shirt</li>
                                            <li class="text-center size-product-cart text-main">M</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li class="text-second primary-col font-weight-bold price-product-cart">
                                                $10.00</li>
                                            <li class="qty-product">1 x</li>
                                        </ul>
                                    </td>

                                    <td aria-label="close">
                                        <a href="#" class="text-reset close text-decoration-none"
                                            aria-hidden="true">&times;</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="cart-summary col-sm-12 col-md-4">
                        <div class="card summary-content">
                            <div class="card-body">
                                <p class="text-main primary-col">Summary</p>
                                <table id="price-list" class="table table-borderless table-sm">
                                    <tr>
                                        <td class="text-left text-main">Sub Total</td>
                                        <td class="text-right text-second primary-col font-weight-bold">$10.8</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-main">Tax</td>
                                        <td class="text-right text-second primary-col font-weight-bold">$0.2</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-main">Shipping</td>
                                        <td class="text-right text-second primary-col font-weight-bold">$0.5</td>
                                    </tr>
                                </table>

                                <div class="coupon-field form-row">
                                    <div class="text-left col-9 col-sm-9 col-md-9">
                                        <input type="text" class="form-control" placeholder="Promo Code">
                                    </div>
                                    <div class="text-right col-3 col-sm-3 col-md-3">
                                        <button type="button"
                                            class="btn btn-coupon btn-rounded secondary-col text-main">use</button>
                                    </div>
                                </div>

                                <hr>

                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="text-left text-main secondary-col font-weight-bold">Total Price</td>
                                        <td class="text-right text-second primary-col font-weight-bold">$11.5</td>
                                    </tr>
                                </table>

                                <a class='text-center btn btn-checkout btn-block text-second' href='/success'
                                    role='button'>Checkout</a>
                            </div>

                        </div>
                    </div>

                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-5 mt-md-5">
        <div class="container">
            <div class="mb-4 footer-content row">
                <div class="footer-brand col-12 col-sm-12 col-md-3 col-lg-3">
                    <div>
                        <h1 class="text-main">VIOLA</h1>
                    </div>
                </div>

                <div class="footer-items-box col-12 col-sm-12 col-md-9 col-lg-9">
                    <div class="footer-items row">
                        <div class="footer-item col-12 col-sm-12 col-md-4">
                            <div>
                                <div class="footer-item-content">
                                    <h3 class="text-main">Store</h3>
                                    <p><a href="#">Men</a></p>
                                    <p><a href="#">Woman</a></p>
                                    <p><a href="#">Child</a></p>
                                    <p><a href="#">Teen</a></p>
                                </div>
                            </div>
                        </div>

                        <div class="footer-item col-12 col-sm-12 col-md-4">
                            <div>
                                <div class="footer-item-content">
                                    <h3 class="text-main">Business</h3>
                                    <p><a href="#">viola@gmail.com</a></p>
                                    <p><a href="#">021-1234-5678</a></p>
                                    <p><a href="#">Bekasi, Tambun Selatan</a></p>
                                </div>
                            </div>
                        </div>

                        <div class="footer-item col-12 col-sm-12 col-md-4">
                            <div>
                                <div class="footer-item-content">
                                    <h3 class="text-main">Social</h3>
                                    <a href="#" class="ig-icon">
                                        <img src="assets/images/instagram.png" alt="ig" class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="copyright-section border-top">
                <div class="row">
                    <div class="col-12">
                        <div class="mt-4 text-center copyright-content">
                            <p class="text-second">Viola Online Store Copyright &copy; <span class="yearCp"></span>
                                All Rights Reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <script src="{{ asset('tema/script/index.js') }}"></script>
</body>

</html>
