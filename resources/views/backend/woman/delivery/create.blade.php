@extends('backend.woman.delivery.form')

    @section('action',route('delivery.store'))

    @section('methodField', "")

    @section ('token',old('token'))

    @section ('woman_token',old('woman_token'))

    @section ('delivery_date',old('delivery_date'))

    @section ('delivery_time',old('delivery_time'))

    @section ('delivery_place',old('delivery_place'))

    @section ('presentation',old('presentation'))

    @section ('delivery_type',old('delivery_type'))

    @section ('compliexicty',old('compliexicty'))

    @section ('other_problem',old('other_problem'))

    @section ('advice',old('advice'))
    
    @php($miscarriage_status= old('miscarriage_status')) 

    @php($hp_code= old('hp_code')) 

    @section ('delivery_by_token',old('delivery_by_token'))

    @php($status= old('status')) 