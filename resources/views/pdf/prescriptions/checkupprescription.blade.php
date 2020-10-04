<html>
<head>
    <title>checkup | prescription</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>


        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 5px;
            font-family: 'kalpurush', sans-serif;
            font-size: 14px;
        }

        .bordertable td, th {
            /*border: 0 phonpx solid #A8A8A8;*/
            border-spacing: 0;
        }

        .horizontal-line {
            display: block;
            height: 1px;
            border: 0;
            border-top: 1px solid #ccc;
            margin: 1em 0;
            padding: 0;
        }

        @page {
            header: page-header;
            footer: page-footer;
            background: url({{ public_path('images/ekhonidaktar_logo_english_background.png') }});
            background-position: right top;
        }
    </style>
</head>
<body style="padding-top: 100px">

{{--<p align="center" style="line-height: 1.2;">--}}
{{--    --}}{{--    <img src="{{ public_path() . '/images/ekhonidaktar_logo_bangla.png' }}" style="height: 60px; width: auto;">--}}

{{--    --}}{{--    <br/>--}}
{{--    --}}{{--    {{ $doctor->name }}<br/>--}}
{{--    --}}{{--    <small>{{ $doctor->email }}, {{ $doctor->workplace }}</small>--}}
{{--    --}}{{--    <br/>--}}
{{--    --}}{{--    <br/>--}}
{{--    <span align="center" style="color: #397736; border-bottom: 1px solid #397736;">--}}
{{--      Checkup Prescription--}}
{{--    </span>--}}
{{--</p>--}}

<br><br><br><br><br>
<hr class="horizontal-line">
<br>
<table>
    <tr>
        <td width="60%" align="left">
            <table class="bordertable" style="margin-top: 30px">
                <thead>
                <tr>
                    {{-- <th width="9%">ক্রয় আইডি</th> --}}
                    <th align="left" style="font-size: 20px">{{$doctor->name}}</th>
                    {{--                    <th>Dosage</th>--}}
                    {{--                    <th>Duration</th>--}}
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$doctor->doctortype->name}}</td>
                </tr>
                <tr>
                    <td>BMDC ID: {{$doctor->bmdc_number}}</td>
                </tr>
                <tr>
                    <td>{{$doctor->postgrad}}</td>
                </tr>
                <tr>
                    {{-- <th width="9%">ক্রয় আইডি</th> --}}
                    <td>{{$doctor->workplace}}</td>
                    {{--                    <th>Dosage</th>--}}
                    {{--                    <th>Duration</th>--}}
                </tr>
                <tr>
                    <td>{{$doctor->email}}</td>
                </tr>

                </tbody>
            </table>

        </td>
        <td align="right" valign="bottom">
            <table class="bordertable" style="margin-top: 30px">
                <tbody>
                <tr>
                    <td width="80%"><span><b>Date: </b> {{ date('d F, Y', strtotime($checkup->start_time)) }}</span>
                    </td>
                </tr>
                <tr align="right">
                    <td>
                        <span><b>Name: </b>{{$patient->name}}</span>
                    </td>
                </tr>
                <tr align="right">
                    <td>
                        <span><b>Gender: </b>@if($patient->gender == 0) Male @else Female @endif</span>
                    </td>
                </tr>
                <tr align="right">
                    <td>
                        <span><b>Age: </b>21</span>
                    </td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
</table>

<br><br><br>
<table>
    <tr>
        <td width="35%" align="left" valign="top">
            <table class="bordertable">
                <thead>
                <tr align="left">
                    <th  style="border: 2px solid black; border-left: 0; border-right: 0">Problem Description</th>
                </tr>
                <tr>
                    <td >
                        <span> {{$prescription['disease_description']}}</span>

                    </td>
                </tr>
                <tr>
                    {{-- <th width="9%">ক্রয় আইডি</th> --}}
                    {{--                    <th align="left" width="20%">Date</th>--}}

                </tr>
                </thead>
                <tbody>

                <table class="bordertable" style="margin-top: 30px">
                    <thead>
                    <tr style="border: 2px solid black; border-right: 0; border-left: 0">
                        {{-- <th width="9%">ক্রয় আইডি</th> --}}
                        <th align="left">Test Name</th>
                        {{--                    <th>Dosage</th>--}}
                        {{--                    <th>Duration</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($prescription['test_descriptions'] as $testDescription)
                        <tr>
                            {{-- <th width="9%">ক্রয় আইডি</th> --}}
                            <td>{{ $testDescription['name'] }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>


                </tbody>
            </table>

        </td>
        <td align="left" valign="top">
            <table class="bordertable">
                <thead>
                <tr style="border: 2px solid black; border-right: 0; border-left: 0">
                    {{-- <th width="9%">ক্রয় আইডি</th> --}}
                    <th align="left" width="40%">Medicine Name</th>
                    <th align="left" width="40%">Dosage</th>
                    <th align="left">Duration</th>
                </tr>
                </thead>
                <tbody>
                @foreach($prescription['medicine_descriptions'] as $medicineDescription)
                    <tr>
                        {{-- <th width="9%">ক্রয় আইডি</th> --}}
                        <td>{{ $medicineDescription['name'] }}</td>
                        <td>{{ join(", ", $medicineDescription['tags']) }}</td>
                        <td>{{ $medicineDescription['duration'] }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <br>
            <span><b>Special Note: </b> {{$prescription['special_note']}}</span>

        </td>
    </tr>
</table>


<htmlpageheader name="page-header">
    <table>
        <tr>
            <td width="50%">
                <small style="font-size: 12px; color: #525659;">creation time: <span
                        style="font-family: Calibri; font-size: 12px;">{{ date('F d, Y, h:i A') }}</span></small>
            </td>
            {{--            <td align="right" style="color: #525659;">--}}
            {{--                | page: {PAGENO}/{nbpg}--}}
            {{--                </small>--}}
            {{--            </td>--}}
        </tr>
    </table>
</htmlpageheader>


<htmlpagefooter name="page-footer">
    {{--    <div class="storeWaterMark" style="opacity: 0.1;">--}}
    {{--        <big>Ekhoni Daktar</big>--}}
    {{--    </div>--}}
    <hr class="horizontal-line" style="width: 90%; height: 3px">
    <br/>
    <div style="padding-left: 30px; padding-right: 30px">
        <table>
            <tr align="left">
                <td>
                    <img src="{{public_path('images/ekhonidaktar_icon.png')}}"
                         style="height: 35px; width: auto; border-radius: 25px">
                    <img src="{{public_path('images/ekhonidaktar_text _bangla.png')}}"
                         style="height: 35px; width: auto; border-radius: 25px">
                </td>
            </tr>
            <tr>
                <td width="70%" align="left">
                    <span style="font-size: 11px; color: #525659;">Developed by: Innova Tech</span>
                </td>
                <td align="right">
                <span style="font-family: Calibri,serif; font-size: 11px; color: #3f51b5;">Powered by:
                    http://ekhonidaktar.com</span>
                </td>
            </tr>
        </table>

    </div>
    <hr class="horizontal-line">

</htmlpagefooter>
</body>
</html>
