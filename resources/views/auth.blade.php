<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    </head>
    <body>
        <div class="row">
            <div class="col-4 p-5">
                <form class="p-4 pt-5 text-center" action="login" method="post">
                    @csrf
                    <h1 class="text-center " style="color: #217fa5">Admin</h1>
                    <h3 class="text-center ">Welcome Back !</h3>
                    <h4 class="my-5 text-end fw-bold">قم بتسجيل الدخول الى حسابك</h4>

                    <div class="form-group text-end">
                        <label class="text-end my-2 text-secondary fw-bold" for="username">اسم المستخدم</label>
                        <input type="text" class="form-control text-end" id="username" placeholder="أدخل اسم المستخدم" name="username" required>
                    </div>

                    <div class="form-group text-end">
                        <label class="text-end my-2 text-secondary fw-bold" for="password">كلمة المرور</label>
                        <input type="password" class="form-control text-end" id="password" placeholder="أدخل كلمة المرور" name="password" required>
                    </div>

                    <button type="submit" class="text-center mt-5 w-50 btn " style="background-color: #217fa5;color: white">تسجيل الدخول</button>
                </form>
            </div>

            <div class="col-8 p-5 text-center" style="background-image: linear-gradient(to bottom left,#65a7c1, #f5fbff)">
                <img class="w-50 mt-5" src="/icons/juzour.png">
            </div>
        </div>
    </body>
</html>
