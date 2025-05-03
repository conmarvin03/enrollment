<head>
    <title>Enrollment - Programs</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        .asd {
            background-color: #f0f0f0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .bond-paper {
            width: 816px; /* 8.5 inches * 96 DPI */
            height: 1248px; /* 13 inches * 96 DPI */
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 40px;
            box-sizing: border-box;
            font-family: 'Times New Roman', serif;
        }

        table, th, td {
            border: 1px solid black !important; 
            border-collapse: collapse !important;
        }

        .content {
            width: 100%;
            height: 100%;
        }

        .sws {
            text-align: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif !important;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .bond-paper, .bond-paper * {
                visibility: visible;
            }

            .bond-paper {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>

<x-app-layout>
    <x-slot name="header">
        <a href="{{route('addgradesubmission')}}" class="btn btn-secondary  mr-2" style="float: right;">go back</a>
        <button class="btn btn-success mr-2" style="float: right;" onclick="printBondPaper()">Print Bond Paper</button>
        <button class="btn btn-warning mr-2" style="float: right;" onclick="downloadPDF()">Download as PDF</button>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Print Grade Sheet') }}
        </h2>
    </x-slot>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session("success") }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session("error") }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="asd">
                <div class="bond-paper" id="bond-paper">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="/upload/logo.jpg" width="300" style="position: relative;">
                        </div>
                        <div class="col-sm-8">
                            <h2 style="text-align:center; font-family:'Times New Roman'; font-size:130%; margin-top:1%; margin-left:-20%;">KOLEHIYO NG LUNGSOD NG DASMARIÑAS</h2>
                            <h2 style="text-align:center; font-family:'Times New Roman'; font-size:90%; margin-top:3%; margin-left:-40%;"><b>Office of the Vice President for the Academic Affairs</b></h2>
                            <h2 style="text-align:center; font-family:'Times New Roman'; font-size:90%; margin-top:1%; margin-left:-90%;"><i>The Registrar</i></h2>
                        </div>
                        <div class="col-sm-2">
                            <img src="/upload/bp.jpg" width="200" style="position: relative;">
                        </div>
                    </div>
                    <p style="float: right; font-size:14px;">KLD-01-02-F007</p><br>
                    <hr>
                    <h1 style="text-align: center; font-family: system-ui, sans-serif; font-size:20px;">GRADE SHEET</h1>
                    <h1 class="sws" style="font-size:14px;">
                        @if($settings->semester == 1)
                            1st Term
                        @elseif($settings->semester == 2)
                            2nd Term
                        @else
                            Mid-Year Term
                        @endif
                        , AY {{$settings->academicyear}} - {{$settings->year}}
                    </h1>
                    <br>

                    @foreach ($Gradesubmissions as $Gradesubmissions)
                    @endforeach   

                    <table style="width:100%">
                        <tr>
                            <th class="sws">Course Code</th>
                            <td class="sws">{{ $Gradesubmissions->coursecode }}</td>
                            <th class="sws">Section</th>
                            <td class="sws">{{ $programs->acc }}</td>
                        </tr>
                        <tr>
                            <th class="sws">Course Title</th>
                            <td class="sws">{{ ucfirst($curr->course) }}</td>
                            <th class="sws">Course</th>
                            <td class="sws">{{ $programs->acc }}</td>
                        </tr>
                        <tr>
                            <th class="sws">Unit/s</th>
                            <td class="sws">{{ $Gradesubmissions->coursecode }}</td>
                            <th class="sws">Name of Faculty</th>
                            <td class="sws">{{ $programs->acc }}</td>
                        </tr>
                    </table>

                    <hr style="border: 10px solid black !important;">

                    <table style="width:100%">
                        <tr>
                            <th class="sws">No.</th>
                            <th class="sws">Student Number</th>
                            <th class="sws">Student Name</th>
                            <th class="sws">Programs/Course</th>
                            <th class="sws">Final Grade</th>
                            <th class="sws">Remarks</th>
                        </tr>
                        @php $s = 1; @endphp
                        @foreach ($gradesStudent as $index => $gradesStudent)
                        <tr>
                            <td>{{ $s++ }}</td>
                            <td>{{ $gradesStudent->kldID }}</td>
                            <td>{{ $gradesStudent->fName.' '.$gradesStudent->mName.' '.$gradesStudent->lName }}</td>
                            <td>{{ $programs->acc }}</td>
                            <td>{{ number_format($gradesStudent->grade, 2) }}</td>
                            <td><b>{{ $gradesStudent->remark ?? '--' }}</b></td>
                        </tr>
                        @endforeach
                        <tr>
                            <th colspan="6" class="text-center"><i>**********Nothing follows**********</i></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printBondPaper() {
            window.print();
        }

      
function downloadPDF() {
    const element = document.getElementById('bond-paper');

    const opt = {
        margin:       0,
        filename:     'grade-sheet.pdf',
        image:        { type: 'jpeg', quality: 1 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'px', format: [816, 1248], orientation: 'portrait' } // match your div size
    };

    html2pdf().set(opt).from(element).save();
}


    </script>
</x-app-layout>

<!-- External Scripts -->
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
            topStart: {}
        }
    });
</script>
