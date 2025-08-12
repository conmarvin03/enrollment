<head>
    <title>Enrollment- Programs</title>
  </head>

<x-app-layout>
    <x-slot name="header">
        @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            });
        </script>
    @endif
    <div class="py-12">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
            {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/gojs/2.3.15/go.js"></script>

            <style>
                #myDiagramDiv {
                    width: 100%;
                    height: 600px;
                    border: 1px solid black;
                }
            </style> --}}
         
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

              
              {{-- Optional: Prerequisite Map Debug --}}
              {{-- 
              <h4>Prerequisite Map</h4>
              <ul>
                @foreach ($pp as $row)
                  <li><strong>{{ $row->prerequisite }}</strong> → {{ $row->course }}</li>
                @endforeach
              </ul> 
              --}}
        
                <div class="card card-success">
                    <div class="card card-success">
                        <div class="card-header">
                            <a href="{{route('programs')}}" style="float: right;" class="btn btn-outline-primary">go back</a>
                        <h3 class="card-title"> <i class="fa-solid fa-user"></i>  &nbsp;&nbsp;{{$program->acc.'- '.$program->program}}</h3>
                        </div>
             
                          </div>
                         
                          <div class="row">
                           
                            <div class="col-6"> <form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                               <button type="submit" class="btn btn-dark" style="float: right;">Import Excel</button>  <h2 class="lead p-3">Import Excel</h2> <hr class=" w-100">
                          
                            <input type="file"  class="p-3"  class="form-control" name="file" required><br>
                          
                            <input type="text" name="id" value="{{$program->id}}" style="display: none;" >
                                           </form> 
                        </div>
                          </div>
                          <div class="row container-fluid">
                    <div class="col-5">
                        <p class="h5">Program Information</p>
                        <hr>
                        <p><b>Number of Courses: </b>{{$noofcourses}}</p>
                        <p><b>Number of Lecture Courses: </b>{{$countlecture}}</p>
                        <p><b>Number of Laboratory Courses: </b>{{$countlaboratory}}</p>
                        <p><b>Total Units:</b>{{$sumUnits}}</p>
                        <form action="{{route('updateprogram', ['program'=>$program])}}" method="post">
                            @csrf
                                @method('put')
                                  <div class="row">
                                    <div class="col">
                                    <input type="text" class="form-control w-100 border-secondary mt-2" name="acc" required value="{{$program->acc}}" placeholder=" Course Code">
                                    </div>
                                    <div class="col">
                                    <button type="submit" class="btn btn-outline-success w-100 mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Update</button>       
                                    </div>
                                  </div>
                                    <input type="text" class="form-control w-100 border-secondary mt-2" name="program" required value="{{$program->program}}" placeholder="Enter Course Title">
                            
                        </form>
               
                        <div class="p-3"> <p class="h5">Add Prerequisite</p>
                            <hr>
                           
                            <div class="row">
                                <div class="col">
                            <form action="{{route('addprereq')}}" method="post">
                                @csrf
                                    @method('post')
                                    <select name="coursecode" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                        <option value="">Select Course Code</option>
                                        @foreach ($curriculum as $asd)
                                       
                                            <option  value="{{$asd->id}}">{{$asd->courseCode.'-'.$asd->course}}</option>
                                                @endforeach   
                                    </select>
                                    </div>
                                    <div class="col">
                                    <select name="prereq" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                        <option value="">Select Prerequisite Code</option>
                                        @foreach ($curriculum as $asd)
                                       
                                            <option  value="{{$asd->id}}">{{$asd->courseCode.'-'.$asd->course}}</option>
                                                @endforeach   
                                    </select>
                                
                                    <input type="text" value="{{$program->id}}" name="zxc" style="display: none;">
                                    </div><div class="col">
                                        <button type="submit" class="btn btn-outline-success  mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Add Prerequisite</button>
                                   
                                    </div></div>
                            </form>
                            
                    </div>
                    </div>
                    <div class="col-7">
                    
                        <p class="h5">Add Course</p>
                        <hr>
                        
                        <form action="{{route('addcurriculums')}}" method="post">
                            @csrf
                                @method('post')
                            <div class="card-body">
                                <p class=" pt-2"> </p>
                                
                            <div class="form-group">
                                <div class="row">
                                <div class="col-4">
                            <input type="text" class="form-control w-100 border-secondary mt-2" name="cc" required placeholder="Enter Course Code">
                                </div>
                                <div class="col-8">
                            <input type="text" class="form-control w-100 border-secondary mt-2" name="c" required placeholder="Enter Course Title">
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                            <select name="type" required class="form-control w-100 border-secondary mt-2">
                                <option value="">Select Course Type</option>
                                <option >General Education</option>
                                <option>Core Course</option>
                                <option>Professional Course</option>
                                <option>Electives</option>
                                <option>Thesis</option>
                                <option>Practicum</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="num" class="form-control w-100  border-secondary mt-2" name="unit" required placeholder="Enter Course Unit/s">
                         
                            
                        </div></div>
                        <div class="row">
                            <div class="col-6">
                                <select name="years" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                    <option value="">Select Year</option>
                                    <option value="1">First year</option>
                                    <option value="2">Second Year</option>
                                    <option value="3">Third Year</option>
                                    <option value="4">Fourth Year</option>
                                    <option value="5">Fifth Year</option>
                                </select>   
                           
                            </div>
                            <div class="col-6">
                                <select name="semester" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                    <option value="">Select Semester</option>
                                    <option value="1">First</option>
                                    <option value="2">Second</option>
                                    <option value="3">Summer</option>
    
                                </select>
                            
                            </div></div>
                            <div class="row">
                                <div class="col-8">
                            <select name="leclab" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                <option value="">Lecture or Laboratory</option>
                                <option value="Lecture">Lecture</option>
                                <option value="Laboratory">Laboratory</option>

                            </select>
                                </div>
                                <div class="col-4">
                            <input type="text" value="{{$program->id}}" name="pID"  style="display:none;">
                            <button type="submit" class="btn btn-outline-success mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Add Course</button>
                            <br>
                            
                                </div></div>
                            </form>
                        </div>
                  
                    
                    </div>
                  </div>
           
                
             
                  </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg pt-2">
                <div class="row">
                    <div class="col">
                <div class="card card-success">
                    <div class="card-header">
                        
                    <h3 class="card-title"> <i class="fa-solid fa-list"></i>  &nbsp;&nbsp;Courses</h3>
                    </div>
         
                      </div>
                      <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                          <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Courses</button>
                          <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Prerequisite</button>
                        </div>
                      </nav>
                      <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div>
                            <table  id="example"  class="table-responsive text-center display table table-striped table-hover table-bordered border-success" >
                                <thead class="text-center">
                                 <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center" width="5">Course Code</th>
                                    <th class="text-center">Course</th>
                                    <th class="text-center">Lec/Lab</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Unit/s</th>
                                    <th class="text-center">Year</th>
                                    <th class="text-center">Semester</th>
                                    <th class="text-center">Action</th>
                                 </tr>
                                </thead>
                                <tbody>
                                    @foreach ($curriculum as $curriculum)
                                    <tr>
                                        <td class="text-center" >{{$curriculum->id}}</td>
                                        <td class="text-center" >{{$curriculum->courseCode}}</td>
                                        <td class="text-center" >{{$curriculum->course}}</td>
                                        <td class="text-center" >{{$curriculum->leclab}}</td>
                                        <td class="text-center" >{{$curriculum->type}}</td>
                                        <td class="text-center" >{{$curriculum->unit}}</td>
                                        <td class="text-center" >{{$curriculum->years}}</td>
                                        <td class="text-center" >{{$curriculum->semester}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col">
                                            <a class="navbar-brand text-light mt-2 btn btn-success" href="{{route('course.edit',['curriculum'=> $curriculum])}}"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                                </div>
                                                
                                                <div class="col">
                                                    <form action="{{route('updateStatus',['curriculum'=> $curriculum])}}" method="post">
                                          
                                                @csrf
                                                    @method('put')
                                                    <button type="submit" class="btn btn-danger mt-2" style="float: right;"><i class="fa-solid fa-trash"></i> Remove</button>
                                             
                                                    <input type="text" value="{{$curriculum->id}}" name="id" style="display:none;">
                                                </form>
                                                </div></div>  
                                        </td>
                    
                                    </tr>
                                 
                                                    
                                  
                                        @endforeach     
                                </tbody>
                    
                            </table>  
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">  <table  id="example"  class="table-responsive text-center display table table-striped table-hover table-bordered border-success w-100" >
                            <thead class="text-center">
                             <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Course Code</th>
                                <th class="text-center">Action</th>
                         
                             </tr>
                            </thead>
                            <tbody>
                                @foreach ($pp as $pp)
                                <tr>
                                    <td class="text-left" >{{$pp->course.' - '.$pp->course1}}</td>
                                    <td class="text-left" >{{$pp->prerequisite.' - '.$pp->prerequisite1}}</td>
                                    <td></td>       
                                </tr>
                             
                                                
                              
                                    @endforeach     
                            </tbody>
                
                        </table>  </div>
                      </div>
                  
                   
                </div>

            </div>
        </div>
    </div>
</div>     </div>
</div>





{{-- <h2>{{ $program->program }} Curriculum Flowchart</h2> --}}

{{-- <!-- Flowchart Container -->
<div id="myDiagramDiv"></div><script>
    var $ = go.GraphObject.make;

    var myDiagram = $(go.Diagram, "myDiagramDiv", {
        initialAutoScale: go.Diagram.Uniform,
        layout: $(go.GridLayout, { 
            wrappingColumn: 6,  // Ensures semesters are aligned
            cellSize: new go.Size(1, 1),
            spacing: new go.Size(100, 50),
        }),
        allowMove: false,
        allowCopy: false,
        allowDelete: false,
        allowSelect: false,
        allowTextEdit: false,
        isReadOnly: true,
        allowZoom: true,
        "toolManager.mouseWheelBehavior": go.ToolManager.WheelZoom,
    });

    // Define Subject Colors Based on Type
    function getColor(type) {
        switch (type) {
            case 'General Education': return 'lightblue';
            case 'Professional Courses': return 'lightgreen';
            case 'Core Courses': return 'tomato';
            default: return 'gray';
        }
    }

    // Define Node Template (Subjects inside table cells)
    myDiagram.nodeTemplate =
        $(go.Node, "Auto",
            { locationSpot: go.Spot.Center },
            $(go.Shape, "RoundedRectangle",
                { strokeWidth: 1, portId: "", fromLinkable: true, toLinkable: true },
                new go.Binding("fill", "color")
            ),
            $(go.TextBlock, { margin: 8, font: "bold 12px Arial", textAlign: "center" },
                new go.Binding("text", "courseCode"))
        );

    // Define Link Template (Prerequisite Arrows)
    myDiagram.linkTemplate =
        $(go.Link,
            { routing: go.Link.Orthogonal, corner: 5 },
            $(go.Shape, { strokeWidth: 2 }), 
            $(go.Shape, { toArrow: "Standard" })
        );

    // Dummy Data (Replace with Laravel JSON)
    var nodeDataArray = {!! json_encode($nodeDataArray) !!};
    var linkDataArray = {!! json_encode($linkDataArray) !!};


    // Assign Colors and Proper Row Positions
    nodeDataArray.forEach((node) => {
        node.color = getColor(node.type);
        node.row = (node.year - 1) * 2 + (node.semester - 1);
    });

    // Generate Table Headers for Year & Semester
    var tableHeaders = [];
    for (var y = 1; y <= 4; y++) {
        for (var s = 1; s <= 2; s++) {
            tableHeaders.push({
                key: "header-" + y + "-" + s,
                courseCode: `Year ${y} - Semester ${s}`,
                color: "gray",
                isHeader: true,
                row: (y - 1) * 2 + (s - 1)
            });
        }
    }

    // Merge Headers with Subject Data
    nodeDataArray = [...tableHeaders, ...nodeDataArray];

    // Assign Grid Layout Wrapping (One Row per Semester)
    myDiagram.layout.wrappingColumn = 6; // Adjust column spacing
    myDiagram.layout.arrangement = go.GridLayout.Position; 

    // Assign Data to Diagram
    myDiagram.model = new go.GraphLinksModel(nodeDataArray, linkDataArray);
</script> --}}
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
    pageLength: 15,

layout: {
    topStart: {
     
    }
}
});
</script>

