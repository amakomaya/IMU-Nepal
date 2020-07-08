@extends('backend.center.form')

    @section('action',route('center.store'))

    @section('methodField', "")

    @section ('name',old('name'))

    @section ('phone',old('phone'))

    @section ('office_address',old('office_address'))

    @section ('office_longitude',old('office_longitude'))

    @section ('office_lattitude',old('office_lattitude'))

    @php($status= old('status')) 

    @section ('username',old('username'))

    @section ('email',old('email'))

    @section ('password',old('password'))

    @section ('re_password',old('re_password'))