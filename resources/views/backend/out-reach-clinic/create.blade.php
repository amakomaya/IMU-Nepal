@extends('backend.out-reach-clinic.form')

    @section('action',route('out-reach-clinic.store'))

    @section('methodField', "")

    @section ('name',old('name'))

    @php($province_id= old('province_id')) 

    @php($district_id= old('district_id')) 

    @php($municipality_id= old('municipality_id')) 

    @section ('ward_no',$ward_no)

    @section ('hp_code',$hp_code)

    @section ('address',old('address'))

    @section ('phone',old('phone'))

    @section ('longitude',old('longitude'))

    @section ('lattitude',old('lattitude'))

    @php($status= old('status')) 