@extends('backend.woman.delivery.baby-detail.form')

    @section('action',route('baby-detail.store'))

    @section('methodField', "")

    @section ('token',old('token'))

    @section ('delivery_token',old('delivery_token'))

    @php($gender= old('gender')) 

    @section ('weight',old('weight'))

    @section ('premature_birth',old('premature_birth'))

    @section ('baby_alive',old('baby_alive'))

    @section ('baby_status',old('baby_status'))

    @section ('advice',old('advice'))