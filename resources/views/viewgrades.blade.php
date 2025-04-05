<head>
    <title>Enrollment- Programs</title>
  </head>
<x-app-layout>
    <x-slot name="header">
        @if(session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Import Failed',
                    html: `{!! nl2br(session("error")) !!}`,
                });
            });
        </script>
        @endif
        
        @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session("success") }}',
                });
            });
        </script>
        @endif
        
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
       
        <div class="card card-success">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"> <i class="fa-solid fa-list"></i>  &nbsp;&nbsp;Grades</h3>
                    
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
                  ->where('p.pID', '=', $program->id)
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
                                  <small>{{ $subject->leclab }} • {{ $subject->unit }} Unit{{ $subject->unit > 1 ? 's' : '' }}</small>
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
      const from = document.getElementById(`subject-${pr.course}`);
      const to = document.getElementById(`subject-${pr.prerequisite}`);

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
                        </div>
             
                          </div>

                      </div>
    
                </div>

            </div>
        </div>
    </div>
</div>     </div>
</div>
</x-app-layout>

      

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script>
   new DataTable('#example', {
layout: {
    topStart: {
     
    }
}
});
</script>

