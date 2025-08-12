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

    <div class="py-8">  <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
       <div class="row">
        <div class="col-sm-2">
            <div class="small-box bg-dark">
                <div class="inner">
                <h1 class="h1">{{$totalUnits}}
                <h5 class="">Total Units Earned</h5>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                </div>
            <div class="small-box bg-dark">
                <div class="inner">
                <h1 class="h1">{{number_format($GWA,2);}}
                <h5 class="">General Weighted Average (GWA)</h5>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                </div>
              
                <p class="lead p-2" style="font-size: 30px;"></p>
                <div class="small-box bg-dark">
                    <div class="inner">
                    
                                @foreach ($gradeCounts as $item)
                                <tr>
                                    @if ($item->grade == 0)
                                        <h3>No Grades - {{ $item->count }}</h3>
                                    @else
                                        <h3>{{ number_format($item->grade, 2) }} - {{ $item->count }}</h3>
                                    @endif
                                </tr>
                            @endforeach
                            <p>Grade Distribution</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                    </div>
                    </div>
             
            </div>
    
        <div class="col-7">
    
<p class="h3 p-2">Current Schedule </p>
                <table class="table table-bordered" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Instructor Name</th>
                            <th>Room</th>
                            <th>Day</th>
                            <th>Time Start</th>
                            <th>Time End</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                            <tr>
                                <td>{{ $grade->subject }}</td>

                                <td>{{ $grade->teacher_name ?? 'TBA' }}</td>
                                <td>{{ $grade->room ?? 'TBA' }}</td>
                                <td>{{ $grade->day ?? 'TBA' }}</td>
                                <td>{{ $grade->timestart ?? 'TBA' }}</td>
                                <td>{{ $grade->timeend ?? 'TBA' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
        </div>
        <div class="col"> 
            
<p class="h3 p-2">Grades Summary </p><div class="small-box bg-light">

            <canvas id="gradePieChart" width="200" height="200"></canvas>
</div>
        </div>
       </div>
       <p class="h1 ">Curriculum Flow chart</p>
       <hr class="mb-4">
       {{-- <div class=" row">
        
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
                        </div> --}}
                        <style>
                            .flowchart-container {
                              display: flex;
                              flex-wrap: wrap;
                              gap: 1rem;
                              padding: 1rem;
                              width: 100%;
                            }
                          
                            .year-header {
                              font-size: 1rem;
                              font-weight: bold;
                              margin-bottom: 1rem;
                              text-align: center;
                              color: #0074cc;
                            }
                          
                            .semester-label {
                              font-weight: 600;
                              margin-bottom: 0.5rem;
                              color: #555;
                              font-size: 0.9rem;
                            }
                          
                            .subject {
                              background-color: #e3f2fd;
                              border-left: 5px solid #1976d2;
                              padding: 0.5rem;
                              margin-bottom: 0.5rem;
                              border-radius: 4px;
                              font-size: 0.85rem;
                              cursor: help;
                            }
                          </style>
                          @php
                          $prereqs = DB::table('prereqs as p')
                              ->join('curriculums as c1', 'p.courseCode', '=', 'c1.id')
                              ->join('curriculums as c2', 'p.preReq', '=', 'c2.id')
                              ->select('c1.courseCode as course', 'c2.courseCode as prerequisite')
                              ->where('p.pID', '=',Auth::user()->pID)
                              ->get();
                          @endphp
                          <script src="https://cdn.jsdelivr.net/npm/leader-line-new/leader-line.min.js"></script>
                          
                          @php
                          
                            $maxYear = $curriculum->max('years');
                          @endphp
                          
                          <div class="row">
                            @for ($year = 1; $year <= $maxYear; $year++)
                              @php
                                $yearSubjects = $curriculum->where('years', $year);
                              @endphp
                          
                              @if ($yearSubjects->count())
                                <div class="col">
                                  <div class="year-header">Year {{ $year }}</div>
                                  <div class="row">
                                    @foreach ([1 => '1st Semester', 2 => '2nd Semester', 3 => 'Summer'] as $sem => $semLabel)
                                      @php
                                        $semesterSubjects = $yearSubjects->where('semester', $sem);
                                      @endphp
                          
                                      @if ($semesterSubjects->count())
                                        <div class="col">
                                          <div class="semester-label">{{ $semLabel }}</div>
                          
                                          @foreach ($semesterSubjects as $subject)
                                            @php
                                              $type = strtolower($subject->type);
                                              $color = match($type) {
                                                  'general education' => '#bbdefb',
                                                  'professional course' => '#c8e6c9',
                                                  'core course' => '#e1bee7',
                                                  default => '#f5f5f5',
                                              };
                                              $border = match($type) {
                                                  'general education' => '#1976d2',
                                                  'professional course' => '#388e3c',
                                                  'core course', 'lecture/laboratory', 'lab/lec' => '#8e24aa',
                                                  default => '#757575',
                                              };
                                            @endphp
                                            <div
                                            class="subject"
                                            id="subject-{{ $subject->courseCode }}"
                                            style="background-color: {{ $color }}; border-left: 5px solid {{ $border }};"
                                            title="{{ $subject->course }}"
                                          >
                                            <strong>{{ $subject->courseCode }}</strong><br>
                                            <small>{{ $subject->leclab }} • {{ $subject->unit }} Unit{{ $subject->unit > 1 ? 's' : '' }}</small><br>
                                          
                                            @if ($subject->grade !== null)
                                              <span style="color: #000; font-weight: bold;">Grade: {{ $subject->grade }}</span>
                                            @else
                                              <span style="color: #999;">No grade</span>
                                            @endif
                                          </div>
                                          
                                          @endforeach
                                        </div>
                                      @endif
                                    @endforeach
                                  </div>
                                </div>
                              @endif
                            @endfor
                          </div>
                          
                          <script src="https://cdn.jsdelivr.net/npm/leader-line-new/leader-line.min.js"></script>
                         <script src="https://cdn.jsdelivr.net/npm/leader-line-new/leader-line.min.js"></script>
            
            <script>
              document.addEventListener("DOMContentLoaded", () => {
                const prerequisites = @json($prereqs);
            
                const getColor = (type, inCount, outCount) => {
                  // Prioritize color override based on multiple connections
                  if ((inCount > 1 || outCount > 1)) return '#ff9800'; // Orange for multi-connect
                  switch (type?.toLowerCase()) {
                    case 'general education': return '#1976d2'; // Blue
                    case 'professional course': return '#388e3c'; // Green
                    case 'core course':
                    case 'lecture/laboratory':
                    case 'lab/lec': return '#8e24aa'; // Purple
                    default: return '#757575'; // Gray
                  }
                };
            
                // Count connections
                const inMap = {};
                const outMap = {};
                prerequisites.forEach(pr => {
                  inMap[pr.course] = (inMap[pr.course] || 0) + 1;
                  outMap[pr.prerequisite] = (outMap[pr.prerequisite] || 0) + 1;
                });
            
                prerequisites.forEach(pr => {
                  const from = document.getElementById(`subject-${pr.prerequisite}`);
                  const to = document.getElementById(`subject-${pr.course}`);
            
                  if (from && to) {
                    const type = to.getAttribute("title-type") || '';
                    const inCount = inMap[pr.course] || 0;
                    const outCount = outMap[pr.prerequisite] || 0;
            
                    new LeaderLine(
                      from,
                      to,
                      {
                        color: 'gray',
                        path: 'straight',
                        startSocket: 'right',
                        endSocket: 'left',
                        startPlug: 'disc',
                        endPlug: 'arrow1',
                        size: 2,
                        dash: { animation: true }
                      }
                    );
                  }
                });
              });
            </script>
            
                        <div class="row">
                     
                     
                        </div>
                </div>
            </div>
        </div>
    </div>
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
    
{{--     
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
</script> --}}

</x-app-layout>
