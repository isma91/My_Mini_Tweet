<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="My_Mini_Tweet is a good way to test your own ego !!" />
    <title>Welcome to My_Mini_Tweet</title>
    <link media="all" type="text/css" rel="stylesheet" href="media/css/mui.min.css" />
    <link media="all" type="text/css" rel="stylesheet" href="media/css/materialize.min.css" />
    <link media="all" type="text/css" rel="stylesheet" href="media/css/google_material_icons.css" />
    <link media="all" type="text/css" rel="stylesheet" href="media/css/style.css" />
    <script src="media/js/jquery-2.1.4.min.js"></script>
    <script src="media/js/mui.min.js"></script>
    <script src="media/js/materialize.min.js"></script>
    <script src="media/js/home_page.js"></script>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
    <div class="container" id="the_body">
        <div class="row mui-panel" id="the_menu">
            <h1 class="title">Welcome to My_Mini_Tweet !!</h1>
        </div>
        <div class="row">
            <div id="div_error">
            </div>
        </div>
        <div class="row mui-panel">
            <ul class="collapsible popout" data-collapsible="accordion">
                <li>
                    <div class="collapsible-header"><i class="material-icons">add_circle</i>Sign up</div>
                    <div class="collapsible-body">
                        <div class="row">
                            <h2 class="title">Please complete all the fields to sign up !!</h2>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">account_box</i>
                                <input id="user_lastname" type="text">
                                <label for="user_lastname">Lastname</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="user_firstname" type="text">
                                <label for="user_firstname">Firstname</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">face</i>
                                <input id="user_username" type="text">
                                <label for="user_username">Login</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">email</i>
                                <input id="user_email" type="email">
                                <label for="user_email">Email</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">vpn_key</i>
                                <input id="user_pass" type="password">
                                <label for="user_pass">Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">vpn_key</i>
                                <input id="user_confirm_pass" type="password">
                                <label for="user_confirm_pass">Confirm Password</label>
                            </div>
                        </div>
                        <div class="row end_button">
                            <button class="waves-effect btn-flat" id="inscription">Sign Up</button>
                        </div>
                    </div>
                </li>
                <li>
                  <div class="collapsible-header"><i class="material-icons">arrow_drop_down_circle</i>Sign in</div>
                  <div class="collapsible-body">
                      <div class="row">
                        <h2 class="title">Please complete all the fields to sign in !!</h2>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">accessibility</i>
                            <input id="user_login" type="text">
                            <label for="user_login">Email or Login</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">vpn_key</i>
                            <input id="user_pass" type="password">
                            <label for="user_pass">Password</label>
                        </div>
                    </div>
                    <div class="row end_button">
                        <button class="waves-effect btn-flat" id="connexion">Sign In</button>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
</body>
</html>