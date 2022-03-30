<!DOCTYPE html>
<html lang="ja">
<?php include "../layout/head.html"?>
<body class="w-screen h-screen">
    <!--nobanner-->
    <main class="flex bg-cover bg-center" style="background-image: url('https://ss1.xrea.com/geyamaclub.s203.xrea.com/Image/login.jpg')">
        <div class="md:flex-1 m-5">
            <div class="flex items-center justify-center min-h-screen rounded-lg">
                <div class="px-8 py-6 mt-4 text-left bg-white shadow-lg">
                    <div class="flex justify-center">
                        <img src="https://ss1.xrea.com/geyamaclub.s203.xrea.com/geyamaclub/Image/logo.png" class="w-20 h-20" alt="">
                    </div>
                    <h3 class="text-2xl font-bold text-center">Login to your account</h3>
                    <form action="../#practice" method="post" enctype="multipart/form-data">
                        <div class="mt-4">
                            <div class="mt-4">
                                <label class="block">Password<label>
                                        <input type="password" placeholder="Password" name="password"
                                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
                            </div>
                            <div class="flex items-baseline justify-between">
                                <input type="submit" value="Login" class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="flex-1"></div>
    </main>
    <script type="text/javascript" src="./index.js"></script>
    <?php include "../layout/script.html"?>
</body>
</html>