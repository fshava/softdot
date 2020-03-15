@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' .'Receipts')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <enrolment-component></enrolment-component> {{-- find definition in js/frontend/componets/EnrolementComponent.vue --}}
            <debtors-component></debtors-component>     {{-- find definition in js/frontend/componets/DebtorsComponent.vue --}}
            <creditors-component></creditors-component> {{-- find definition in js/frontend/componets/CreditorsComponent.vue --}}
            <financial-component></financial-component> {{-- find definition in js/frontend/componets/FinancialComponent.vue --}}
        </div><!--col-->
    </div><!--row-->
@endsection
