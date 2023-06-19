@extends('layouts.layout')
@section('content')
    <div class="container">
        <div class="row mt-1">
            <div class="col-sm-12">
                <div class="text-center">
                    <img src="{{ asset('pdf/img/logo.png') }}" class="img-fluid" alt="...">
                </div>
                <p class="font-weight-bold text-justify text-center"><u>The Rotary Bangalore Midtown in association with
                        Sansera Foundation invites application
                        from Students who have secured marks in excess of 60% in 10th standard exam last held.</u></p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-3">
                <div>
                    <span>Application No. : {{ $scholarship->application_no }}</span>
                </div>
            </div>

            <div class="col-sm-3">
                <div>
                    <span>Year : {{ $scholarship->year }}</span>
                </div>
            </div>

            <div class="col-sm-3">
                <div>
                    <span> Fee Amount: {{ $scholarship->apply_amount }}</span>
                </div>
            </div>

            <div class="col-sm-3">
                <div>
                    <span> Eligibility Amount: {{ $eAmount }}</span>
                </div>
            </div>

        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">PERSONAL DETAILS</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-borderless p-0 m-0">
                        <tr>
                            <td>1. Full Name : </td>
                            <td> : </td>
                            <td>{{ $scholarship->studentDetail->full_name }}</td>
                            <td rowspan="5">
                                <div class="text-right">
                                    {{-- <img width="200" height="200" src="{{asset('img/profile/male.png')}}" class="rounded" alt="prfile"> --}}
                                    <img width="200" height="200" src="{{ asset($scholarship->photo) }}"
                                        class="rounded" alt="prfile">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2. Father Name </td>
                            <td>: </td>
                            <td>{{ $scholarship->studentDetail->father_name }}</td>
                        </tr>
                        <tr>
                            <td><span class="ml-3">Occupation</span> </td>
                            <td> : </td>
                            <td>{{ $scholarship->studentDetail->father_occupation }}</td>
                        </tr>
                        <tr>
                            <td>3. Mother Name</td>
                            <td> : </td>
                            <td>{{ $scholarship->studentDetail->mother_name }}</td>
                        </tr>
                        <tr>
                            <td><span class="ml-3">Occupation</span></td>
                            <td> : </td>
                            <td> {{ $scholarship->studentDetail->mother_occupation }}</td>
                        </tr>
                        <tr>
                            <td>4. Annual Income</td>
                            <td>:</td>
                            <td> {{ $scholarship->annual_income }}</td>
                        </tr>
                        <tr>
                            <td colspan="4">5. Full Address </td>

                        </tr>
                        <tr>
                            <td> <span class="ml-3">House no.</span> </td>
                            <td>:</td>
                            <td>{{ $scholarship->studentDetail->house_no }}</td>
                            <td>Street/ Cross </td>
                            <td>:</td>
                            <td>{{ $scholarship->studentDetail->state }}</td>
                        </tr>
                        <tr>
                            <td> <span class="ml-3">Village</span> </td>
                            <td> : </td>
                            <td>{{ $scholarship->studentDetail->scholarshipVillage->name }}</td>
                            <td>Post office :</td>
                            <td> : </td>
                            <td>{{ $scholarship->studentDetail->post_office }}</td>
                        </tr>
                        <tr>
                            <td><span class="ml-3">Taluk</span> </td>
                            <td> : </td>
                            <td>{{ $scholarship->studentDetail->taluk }}</td>
                            <td>District :</td>
                            <td> : </td>
                            <td>{{ $scholarship->studentDetail->district }}</td>
                        </tr>
                        <tr>
                            <td><span class="ml-3">Pin code</span> </td>
                            <td> : </td>
                            <td>{{ $scholarship->studentDetail->pincode }}</td>
                            <td>State </td>
                            <td> : </td>
                            <td>{{ $scholarship->studentDetail->state }}</td>
                        </tr>
                        <tr>
                            <td>6. Contact no. 1 :</td>
                            <td>:</td>
                            <td>{{ $scholarship->studentDetail->contact_no_1 }}</td>
                            <td>Contact no : </td>
                            <td>:</td>
                            <td>{{ $scholarship->studentDetail->contact_no_2 }}</td>
                        </tr>

                        <tr>
                            <td>7. Date of Birth </td>
                            <td>:</td>
                            <td>{{ $scholarship->studentDetail->date_of_birth }}</td>
                            <td>Age </td>
                            <td>:</td>
                            <td>{{ $scholarship->studentDetail->age }}</td>
                        </tr>
                        <tr>
                            <td>8. Male/ Female </td>
                            <td>:</td>
                            <td colspan="4">{{ $scholarship->studentDetail->gender }}</td>
                        </tr>
                        <tr>
                            <td>9. Aadhar no. </td>
                            <td>:</td>
                            <td colspan="4">{{ $scholarship->studentDetail->aadhar_no }}</td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">DETAILS OF STUDIED SCHOOL / COLLEGE</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $scholl_college_data = $scholarship->school_or_college == 1 ? $scholarship->schoolDetail : $scholarship->collegeDetail;
                    @endphp
                    <div class="col-sm-12">
                        <table class="table table-borderless p-0 m-0">
                            <tbody>
                                @if ($scholarship->school_or_college == 1)
                                    <tr>
                                        <td>Govt. / Govt. Aided / Private</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $scholl_college_data->school_type }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>Govt. / Govt. Aided / Private</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $scholl_college_data->college_type }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td> Village </td>
                                    <td>: </td>
                                    <td>{{ $scholl_college_data->scholarshipVillage->name }}</td>

                                    <td> Taluk </td>
                                    <td>: </td>
                                    <td>{{ $scholarship->studentDetail->taluk }}</td>

                                    <td> District </td>
                                    <td>: </td>
                                    <td>{{ $scholl_college_data->district }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">Marks Obtained in last Examination SSLC/PUC/Degree</td>
                                    <td> : </td>
                                    <td>{{ $scholarship->marks_obtained }}</td>

                                    <td>Year</td>
                                    <td> : </td>
                                    <td>{{ $scholarship->year }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">If PUC / Degree please specify the subject </td>
                                    <td>:</td>
                                    <td colspan="4">{{ $scholarship->marks_obtained_type }}</td>
                                </tr>
                                <tr>
                                    <td>Grade/ Class</td>
                                    <td>:</td>
                                    <td colspan="2">{{ $scholarship->classDetail->name }}</td>

                                    <td colspan="2">Percentage</td>
                                    <td>:</td>
                                    <td colspan="2">{{ $scholarship->percentage_marks_obtained }}</td>
                                </tr>

                                <tr>
                                    <td>Contact Person:</td>
                                    <td>:</td>
                                    <td colspan="2">{{ $scholarship->school_contact_person }}</td>
                                    <td colspan="2">Designation</td>
                                    <td>:</td>
                                    <td colspan="2">{{ $scholarship->school_designation }}</td>
                                </tr>

                                <tr>
                                    <td>Contact no.</td>
                                    <td>:</td>
                                    <td colspan="2">{{ $scholarship->school_contact_number }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="card  mt-3">
            <div class="card-header">
                <h3 class="card-title">FURTHER EDUCATION DETAILS </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p> Course Joined : {{ $scholarship->further_education_details_course_joined }}</p>
                    </div>

                    <div class="col-sm-12">
                        @if ($scholarship->further_education_details_school_or_college == '1')
                            <p> School /Institute : {{ $scholarship->furtherEducationschollDetail->name }}</p>
                        @else
                            <p> College /Institute : {{ $scholarship->furtherEducationCollegeDetail->name }}</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">DOCUMENTS TO BE ATTACH</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p> <a href="{{ asset($scholarship->income_certificate) }}" target="_blank"> 1. Income
                                certificate of Parents/ Guardian </a></p>
                        <p> <a href="{{ asset($scholarship->id_proof) }}" target="_blank">
                                2. Any Govt. ID proof (Aadhar, Ration card etc.)</a></p>
                        <p> <a href="{{ asset($scholarship->previous_educational_marks_card) }}"
                                target="_blank">
                                3. Previous educational marks card </a></p>
                        <p> <a href="{{ asset($scholarship->original_fee_receipt) }}" target="_blank">
                                4. Original fee receipt </a></p>
                        <p> <a href="{{ asset($scholarship->bank_passbook) }}" target="_blank">
                                5. Bank passbook copy </a></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">DECLARATION OF STUDENT </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p> 1. I certified that the information given in above istrue and correct.</p>
                        <p> 2. I am not availing any otherscholarship forthis purpose from any NGO/State/Central Govt.</p>
                        <p>3. If the information given by me isfound to be false/incorrect, the scholarship sanction to me
                            may be
                            cancelled and the amount of scholarship refunded by me</p>
                    </div>


                </div>
            </div>
        </div>

    </div>
@endsection
