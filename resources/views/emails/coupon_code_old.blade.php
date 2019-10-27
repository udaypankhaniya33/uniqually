<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>vManageTax- Coupon Code</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">@media only screen and (max-width: 600px) {
            #logo {
                width: 80% !important
            }
            #main_table {
                width: 100% !important;
                margin: 0 auto !important;
            }
            #content_row {
                padding: 20px !important;
            }
            body{
                padding-top: 0 !important;
            }
            p{
                font-size:18px !important;
            }
            h2{
                font-size: 1.2em !important;
            }
        }
        @media only screen and (max-width: 768px) and (min-width: 600px) {
            #logo {
                width: 50% !important;
            }
        }
    </style>
</head>
<body style='background-image:linear-gradient(to right, #3b4498 0%, #344d9f 19%, #2266b3 51%, #058fd3 92%, #0098da 99%); font-family:"Roboto", sans-serif; height:100%; margin:0; padding-top: 10px;' >
<table id="main_table" style="box-shadow:0 8px 12px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); margin:20px auto; width:80%">
    <tr style="border-spacing: 0px; border-bottom: 1px solid black">
        <td id="logo_wrapper" style="background:white">
            <img src="https://res.cloudinary.com/dnfds8ida/image/upload/v1571914161/logo_1_hsmcjs.png" id="logo" style="display:block; height:auto; margin:2% auto; width:30%">
        </td>
    </tr>
    <tr style="border-spacing: 0px; background: blue">
        <td id="content_row" style="padding:5%">
            <p style="color:white; font-size:20px">Hi there, </p>
            <p style="color:white; font-size:20px">Thanks for completing the survey! To show our appreciation we’d like to give you a coupon code that you can use on our website
                <a href="www.vmanagetax.com" style="color:white; font-weight:bolder">www.vmanagetax.com</a> to purchase Individual/Business Tax Services.
            </p>
            <h2 style="color:white; margin-bottom:0; margin-top:0; text-shadow:darkblue 0.3px 0.2px">
                <strong>Coupon code:</strong> {{$coupon}} </h2>
            <h3 style="color:white; margin-bottom:0; margin-top:0; text-shadow:darkblue 0.3px 0.2px">
                <strong>Coupon amount:</strong> {{$discount.'% '}}off </h3>
            <p style="color:white; font-size:20px; margin-top: 10px">
                Thank you!
            </p>
        </td>
    </tr>
    <tr style="border-spacing: 0px; background: white">
        <td id="footer_row" style="padding-bottom:10px; padding-top:10px">
            <p style="color:darkblue; font-size:14px; text-align:center" align="center">Copyright @ vManageTax</p>
        </td>
    </tr>
</table>
</body>
</html>
