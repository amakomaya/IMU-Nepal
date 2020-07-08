@extends('backend.center.form')

    @section('action',route('center.update', $data->id))

    @section('methodField',method_field('PUT'))

    @section ('name',!!old('name')? old('name') : $data->name)

    @section ('token',!!old('token')? old('token') : $data->token)

    @section ('phone',!!old('phone')? old('phone') : $data->phone)

    @section ('email',!!old('email')? old('email') : $data->email)

    @section ('office_address',!!old('office_address')? old('office_address') : $data->office_address)

    @section ('office_longitude',!!old('office_longitude')? old('office_longitude') : $data->office_longitude)

    @section ('office_lattitude',!!old('office_lattitude')? old('office_lattitude') : $data->office_lattitude)

    @php($status = !!old('status')? old('status') : $data->status)

    @section ('email',!!old('email')? old('email') : $user->email)