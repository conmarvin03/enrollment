<head><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Enrollment- Dashboard</title>
    <link rel="icon" type="image/x-icon" href="/sss.png"> 
  </head>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         
       <div class=" row">
        
                        <div class="col-lg-3 col-6">
                        
                        <div class="small-box bg-success">
                        <div class="inner">
                        <h3>{{$teacher}}</h3>
                        <p>Teachers</p>
                        </div>
                        <div class="icon">
                        <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{route('users.teacher')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                        
                        <div class="small-box bg-success">
                        <div class="inner">
                        <h3>{{$studentcount}}</h3>
                        <p>Students Enrolled</p>
                        </div>
                        <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{route('students')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                        
                        <div class="small-box bg-success">
                        <div class="inner">
                          <h3>{{$gradesubmissions}}</h3>
                          <p>Grade Sheet Submitted</p>
                        </div>
                        <div class="icon">
                        <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{route('admingrade')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                        
                        <div class="small-box bg-success">
                        <div class="inner">
                          <h3>{{$programs}}</h3>
                        <p>Number of Programs</p>
                        </div>
                        <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{route('programs')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                        </div>
                        </div>

                        <div class="row">
                        <div class="col">
                            <canvas id="programChart" width="300" height="300"></canvas>

                        </div>
                        <div class="col">
                            <canvas id="gradePieChart" width="300" height="300"></canvas>
                           
                            
                        </div>
                     
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const data = {
            labels: {!! json_encode($gradeStats->pluck('remark')) !!},
            datasets: [{
                data: {!! json_encode($gradeStats->pluck('total')) !!},
                backgroundColor: ['blue','green'], // Green for Passed, Red for Failed
            }]
        };
    
        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Passed vs Failed Students'
                    }
                }
            },
        };
    
        const gradePieChart = new Chart(
            document.getElementById('gradePieChart'),
            config
        );
    </script>
    
<script>
    const ctx = document.getElementById('programChart').getContext('2d');
    const programChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($programData->pluck('program')) !!},
            datasets: [{
                label: 'Number of Students',
                data: {!! json_encode($programData->pluck('student_count')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>

</x-app-layout>
