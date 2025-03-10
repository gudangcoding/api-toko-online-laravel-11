<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Library  -->
    <link rel="stylesheet" href="{{ asset('tema/library/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Styles -->
    <link rel="stylesheet" href="style/main.css" />
    <title>Login Page | Toko Online</title>
</head>

<body>

    <div class="wrap-login">
        <header>
            <nav class="second-nav">
                <div class="text-center brand brand-second">
                    <h1 class="text-uppercase text-main font-weight-bold"><a class='text-reset' href='/'>Viola</a>
                    </h1>
                </div>
            </nav>
        </header>

        <main class="container-custom">
            <div class="banner-side">
                <div class="banner-side-image">
                    <div class="banner-side-text">
                        <h1 class="text-main" data-aos="fade-right">Enjoy Your
                            <br>
                            Shopping Everyday
                        </h1>
                    </div>
                </div>
            </div>

            <div class="container container-login">
                <div class="login-form row" data-aos="fade-left">
                    <div class="col-12 col-sm-6 offset-sm-6">
                        <div class="bg-white border shadow card-login card">
                            <h2 class="text-center text-main primary-col">LOGIN</h2>
                            <div class="field-user card-body">
                                <form action="#" method="POST">
                                    <div class="form-group field-user-input">
                                        <label for="username" class="text-second field">Email</label>
                                        <input type="email" class="form-control" id="username" placeholder="Email"
                                            autocomplete="off" required>
                                    </div>
                                    <div class="form-group field-user-input">
                                        <label for="password" class="text-second field">Password</label>
                                        <input type="password" class="form-control" id="password"
                                            placeholder="Password" required>
                                    </div>
                                    <div class="text-right form-group forgot-link">
                                        <small><a href="#" class="text-second">Forgot Password?</a></small>
                                    </div>
                                    <button type="submit"
                                        class="btn btn-login text-second text-uppercase">Login</button>
                                    <!-- Membeuat dont -->
                                    <p class="text-center text-second text-add">Don't have Account?<a href="#">
                                            Create Account</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <br><br>
            </div>

        </main>

    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
