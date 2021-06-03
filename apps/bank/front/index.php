<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bank stat</title>

    <link rel="stylesheet" href="./assets/style.css?v=<?= rand();?>">
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- BOOTSTRAP popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <!-- BOOTSTRAP js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" 
    integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer src="./assets/main.js?v=<?= rand();?>"></script>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap justify-content-center p-0 shadow">
        <div class="navbar_button">
            <div class="button_item"></div>
            <div class="button_item"></div>
            <div class="button_item"></div>
        </div>
        <div class="navbar-brand px-3">Система збору реквізитів</div>
    </header>

    <div class="container-fluid p-0">
        <div class="content_container">
            <div class="sidebar">
                <div class="sidebar_charts">
                <ul class="nav flex-column" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <div class="linkContainer">
                            <svg class="icon ic_active" enable-background="new 0 0 502.017 502.017" height="512" viewBox="0 0 502.017 502.017" width="512" xmlns="http://www.w3.org/2000/svg">
                                <g><path d="m487.31 472.602c-20.397 0-38.108-14.518-42.113-34.515l-60.219-301.16c-6.749-33.7-36.589-58.16-70.956-58.16s-64.207 24.46-70.957 58.165l-29.947 149.762c-8.081-2.043-16.502-3.134-25.123-3.134-44.039 0-82.978 28.065-96.893 69.832-9.118 27.342-19.733 59.19-28.141 84.432-5.324 15.981-18.009 27.926-33.545 32.606v-455.722c0-8.123-6.585-14.708-14.707-14.708s-14.709 6.585-14.709 14.708v472.602c0 8.122 6.585 14.708 14.708 14.708h472.602c8.122 0 14.708-6.585 14.708-14.708-.001-8.123-6.586-14.708-14.708-14.708zm-346.575 0h-63.888c6.063-7.414 10.861-15.996 14.02-25.479 8.408-25.24 19.021-57.084 28.139-84.427 9.908-29.739 37.632-49.719 68.988-49.719 6.659 0 13.15.909 19.344 2.613l-24.492 122.491c-4.003 20.003-21.715 34.521-42.111 34.521zm58.191 0c6.176-8.334 10.629-18.079 12.765-28.751 5.928-29.668 14.123-70.647 22.809-114.081 10.175 8.461 18.085 19.731 22.483 32.929 9.117 27.339 19.731 59.183 28.137 84.424 3.16 9.482 7.958 18.065 14.021 25.478h-100.215zm162.357 0c-21.932 0-41.323-13.975-48.253-34.776-8.409-25.242-19.023-57.09-28.139-84.428-7.878-23.648-23.781-42.896-44.084-55.153l31.102-155.542c4.005-20.002 21.716-34.521 42.114-34.521 20.397 0 38.108 14.518 42.113 34.515 0 0 52.62 263.132 60.219 301.16 2.136 10.669 6.587 20.411 12.763 28.744h-67.835z"/></g></svg>
                            <button class="nav-link active p-0" id="linear-tab" type="button" value="line">Linear</button>
                        </div>
                    </li>
                    <li class="nav-item" role="presentation">
                        <div class="linkContainer">
                            <svg class="icon" enable-background="new 0 0 502.017 502.017" height="512" viewBox="0 0 502.017 502.017" width="512" xmlns="http://www.w3.org/2000/svg">
                                <g><path d="m487.31 141.781h-94.52c-8.122 0-14.708 6.585-14.708 14.708v316.114h-33.599v-457.895c0-8.123-6.585-14.708-14.708-14.708h-94.519c-8.122 0-14.708 6.585-14.708 14.708v457.895h-33.598v-174.334c0-8.122-6.585-14.708-14.708-14.708h-94.52c-8.122 0-14.708 6.585-14.708 14.708v174.333h-33.599v-457.894c0-8.123-6.585-14.708-14.707-14.708s-14.708 6.585-14.708 14.708v472.602c0 8.122 6.585 14.708 14.708 14.708h472.602c8.122 0 14.708-6.585 14.708-14.708v-330.822c-.001-8.122-6.586-14.707-14.708-14.707zm-329.776 330.821h-65.105v-159.625h65.105zm157.534 0h-65.104v-443.187h65.104zm157.534 0h-65.105v-301.406h65.105z"/></g></svg>
                            <button class="nav-link p-0" id="bar-tab" type="button" value="bar">Bar</button>
                        </div>
                    </li>
                    <li class="nav-item" role="presentation">
                        <div class="linkContainer">
                            <svg class="icon" enable-background="new 0 0 502.017 502.017" height="512" viewBox="0 0 502.017 502.017" width="512" xmlns="http://www.w3.org/2000/svg">
                                <g><path d="m251.009 0c-138.629 0-251.009 112.38-251.009 251.009s112.38 251.009 251.009 251.009 251.009-112.38 251.009-251.009-112.381-251.009-251.009-251.009zm14.707 29.897c110.489 7.279 199.124 95.915 206.404 206.404h-96.989c-6.735-57.183-52.231-102.679-109.414-109.413v-96.991zm80.859 221.112c0 52.779-42.786 95.567-95.567 95.567s-95.567-42.787-95.567-95.567 42.786-95.567 95.567-95.567 95.567 42.787 95.567 95.567zm-95.566 221.593c-122.187 0-221.594-99.406-221.594-221.594 0-117.243 91.533-213.511 206.886-221.111v96.989c-62.009 7.304-110.274 60.183-110.274 124.122 0 68.915 56.066 124.982 124.982 124.982 28.415 0 55.393-9.435 77.359-26.823l68.568 68.568c-40.437 35.487-91.712 54.867-145.927 54.867zm166.725-75.667-68.568-68.568c14.358-18.138 23.293-39.692 25.971-62.651h96.986c-3.178 48.739-22.129 94.46-54.389 131.219z"/></g></svg>
                            <button class="nav-link p-0" id="other-tab" type="button" value="pie">Pie</button>
                        </div>
                    </li>
                </ul>
                </div>
                <div class="sidebar_other">
                    <hr>
                    <button class="about" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Про цю сторінку</button>
                </div>
            </div>
            <div class="content_inner">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane show active" id="data" role="tabpanel" aria-labelledby="Graph">
                        <div class="chart_container">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Що це за діаграми?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Ці діаграми демонструють найбільш популярні банки серед студентів ДУОП.
                Усього тут є 3 вида діаграм, які працюють з однією і тією ж інформацією, але надають її у різному вигляді. <br>
                Ви студент? Ви можете надати нам свої дані через офіційного бота ДУОП у Телеграм.<br><br>
                <img src="https://upload.wikimedia.org/wikipedia/commons/8/82/Telegram_logo.svg" height="22px" width="22px"><b><font color="MediumBlue"> @OdesaPolytechnicBot</font</b>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Зрозуміло</button>
            </div>
        </div>
        </div>
    </div>

</body>

</html>
