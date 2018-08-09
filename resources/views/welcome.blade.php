<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script type="text/javascript">
            window.Laravel = { csrfToken: '{{ csrf_token() }}' }
        </script>
        <title>Movies</title>

        <!-- Fonts -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous"> -->
        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">

        <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script> -->
        <script type="text/javascript">
            const APP_URL = "<?php echo env('APP_URL') ?>";
            const APP_FILE = "<?php echo env('APP_FILE') ?>";
        </script>
    </head>
    <body>
        <div id="app">
            <v-app dark id="inspire">
                <navbar></navbar>
                <router-view></router-view>
                <v-footer app fixed dark class="pt-4 pb-4 text-md-center">
                    <v-layout>
                        <v-flex xs12>
                            <div>
                                <v-icon>code</v-icon>
                                with
                                <v-icon>favorite</v-icon>
                                by
                                <b>Justine Joshua Quiazon</b>
                                &nbsp;
                                - &nbsp;&nbsp;Powered By:
                                <a href="https://laravel.com/" target="_blank"><img src="https://cdn.worldvectorlogo.com/logos/laravel.svg" alt="" width="25" height="25" class="mt-2"></a>
                                <a href="https://vuejs.org/" target="_blank"><img src="https://avatars1.githubusercontent.com/u/6128107?s=400&v=4" alt="" width="25" height="25" class="mt-2"></a>
                                <a href="https://vuetifyjs.com/en/" target="_blank"><img src="https://avatars0.githubusercontent.com/u/22138497?s=400&v=4" alt="" width="25" height="25"></a>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-footer>
                <notifications position="bottom right" classes="vue-notification"/>
            </v-app>
            <!-- <div class="container-fluid">
                <router-view></router-view>
                <notifications position="bottom right" classes="vue-notification"/>
            </div> -->
        </div>
        <script src="{{ asset('js/app.js', Request::secure()) }}" type="text/javascript"></script>
        <style media="screen">
        .vue-notification {
            padding: 15px;
            margin: 0 5px 5px;

            font-size: 16px;

            color: #ffffff;
            background: #44A4FC;
            border-left: 5px solid #187FE7;

            &.warn {
                background: #ffb648;
                border-left-color: #f48a06;
            }

            &.error {
                background: #E54D42;
                border-left-color: #B82E24;
            }

            &.success {
                background: #68CD86;
                border-left-color: #42A85F;
            }
        }

        .content {
            padding-top: 90px;
        }
        </style>
    </body>
</html>
