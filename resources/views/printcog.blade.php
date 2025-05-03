<head>
    <title>Enrollment- COG</title>
    <link rel="icon" type="image/x-icon" href="/sss.png"> 
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        .certificate {
            position: relative;
            width: 1100px;
            height: 768px;
            background-image: url('/upload/bgcog.png'); /* Use actual server path or base64 if needed */
            background-size: cover;
            background-position: center;
            margin: auto;
            padding: 40px;
            box-sizing: border-box;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 22px;
            margin-top: 60px;
        }

        .student-info {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            font-size: 16px;
        }

        .table-container {
            margin-top: 5px;
            font-size: 15px;
        }

        
        .footer {
            margin-top: 1px;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            text-align: center;
        }
        table, th, td {
            border: 1px solid black !important; 
            border-collapse: collapse !important;
        }   .footer div {
            width: 30%;
        }

        

      
    </style>
</head>
<x-app-layout><script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <script>
        function downloadPDF() {
            const element = document.querySelector('.certificate');
            const opt = {
                margin:       0,
                filename:     'certificate_of_grades.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'pt', format: 'a4', orientation: 'landscape' }
            };
            html2pdf().set(opt).from(element).save();
        }
    
      
    </script>
    
    <x-slot name="header">
      
        <a href="{{route('show.grades')}}" style="float: right;" class="btn btn-primary  ml-2">go back</a>
        <button onclick="downloadPDF()" class="btn btn-success" style="float: right;">Download PDF</button>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
     

        
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                
<div class="certificate">
    <div class="title">Certificate of Grades</div>

    <div class="student-info">
        <div>
            <strong>Name:</strong> {{$user->fName}} {{$user->mName}} {{$user->lName}}<br>
            <strong>Section:</strong> {{$user->ay}}-000{{$user->section}}
        </div>
        <div>
            <strong>Student Number:</strong> {{$user->kldID}}<br>
            <strong>Semester/Academic Year:</strong> 
            <?php if($settings->semester==1){?> First Term
                <?php }else if($settings->semester==2){ ?>
                    Second Term
            <?php }else{?>
                Mid-Year Term
                <?php } ?>
          , {{$settings->academicyear}} - {{$settings->year}}
        </div>
    </div>

    <div class="table-container">
        <table style="width:100%">
            <thead>
                <tr style="height: 5px; line-height: 1; font-size: 14px; padding: 0;">
                    <th>Course Code</th>
                    <th>Description</th>
                    <th>Final Grade</th>
                    <th>Unit/s</th>
                    <th>Professor's Name</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($grades as $grades)
            <tr style="height: 5px; line-height: 1; font-size: 14px; padding: 0;">
                <td class="text-left" >{{ $grades->subject }}</td>
                <td class="text-left" width="450" >{{ $grades->course }}</td>
                <td class="text-left">{{ number_format($grades->grade, 2) }}</td>
                <td class="text-left">{{ $grades->remark }}</td>
                <td class="text-left" width="150">{{ $grades->teacher_name }}</td>
            </tr>
        @endforeach
            </tbody>
        </table>
    </div>
    <br><br>

    <div class="row">
        <div class="col">
           
            <div class="name-bold" style="height: 5px; line-height: 1; font-size: 14px; padding: 0;"> Prepared: <br><br><u>Jannel M. Alano, RPm</u><p><b>Registration Officer</b></div>
            
        </div>
        <div  class="col">
            <div class="name-bold"  style="height: 5px; line-height: 1; font-size: 14px; padding: 0;">Checked: <br><br> <u>Leo Guisseppe N. Dinglasan</u><br>  <p><b>Registrar III</b></div>
            
        </div>
        <div class="col">
            <div   style="height: 5px; line-height: 1; font-size: 14px; padding: 0;">Attested:<br><br> <u>Ma. Victoria C. Balubio, MAEd, MBA, LPT</u><br>  <p><b>College Registrar</b></p></div>
          
        </div>
   
</div>
            </div>
        </div>
    </div>
</x-app-layout>
