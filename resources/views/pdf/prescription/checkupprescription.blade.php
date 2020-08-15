<html>
<head>
    <title>checkup | prescription</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /*body {*/
        /*    font-family: 'kalpurush', sans-serif;*/
        /*}*/

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 5px;
            /*font-family: 'kalpurush', sans-serif;*/
            font-size: 14px;
        }

        .bordertable td, th {
            border: 1px solid #A8A8A8;
        }

        .storeWaterMark {
            text-align: center;
            font-size: 30px;
            color: #b8cee3;
            opacity: 0.1 !important;
        }

        @page {
            header: page-header;
            footer: page-footer;
            {{--            background: url({{ public_path('images/ekhonidaktar_logo_bangla.png') }});--}}
 background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
    </style>
</head>
<body>
<p align="center" style="line-height: 1.2;">
    <img src="{{ public_path() . '/images/ekhonidaktar_logo_bangla.png' }}" style="height: 60px; width: auto;">

    <br/>
    {{ $doctor->name }}<br/>
    <small>{{ $doctor->email }}, {{ $doctor->workplace }}</small>
    <br/>
    <br/>
    <span align="center" style="color: #397736; border-bottom: 1px solid #397736;">
      Checkup Prescription
    </span>
</p>

<div class="row">
    <div class="col-4">
        Patient Description
    </div>
    <div class="col-8">
        <table class="bordertable">
            <thead>
            <tr>
                {{-- <th width="9%">ক্রয় আইডি</th> --}}
                <th>Medicine Name</th>
                <th>Dosage</th>
                <th>Duration</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<htmlpageheader name="page-header">
    <table>
        <tr>
            <td width="50%">
                <small style="font-size: 12px; color: #525659;">presciption create time: <span
                        style="font-family: Calibri; font-size: 12px;">{{ date('F d, Y, h:i A') }}</span></small>
            </td>
            <td align="right" style="color: #525659;">
                | page: {PAGENO}/{nbpg}
                </small>
            </td>
        </tr>
    </table>
</htmlpageheader>


<htmlpagefooter name="page-footer">
    <div class="storeWaterMark" style="opacity: 0.1;">
        <big>Ekhoni Daktar</big>
    </div>
    <br/>
    <table>
        <tr>
            <td width="70%" align="left">
                <span style="font-size: 11px; color: #525659;">Developed by: Innova Tech</span>
            </td>
            <td align="right">
                <span style="font-family: Calibri; font-size: 11px; color: #3f51b5;">Powered by:
                    http://ekhonidaktar.com</span>
            </td>
        </tr>
    </table>
</htmlpagefooter>
</body>
</html>
