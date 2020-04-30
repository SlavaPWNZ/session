<!DOCTYPE html>
<html >
<head>
    <meta name="_token" content="{{csrf_token()}}" />
    <title>Тестовое задание</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div id="editor">
        <div class="box">
            Максимальный пик за <b>{{ $dates }}</b> составил <b>{{ $result['users'] }}</b> пользователя:
            <table>
                <tbody>
                    @foreach ($result['times'] as $res)
                        <tr>
                            <td>{{ $res }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
