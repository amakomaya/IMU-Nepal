@extends('backend.health-worker.form')

    @if($role=='healthworker')
        @section('action',route('health-worker.update', $data->id))
    @else
        @section('action',route('fchv.update', $data->id))
    @endif

    @section('methodField',method_field('PUT'))

    @section ('token',!!old('token')? old('token') : $data->token)

    @section ('name',!!old('name')? old('name') : $data->name)

    @section ('post',!!old('post')? old('post') : $data->post)

    @php($province_id = !!old('province_id')? old('province_id') : $data->province_id)

    @php($district_id = !!old('district_id')? old('district_id') : $data->district_id)

    @php($municipality_id = !!old('municipality_id')? old('municipality_id') : $data->municipality_id)

    @php($ward = !!old('ward')? old('ward') : $data->ward)

    @php($hp_code = !!old('hp_code')? old('hp_code') : $data->hp_code)

    @section ('phone',!!old('phone')? old('phone') : $data->phone)

    @section ('tole',!!old('tole')? old('tole') : $data->tole)

    @section ('registered_device',!!old('registered_device')? old('registered_device') : $data->registered_device)

    @section ('role',!!old('role')? old('role') : $data->role)

    @section ('imei',!!old('imei')? old('imei') : $user->imei)

    @section ('longitude',!!old('longitude')? old('longitude') : $data->longitude)

    @section ('latitude',!!old('lattitude')? old('lattitude') : $data->latitude)

    @php($status = !!old('status')? old('status') : $data->status)

    @section ('email',!!old('email')? old('email') : $user->email)