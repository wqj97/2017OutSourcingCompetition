@extends('layouts.admin')

@section('content')
    <script src="{{ asset('bower-assets/chart.js/dist/Chart.min.js') }}"></script>
    <div class="container-fluid">
        <div class="text-center">
            <h3>{{ trans('dashboard.The Trends') }}</h3>
            <br>

            <canvas id="Timeline" width="400" height="140"></canvas>
            <canvas id="Topic" width="400" height="140"></canvas>
            <canvas id="User" width="400" height="140"></canvas>
            <canvas id="Notice" width="400" height="140"></canvas>
            <script>

              function renderTrendChart (data) {
                new Chart(document.getElementById("Timeline"), {
                  type: 'line',
                  data: {
                    labels: data.label,
                    datasets: [data.datasets[0]],
                  }
                });
                new Chart(document.getElementById("Topic"), {
                  type: 'line',
                  data: {
                    labels: data.label,
                    datasets: [data.datasets[1]],
                  }
                });
                new Chart(document.getElementById("User"), {
                  type: 'line',
                  data: {
                    labels: data.label,
                    datasets: [data.datasets[2]],
                  }
                });
                new Chart(document.getElementById("Notice"), {
                  type: 'line',
                  data: {
                    labels: data.label,
                    datasets: [data.datasets[3]],
                  }
                });
              }

              $.ajax({
                type: 'GET',
                url: '/dashboard/trend/my-trend',
                success: function (response, status, xhr) {
                  renderTrendChart(response);
                },
                error: function () {
                  alert('the trend render fail');
                }
              })
            </script>
        </div>
    </div>
@endsection

