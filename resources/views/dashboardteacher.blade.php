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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <div class="py-8">  <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
       <div class="row">
        <div class="col-sm-3">
            <div class="small-box bg-dark">
                <div class="inner">
                <h1 class="h1">{{$grades}}<br><br>
                <h5 class="">Grades Submissions</h5>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                </div>
              

             
            </div>
    
            <div class="col-sm-3">
                <div class="small-box bg-success">
                    <div class="inner">
                    <h1 class="h1">{{$published}}</h1><br><br>
                    <h5 class="">Grades Published</h5>
                    </div>
                    <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                    </div>
                    </div>
                  
    
                 
                </div>
                <div class="col-sm-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                        <h1 class="h1">{{$initial}}<br><br>
                        <h5 class="">Grades Initially Uploaded</h5>
                        </div>
                        <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                        </div>
                        </div>
                      
        
                     
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                            <h1 class="h1">{{$needstograde}}<br><br>
                            <h5 class="">No Grade</h5>
                            </div>
                            <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                            </div>
                            </div>
                          
            
                         
                        </div>
                </div>
                  <div class="row">
                        <!-- Bar Chart Column -->
                        <div class="col-md-6 mb-4">  
                               <p class="h3 p-2">Grade Submission</p>
                            <canvas id="gradeChart"></canvas>
                        </div>
                
                        <!-- Pie Chart Column -->
                        <div class="col-md-6 mb-4" style="width: 500px;">
                            <p class="h3 p-2">Grades Summary</p>
                            <canvas id="gradePieChart"></canvas>
                        </div>
                    </div>
            
                <script>
                    const ctx = document.getElementById('gradeChart').getContext('2d');
                    const gradeChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Published', 'Initial', 'Needs to Grade'],
                            datasets: [{
                                label: 'Grade Submission Status',
                                data: [{{ $published }}, {{ $initial }}, {{ $needstograde }}],
                                backgroundColor: ['#4CAF50', '#ffc107', '#007bff']
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    stepSize: 1
                                }
                            }
                        }
                    });
                </script>
                   <script>
                    const labels = {!! json_encode($gradeStats->pluck('remark')) !!};
                    const totalData = {!! json_encode($gradeStats->pluck('total')) !!};
                
                // Check the value of the "Failed" count and set backgroundColor accordingly
                const passedCount = totalData[0]; // Assuming the first element is for Passed
                const failedCount = totalData[1]; // Assuming the second element is for Failed
                
                // If no failed students, set only green, otherwise, use both red and green
                const backgroundColor = failedCount === 0 ? ['#00cc00'] : ['#ff3333', '#00cc00'];
                
                const data = {
                    labels: labels,
                    datasets: [{
                        data: totalData,
                        backgroundColor: backgroundColor, // Dynamically set background color
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
            </div>
        </div>
    </div>

</x-app-layout>
