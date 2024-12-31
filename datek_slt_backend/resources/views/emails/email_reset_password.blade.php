<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif
        }
    </style>
</head>

<body>
    <div style="width: 100%; background: white; padding: 50px 0; color: #000000">
        <h1 style="text-align: center; font-size: 30px">DATEK</h1>
        <div
            style="width: 45%; margin: 0 auto; background: white; border: 1px solid #ccc; border-radius: 10px; border-top: 3px solid #4e43d8; padding: 50px 20px">
            <h2>Đặt lại mật khẩu</h2>
            <div>
                <p style="color: black"> <strong style="margin-right: 8px; font-size: 20px">Xin chào </strong><a
                        href="">{{ $email }}</a>!
                </p>
                <p style="font-size: 16px">Bạn đã yêu cầu đặt lại mật khẩu. Vui lòng bấm nút bên
                    dưới để đặt lại mật khẩu của bạn.</p>
                <div style="display: flex; justify-content: center">
                    <a style="
                        text-decoration: none; 
                        background-color: #4e43d8; 
                        padding: 20px 30px; 
                        font-weight: bold; 
                        color: white; 
                        border-radius: 10px;
                        margin: 40px auto;"
                        href="{{ $resetPasswordUrl }}">ĐẶT LẠI MẬT KHẨU</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
