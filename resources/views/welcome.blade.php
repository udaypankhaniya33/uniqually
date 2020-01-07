<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>UniaAlly - API</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

        <style>
            html body{
                font-family: Nunito, sans-serif;
                font-weight: bold;
                height: 100%;
            }
        </style>

    </head>
    <body>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center p-5">
            <div class="col"></div>
            <div class="col-8 text-center">
                <div class="card bg-light">
                    <div class="card-body">
                        <img class="img-fluid mt-5" src="https://uniqally.com/assets/img/logo_navbar.png">
                        <h2 class="mt-5">Welcome to UniqAlly Protected API</h2>
                        <hr>
                        <p class="mt-5">
                            One-Stop-shop -
                            For all your Individual & Business Accounting and Tax needs managed by highly qualified professionals
                            offering personalized and unique solutions
                        </p>
                        <img class="img-fluid" style="height: 200px" src="data:image/svg+xml;base64,
PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDI5OS45OTUgMjk5Ljk5NSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjk5Ljk5NSAyOTkuOTk1OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMiIgaGVpZ2h0PSI1MTIiPjxnPjxnPgoJPGc+CgkJPGc+CgkJCTxwYXRoIGQ9Ik0xNDkuOTk3LDE2MS40ODVjLTguNjEzLDAtMTUuNTk4LDYuOTgyLTE1LjU5OCwxNS41OThjMCw1Ljc3NiwzLjE0OSwxMC44MDcsNy44MTcsMTMuNTA1djE3LjM0MWgxNS41NjJ2LTE3LjM0MSAgICAgYzQuNjY4LTIuNjk3LDcuODE3LTcuNzI5LDcuODE3LTEzLjUwNUMxNjUuNTk1LDE2OC40NjcsMTU4LjYxMSwxNjEuNDg1LDE0OS45OTcsMTYxLjQ4NXoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6I0FFNUFBRCIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD4KCQkJPHBhdGggZD0iTTE1MC4wMDMsODUuODQ5Yy0xMy4xMTEsMC0yMy43NzUsMTAuNjY1LTIzLjc3NSwyMy43NzV2MjUuMzE5aDQ3LjU0OHYtMjUuMzE5ICAgICBDMTczLjc3NSw5Ni41MTYsMTYzLjExMSw4NS44NDksMTUwLjAwMyw4NS44NDl6IiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBjbGFzcz0iYWN0aXZlLXBhdGgiIHN0eWxlPSJmaWxsOiNBRTVBQUQiIGRhdGEtb2xkX2NvbG9yPSIjMDAwMDAwIj48L3BhdGg+CgkJCTxwYXRoIGQ9Ik0xNDkuOTk1LDAuMDAxQzY3LjE1NiwwLjAwMSwwLDY3LjE1OSwwLDE0OS45OThjMCw4Mi44MzcsNjcuMTU2LDE0OS45OTcsMTQ5Ljk5NSwxNDkuOTk3czE1MC02Ny4xNjEsMTUwLTE0OS45OTcgICAgIEMyOTkuOTk1LDY3LjE1OSwyMzIuODM0LDAuMDAxLDE0OS45OTUsMC4wMDF6IE0xOTYuMDg1LDIyNy4xMThoLTkyLjE3M2MtOS43MzQsMC0xNy42MjYtNy44OTItMTcuNjI2LTE3LjYyOXYtNTYuOTE5ICAgICBjMC04LjQ5MSw2LjAwNy0xNS41ODIsMTQuMDAzLTE3LjI1di0yNS42OTdjMC0yNy40MDksMjIuMy00OS43MTEsNDkuNzExLTQ5LjcxMWMyNy40MDksMCw0OS43MDksMjIuMyw0OS43MDksNDkuNzExdjI1LjY5NyAgICAgYzcuOTkzLDEuNjczLDE0LDguNzU5LDE0LDE3LjI1djU2LjkxOWgwLjAwMkMyMTMuNzExLDIxOS4yMjUsMjA1LjgxOSwyMjcuMTE4LDE5Ni4wODUsMjI3LjExOHoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6I0FFNUFBRCIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD4KCQk8L2c+Cgk8L2c+CjwvZz48L2c+IDwvc3ZnPg==" />
                        <p class="text-primary mt-5">support@uniqally.com</p>
                    </div>
                </div>
            </div>
            <div class="col"></div>
        </div>
    </div>
    </body>
</html>
