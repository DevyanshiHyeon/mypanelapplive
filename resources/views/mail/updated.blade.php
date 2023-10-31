<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Template</title>
    <style>
        /* Responsive image styling */
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td>
                <img src="{{ $data['logo'] }}" alt="Application Logo" height="60px" width="60px">
            </td>
        </tr>
        <tr>
            <td>
                <h2><b>App Name</b>: <a href="https://play.google.com/store/apps/details?id={{ $data['package'] }}">{{ $data['app_name'] }}</a></h2>
                <p><b>App Status</b>: {{ $data['status'] }}</p>
                <p><b>Package Name</b>: {{ $data['package'] }}</p>
                @if(isset($data['account_name']))
                <p>Account name: <a href="https://play.google.com/store/apps/developer?id={{$data['account_name']}}"> {{$data['account_name']}} </a>
                @endif
            </td>
        </tr>
    </table>
</body>
</html>

