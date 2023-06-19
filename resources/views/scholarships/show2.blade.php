@extends('layouts.layout')
@section('content')
<div class="container">
  <div class="row mt-5">
        <div class="col-sm-12">
            <div class="text-center">
                <img src="{{asset('pdf/img/logo.png')}}" class="img-fluid" alt="...">
            </div>
            <p class="font-weight-bold text-justify text-center"><u>The Rotary Bangalore Midtown in association with Sansera Foundation invites application
                from Students who have secured marks in excess of 60% in 10th standard exam last held.</u></p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-sm-4">
            <div>
                <span>Application No. : {{$scholarship->application_no}}</span>
            </div>
        </div>

        <div class="col-sm-4">
            <div>
                <span>Year : {{$scholarship->year}}</span>
            </div>
        </div>

        <div class="col-sm-4">
            <div>
                <span> Annual Income: {{$scholarship->annual_income}}</span>
            </div>
        </div>
        
    </div>

    <div class="row mt-5">
        <div class="col-sm-12">
            <h6 class="font-weight-bold">PERSONAL DETAILS:</h6>
        </div>
        <table class="table">
            <tr>
                <td>1. Full Name : {{$scholarship->studentDetail->full_name}}</td>
                <td rowspan="5">
                    <div class="text-right">
                        <img width="200" height="200" src="{{asset('img/profile/male.png')}}" class="rounded" alt="prfile">
                    </div>
                </td>
            </tr>
            <tr>
                <td>2. Father Name : {{$scholarship->studentDetail->father_name}}</td>
            </tr>
            <tr>
                <td>Occupation : {{$scholarship->studentDetail->father_occupation}}</td>
            </tr>
            <tr>
                <td>3. Mother Name: {{$scholarship->studentDetail->mother_name}}</td>
            </tr>
            <tr>
                <td>Occupation : {{$scholarship->studentDetail->mother_occupation}}</td>
            </tr>
            <tr>
                <td>4. Full Address </td>
                <td></td>
            </tr>
            <tr>
                <td>House no.  : {{$scholarship->studentDetail->house_no}}</td>
                <td>Street/ Cross :</td>
            </tr>
            <tr>
                <td>Village : {{$scholarship->studentDetail->scholarshipVillage->name}}</td>
                <td>Post office :</td>
            </tr>
            <tr>
                <td>Taluk : {{$scholarship->studentDetail->taluk}}</td>
                <td>District :</td>
            </tr>
            <tr>
                <td>Pin code : {{$scholarship->studentDetail->pincode}}</td>
                <td>State :</td>
            </tr>
            <tr>
                <td>5. Contact no. 1 :</td>
                <td>Contact no : </td>
            </tr>

            <tr>
                <td>6. Date of Birth : </td>
                <td>Age : </td>
            </tr>
            <tr>
                <td colspan="2">7. Male/ Female:</td>
            </tr>
            <tr>
                <td colspan="2">8. Aadhar no. : </td>
            </tr>
        </table>
        
    </div>

    <div class="row mt-5">
        <div class="col-sm-12">
            <h6 class="font-weight-bold"> DETAILS OF STUDIED SCHOOL / COLLEGE :</h6>
        </div>

        <div class="col-sm-12">
           <p>Govt. / Govt. Aided / Private: </p>
        </div>

        <div class="col-sm-12">
           <div class="row">
            <div class="col-sm-4">
                <p>Village:</p>
            </div>
            <div class="col-sm-4">
                Taluk : 
            </div>
            <div class="col-sm-4">
                <p>District:</p>
            </div>
           </div>
        </div>

        
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <p>Marks Obtained in last Examination  SSLC/PUC/Degree: </p>
                </div>
                <div class="col-sm-6">
                    <p>Year :</p>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <p>If PUC / Degree please specify the subject :</p>
        </div>

        <div class="col-sm-6">Grade/ Class :</div>
        <div class="col-sm-6">Percentage :</div>
        <div class="col-sm-6">Contact Person:</div>
        <div class="col-sm-6">Designation :</div>
        <div class="col-sm-6">Contact no. : </div>
        <div class="col-sm-6">Seal & Signature:</div>
        

    </div>


    <div class="row mt-5">
        <div class="col-sm-12">
            <h6 class="font-weight-bold"> FURTHER EDUCATION DETAILS :</h6>
        </div>
        <div class="col-sm-12">
           <p> Course Joined :</p>
        </div>

        <div class="col-sm-12">
           <p> College /Institute : </p>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-sm-12">
            <h6 class="font-weight-bold"> DECLARATION OF STUDENT </h6>
        </div>
        <div class="col-sm-12">
           <p> 1. I certified that the information given in above istrue and correct.</p>
           <p> 2. I am not availing any otherscholarship forthis purpose from any NGO/State/Central Govt.</p>
           <p>3. If the information given by me isfound to be false/incorrect, the scholarship sanction to me may be
            cancelled and the amount of scholarship refunded by me</p>
        </div>

        <div class="col-sm-4"><p> Date : </p></div>
        <div class="col-sm-4"><p> Student Signature  </p></div>
        <div class="col-sm-4"><p> Parents/ Guardian Signature  </p></div>
        <div class="col-sm-12"><p> Place : </p></div>
    </div>

    <div class="row mt-5">
        <div class="col-sm-12">
            <h6 class="font-weight-bold">DOCUMENTS TO BE ATTACH :</h6>
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
            <h6 class="font-weight-bold">FOR OFFICE USE` :</h6>
        </div>
        <div class="col-sm-12">
           <p> 1. Applicant Selected / Not selected : </p>
           <p> 2. If Not selected : </p>
           <p> 3. Sanctioned amount : </p>
           <p>I, [Name of Principal], being first duly sworn, hereby state that: I am the principal of [Name of School], located at
            [Address of School]. [Student's Name], date of birth [Student's Date of Birth], is a student at [Name of School]
            and is enrolled in the [Grade Level] grade. This affidavit is being executed for the purpose of verifying [Student's
            Name]'s complete information provided above in the portal is true and attendance for the purpose of applying
            for a scholarship.
            <p class="mt-5">I declare under penalty of perjury that the foregoing is true and correct.</p>
        </p>
        </div>
        <div class="col-sm-4"><p>Date: </p></div>
        <div class="col-sm-4"><p>Signature </p></div>
        <div class="col-sm-4"><p>Seal </p></div>
    </div>

    <div class="row my-5">
        <div class="col-sm-12">
            <h6 class="font-weight-bold">SANSERA FOUNDATION</h6>
            <p>No 143/A, Jirani link Road Near OMEX Circle Bengaluru 560099,Mobil No: 9845620096</p>
            <img src="./img/Screenshot from 2023-01-28 22-22-18.png" alt="">
        </div>
    </div>

</div>
@endsection