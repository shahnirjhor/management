<!DOCTYPE html>
<html lang="en">
<head>
  <title>Scholarship-{{$scholarship->studentDetail->full_name}}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        *{
            color: black;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        border-top: none;
        /* padding: 5px; */
    }
  </style>
</head>
<body>

    <div class="container">
        <div class="row mt-1">
              <div class="col-sm-12">
                  <div class="text-center">
                      <img src="{{public_path('pdf/img/logo.png')}}" class="img-fluid" alt="...">
                  </div>
                  <p class="text-center" style="font-size: 17px;"><u><strong>The Rotary Bangalore Midtown in association with Sansera Foundation invites application
                      from Students who have secured marks in excess of 60% in 10th standard exam last held.</strong></u></p>
              </div>
          </div>

          <div class="mt-2">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Application No. : {{$scholarship->application_no}}</td>
                        <td>Year : {{$scholarship->year}}</td>
                        <td>Annual Income: {{$scholarship->annual_income}}</td>
                    </tr>
                </tbody>
            </table>

          </div>

          <div class="row mt-5">
              <div class="col-sm-12">
                  <h5><strong><u>PERSONAL DETAILS:</u><strong></h5>
              </div>
              <table class="table borderless" >
                  <tr>
                      <td><span style="margin-left: 18px;">1. Full Name</span></td>
                      <td> : </td>
                      <td>{{$scholarship->studentDetail->full_name}}</td>
                      <td rowspan="5" colspan="4">

                          <div style="float: right; border: 1px solid black; width: 160px;
                          height: 180px">
                          <br>
                          <br>
                          <br>
                              <p style="text-align: center">Latest passport <br> size photo</p>
                          </div>
                      </td>
                  </tr>
                  <tr>
                      <td><span style="margin-left: 18px;">2. Father Name</span> </td>
                      <td>: </td>
                      <td>{{$scholarship->studentDetail->father_name}}</td>
                  </tr>
                  <tr>
                      <td><span style="margin-left: 30px;">Occupation</span> </td>
                      <td> : </td>
                      <td>{{$scholarship->studentDetail->father_occupation}}</td>
                  </tr>
                  <tr>
                      <td> <span style="margin-left: 18px;">3. Mother Name</span></td>
                      <td> : </td>
                      <td>{{$scholarship->studentDetail->mother_name}}</td>
                  </tr>
                  <tr>
                      <td><span style="margin-left: 30px;">Occupation</span></td>
                      <td> : </td>
                      <td> {{$scholarship->studentDetail->mother_occupation}}</td>
                  </tr>
                  <tr>
                      <td colspan="4"><span style="margin-left: 18px;">4. Full Address</span>  </td>

                  </tr>
                  <tr>
                      <td> <span style="margin-left: 30px;">House no.</span> </td>
                      <td>:</td>
                      <td>{{$scholarship->studentDetail->house_no}}</td>
                      <td>Street/ Cross </td>
                      <td>:</td>
                      <td>{{$scholarship->studentDetail->state}}</td>
                  </tr>
                  <tr>
                      <td> <<span style="margin-left: 30px;">Village</span></td>
                      <td> : </td>
                      <td>{{$scholarship->studentDetail->scholarshipVillage->name}}</td>
                      <td>Post office :</td>
                      <td> : </td>
                      <td>{{$scholarship->studentDetail->post_office}}</td>
                  </tr>
                  <tr>
                      <td><span style="margin-left: 30px;">Taluk</span></td>
                      <td> : </td>
                      <td>{{$scholarship->studentDetail->taluk}}</td>
                      <td>District :</td>
                      <td> : </td>
                      <td>{{$scholarship->studentDetail->district}}</td>
                  </tr>
                  <tr>
                      <td><span style="margin-left: 30px;">Pin code</span></td>
                      <td> : </td>
                      <td>{{$scholarship->studentDetail->pincode}}</td>
                      <td>State </td>
                      <td> : </td>
                      <td>{{$scholarship->studentDetail->state}}</td>
                  </tr>
                  <tr>
                      <td><span style="margin-left: 18px;">5. Contact no. 1 :</span></td>
                      <td>:</td>
                      <td>{{$scholarship->studentDetail->contact_no_1}}</td>
                      <td>Contact no : </td>
                      <td>:</td>
                      <td>{{$scholarship->studentDetail->contact_no_2}}</td>
                  </tr>

                  <tr>
                      <td><span style="margin-left: 18px;">6. Date of Birth </span></td>
                      <td>:</td>
                      <td>{{$scholarship->studentDetail->date_of_birth}}</td>
                      <td>Age </td>
                      <td>:</td>
                      <td>{{$scholarship->studentDetail->age}}</td>
                  </tr>
                  <tr>
                      <td><span style="margin-left: 18px;">7. Male/ Female</span> </td>
                      <td>:</td>
                      <td colspan="4">{{$scholarship->studentDetail->gender}}</td>
                  </tr>
                  <tr>
                      <td> <span style="margin-left: 18px;">8. Aadhar no. </span> </td>
                      <td>:</td>
                      <td colspan="4">{{$scholarship->studentDetail->aadhar_no}}</td>
                  </tr>
              </table>

          </div>

          <div class="row mt-5">
              <div class="col-sm-12">
                <h5><strong><u>DETAILS OF STUDIED SCHOOL / COLLEGE :</u><strong></h5>
              </div>
              @php
                  $scholl_college_data = $scholarship->school_or_college == 1 ? $scholarship->schoolDetail : $scholarship->collegeDetail;
              @endphp
              <div class="col-sm-12">
                  <table class="table table-borderless p-0 m-0">
                      <tbody>
                          @if ($scholarship->school_or_college == 1)
                          <tr>
                              <td colspan="4">Govt. / Govt. Aided / Private</td>
                              <td>:</td>
                              <td colspan="4">{{$scholl_college_data->school_type}}</td>
                          </tr>
                          @else
                          <tr>
                              <td>Govt. / Govt. Aided / Private</td>
                              <td>:</td>
                              <td colspan="7">{{$scholl_college_data->college_type}}</td>
                          </tr>
                          @endif

                          <tr>
                              <td> Village </td>
                              <td>: </td>
                              <td>{{$scholl_college_data->scholarshipVillage->name}}</td>

                              <td> Taluk </td>
                              <td>: </td>
                              <td>{{$scholarship->studentDetail->taluk}}</td>

                              <td> District </td>
                              <td>: </td>
                              <td>{{$scholl_college_data->district}}</td>
                          </tr>
                          <tr>
                              <td colspan="4">Marks Obtained in last Examination  SSLC/PUC/Degree</td>
                              <td> : </td>
                              <td>{{$scholarship->marks_obtained}}</td>

                              <td>Year</td>
                              <td> : </td>
                              <td>{{$scholarship->year}}</td>
                          </tr>
                          <tr>
                              <td colspan="4">If PUC / Degree please specify the subject </td>
                              <td>:</td>
                              <td colspan="4">{{$scholarship->marks_obtained_type}}</td>
                          </tr>
                          <tr>
                              <td>Grade/ Class</td>
                              <td>:</td>
                              <td colspan="2">{{$scholarship->classDetail->name}}</td>

                              <td colspan="2">Percentage</td>
                              <td>:</td>
                              <td colspan="2">{{$scholarship->percentage_marks_obtained}}</td>
                          </tr>

                          <tr>
                              <td>Contact Person:</td>
                              <td>:</td>
                              <td colspan="2">{{$scholarship->school_contact_person}}</td>
                              <td colspan="2">Designation</td>
                              <td >:</td>
                              <td colspan="2">{{$scholarship->school_designation}}</td>
                          </tr>

                          <tr>
                              <td>Contact no.</td>
                              <td>:</td>
                              <td colspan="2">{{$scholarship->school_contact_number}}</td>
                              <td colspan="2">Seal & Signature</td>
                              <td>:</td>
                              <td colspan="2"></td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>


          <div class="row mt-5">
              <div class="col-sm-12">
                <h5><strong><u>FURTHER EDUCATION DETAILS :</u><strong></h5>
              </div>
              <div class="col-sm-12">
                 <p> Course Joined : {{$scholarship->further_education_details_course_joined}}</p>
              </div>

              <div class="col-sm-12">
                @if($scholarship->further_education_details_school_or_college == "1")
                <p> School /Institute : {{$scholarship->furtherEducationschollDetail->name}}</p>
                @else
                <p> College /Institute : {{$scholarship->furtherEducationCollegeDetail->name}}</p>
                @endif
              </div>
          </div>

          <div class="row mt-5">
              <div class="col-sm-12">
                <h5><strong><u>DECLARATION OF STUDENT</u><strong></h5>
              </div>
              <div class="col-sm-12">
                 <p> 1. I certified that the information given in above istrue and correct.</p>
                 <p> 2. I am not availing any otherscholarship forthis purpose from any NGO/State/Central Govt.</p>
                 <p>3. If the information given by me isfound to be false/incorrect, the scholarship sanction to me may be
                  cancelled and the amount of scholarship refunded by me</p>
              </div>
              <table class="table">
                <tbody>
                    <tr>
                        <td>Date : </td>
                        <td>Student Signature</td>
                        <td>Parents/ Guardian Signature </td>
                    </tr>
                    <tr>
                        <td>Place : </td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>
          </div>

          <div class="row mt-5">
              <div class="col-sm-12">
                <h5><strong><u>DOCUMENTS TO BE ATTACH :</u><strong></h5>
              </div>
              <div class="col-sm-12">
                 <p> 1. Income certificate of Parents/ Guardian </p>
                 <p> 2. Any Govt. ID proof (Aadhar, Ration card etc.) </p>
                 <p> 3. Previous educational marks card </p>
                 <p> 4. Original fee receipt </p>
                 <p> 5. Bank passbook copy </p>
              </div>
          </div>

          <div class="row mt-5">
              <div class="col-sm-12">
                <h5 class="text-center"><strong><u>FOR OFFICE USE :</u><strong></h5>
              </div>
              <div class="col-sm-12">
                 <p> 1. Applicant Selected / Not selected : </p>
                 <p> 2. If Not selected : </p>
                 <p> 3. Sanctioned amount : </p>

                 <p>I, {{$teacherName}}, being first duly sworn, hereby state that: I am the principa of {{$scholl_college_data->name}}, located at
                  {{$scholl_college_data->scholarshipVillage->name}}, {{$scholl_college_data->district}}. {{$scholarship->studentDetail->full_name}}, date of birth {{date('d-m-Y', strtotime($scholarship->studentDetail->date_of_birth))}}, is a student at {{$scholl_college_data->name}}
                  and is enrolled in the {{$scholarship->classDetail->name}} grade. This affidavit is being executed for the purpose of verifying {{$scholarship->studentDetail->full_name}} complete information provided above in the portal is true and attendance for the purpose of applying
                  for a scholarship.
                  <p class="mt-5">I declare under penalty of perjury that the foregoing is true and correct.</p>
                </p>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Date:</td>
                            <td>Signature</td>
                            <td>Seal</td>
                        </tr>
                    </tbody>
                </table>
              </div>
          </div>

          <div class="row my-5">
              <div class="col-sm-12">
                  <h6><strong style="color:blue; font-style: italic;">SANSERA FOUNDATION</strong></h6>
                  <p>No 143/A, Jirani link Road Near OMEX Circle Bengaluru 560099,Mobil No: 9845620096</p>
                 {{--  <img src="{{public_path('pdf//img/qr.png')}}" alt=""> --}}
              </div>
          </div>

      </div>

</body>
</html>
